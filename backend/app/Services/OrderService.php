<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmation;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;
    protected $shippingCalculator;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        ShippingCalculatorService $shippingCalculator
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->shippingCalculator = $shippingCalculator;
    }

    public function getUserOrders($userId, $perPage = 10)
    {
        return $this->orderRepository->getUserOrders($userId, $perPage);
    }

    public function getOrderByNumber($orderNumber)
    {
        return $this->orderRepository->getOrderByNumber($orderNumber);
    }

    public function getAllOrders($filters = [], $perPage = 20)
    {
        return $this->orderRepository->getAllOrders($filters, $perPage);
    }

    public function createOrder($userId, array $orderData)
    {
        return DB::transaction(function () use ($userId, $orderData) {
            // Si no hay usuario autenticado, crear uno como guest
            if (!$userId) {
                $user = User::firstOrCreate(
                    ['email' => $orderData['customer']['email']],
                    [
                        'name' => $orderData['customer']['name'],
                        'email' => $orderData['customer']['email'],
                        'phone' => $orderData['customer']['phone'],
                        'document' => $orderData['customer']['document'],
                        'password' => bcrypt(Str::random(32)), // Password aleatorio para guest
                        'role' => 'customer',
                    ]
                );
                $userId = $user->id;
            }

            // Crear dirección de envío
            $shippingAddress = Address::create([
                'user_id' => $userId,
                'full_name' => $orderData['customer']['name'],
                'phone' => $orderData['customer']['phone'],
                'address_line_1' => $orderData['shipping']['address'],
                'address_line_2' => null,
                'city' => $orderData['shipping']['city'],
                'state' => $orderData['shipping']['state'],
                'postal_code' => $orderData['shipping']['zipCode'] ?? '000000',
                'country' => 'Colombia',
                'latitude' => $orderData['shipping']['latitude'] ?? null,
                'longitude' => $orderData['shipping']['longitude'] ?? null,
                'type' => 'shipping',
                'is_default' => false,
            ]);

            // Verificar stock de todos los productos
            $items = $orderData['items'];
            foreach ($items as $item) {
                $product = $this->productRepository->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }
            }

            // Validar el costo de envío recalculando en el backend
            $calculatedShippingCost = $this->shippingCalculator->calculateFromOrderData($orderData);
            $frontendShippingCost = $orderData['totals']['shipping'];

            // Permitir una diferencia mínima por redondeo
            if (abs($frontendShippingCost - $calculatedShippingCost) > 0.01) {
                throw new \Exception(
                    "El costo de envío no es válido. Esperado: $" . number_format($calculatedShippingCost, 0) .
                    ", Recibido: $" . number_format($frontendShippingCost, 0)
                );
            }

            // Usar los totales validados
            $subtotal = $orderData['totals']['subtotal'];
            $tax = $orderData['totals']['tax'];
            $shippingCost = $calculatedShippingCost; // Usar el costo calculado en backend
            $discount = $orderData['totals']['discount'] ?? 0;
            $total = $orderData['totals']['total'];

            // Procesar cupón si existe
            $couponId = null;
            $couponCode = null;
            if (isset($orderData['coupon_code']) && !empty($orderData['coupon_code'])) {
                // Usar lockForUpdate para prevenir race conditions
                $coupon = Coupon::where('code', strtoupper($orderData['coupon_code']))
                    ->lockForUpdate()
                    ->first();

                if ($coupon && $coupon->isValid()) {
                    // Verificar monto mínimo
                    if ($coupon->min_purchase_amount && $subtotal < $coupon->min_purchase_amount) {
                        throw new \Exception("El monto mínimo para este cupón es $" . number_format($coupon->min_purchase_amount, 0));
                    }

                    // Verificar límite por usuario si existe
                    if ($coupon->max_usage_per_customer) {
                        $userUsageCount = \App\Models\CouponRedemption::where('coupon_id', $coupon->id)
                            ->where('user_id', $userId)
                            ->count();

                        if ($userUsageCount >= $coupon->max_usage_per_customer) {
                            throw new \Exception("Ya has usado este cupón el máximo de veces permitidas");
                        }
                    }

                    $couponId = $coupon->id;
                    $couponCode = $coupon->code;

                    // Incrementar contador de uso de forma segura
                    $coupon->increment('usage_count');
                }
            }

            // Mapear método de pago del frontend al backend
            $paymentMethodMap = [
                'card' => 'stripe',
                'pse' => 'payu',
                'cash' => 'cash_on_delivery'
            ];
            $paymentMethod = $paymentMethodMap[$orderData['payment']['method']] ?? 'stripe';

            // Crear orden
            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $shippingAddress->id,
                'coupon_id' => $couponId,
                'coupon_code' => $couponCode,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $orderData['shipping']['notes'] ?? null,
                'night_delivery' => $orderData['shipping']['nightDelivery'] ?? false,
            ]);

            // Registrar redención del cupón si se usó
            if ($couponId) {
                \App\Models\CouponRedemption::create([
                    'coupon_id' => $couponId,
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                ]);
            }

            // Crear items de la orden
            foreach ($items as $item) {
                $product = $this->productRepository->findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Reducir stock
                $this->productRepository->updateStock($product->id, $item['quantity']);
            }

            // Crear registro de pago
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                'payment_method' => $paymentMethod,
                'amount' => $total,
                'status' => 'pending',
                'payment_details' => json_encode([
                    'method' => $orderData['payment']['method'],
                    'amount' => $orderData['payment']['amount']
                ])
            ]);

            // Cargar relaciones necesarias para el email
            $order->load(['items', 'shippingAddress', 'billingAddress', 'user']);

            // Enviar email de confirmación (verificar preferencias del usuario)
            try {
                $user = User::find($userId);

                // Solo enviar si el usuario tiene habilitadas las notificaciones por email
                if ($user && $user->email_notifications) {
                    Mail::to($orderData['customer']['email'])
                        ->send(new OrderConfirmation($order));
                }
            } catch (\Exception $e) {
                // Log el error pero no falla la orden
                \Log::error('Error enviando email de confirmación: ' . $e->getMessage());
            }

            return $order;
        });
    }

    public function updateOrderStatus($orderId, $status)
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Estado de orden inválido');
        }

        return $this->orderRepository->updateStatus($orderId, $status);
    }

    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        $validStatuses = ['pending', 'paid', 'failed', 'refunded'];

        if (!in_array($paymentStatus, $validStatuses)) {
            throw new \Exception('Estado de pago inválido');
        }

        return $this->orderRepository->updatePaymentStatus($orderId, $paymentStatus);
    }

    public function cancelOrder($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if ($order->status === 'delivered') {
            throw new \Exception('No se puede cancelar una orden ya entregada');
        }

        return DB::transaction(function () use ($order) {
            // Devolver stock
            foreach ($order->items as $item) {
                $product = $this->productRepository->findOrFail($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            // Actualizar estado
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'refunded',
            ]);

            return $order;
        });
    }
}
