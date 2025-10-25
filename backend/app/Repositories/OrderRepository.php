<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getUserOrders($userId, $perPage = 10)
    {
        return $this->model->where('user_id', $userId)
            ->with(['items.product', 'shippingAddress', 'payment', 'shipment'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getOrderByNumber($orderNumber)
    {
        return $this->model->where('order_number', $orderNumber)
            ->with(['items.product', 'user', 'shippingAddress', 'billingAddress', 'payment', 'shipment'])
            ->firstOrFail();
    }

    public function getAllOrders($filters = [], $perPage = 20)
    {
        $query = $this->model->with(['user', 'items', 'payment', 'shipment']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function updateStatus($orderId, $status)
    {
        $order = $this->findOrFail($orderId);
        $order->update(['status' => $status]);
        return $order;
    }

    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        $order = $this->findOrFail($orderId);
        $order->update(['payment_status' => $paymentStatus]);
        return $order;
    }
}
