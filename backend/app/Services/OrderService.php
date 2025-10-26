<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Address;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmation;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
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

            // Usar los totales que vienen del frontend (ya calculados correctamente)
            $subtotal = $orderData['totals']['subtotal'];
            $tax = $orderData['totals']['tax'];
            $shippingCost = $orderData['totals']['shipping'];
            $total = $orderData['totals']['total'];

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
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $orderData['shipping']['notes'] ?? null,
            ]);

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

            // Enviar email de confirmación
            try {
                Mail::to($orderData['customer']['email'])
                    ->send(new OrderConfirmation($order));
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
