<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

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
            // Calcular totales
            $subtotal = 0;
            $items = $orderData['items'];

            foreach ($items as $item) {
                $product = $this->productRepository->findOrFail($item['product_id']);

                // Verificar stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }

                $itemSubtotal = $product->final_price * $item['quantity'];
                $subtotal += $itemSubtotal;
            }

            // Aplicar descuento si hay cupón
            $discount = $orderData['discount'] ?? 0;
            $tax = $orderData['tax'] ?? ($subtotal * 0.19); // 19% IVA Colombia
            $shippingCost = $orderData['shipping_cost'] ?? 0;
            $total = $subtotal + $tax + $shippingCost - $discount;

            // Crear orden
            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'shipping_address_id' => $orderData['shipping_address_id'],
                'billing_address_id' => $orderData['billing_address_id'] ?? $orderData['shipping_address_id'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $orderData['notes'] ?? null,
            ]);

            // Crear items de la orden
            foreach ($items as $item) {
                $product = $this->productRepository->findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $product->final_price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->final_price * $item['quantity'],
                ]);

                // Reducir stock
                $this->productRepository->updateStock($product->id, $item['quantity']);
            }

            return $order->load(['items', 'shippingAddress', 'billingAddress']);
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
