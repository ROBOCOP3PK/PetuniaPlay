<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order_number' => $this->order?->order_number,
            'tracking_number' => $this->tracking_number,
            'carrier' => $this->carrier,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'shipped_at' => $this->shipped_at?->format('Y-m-d H:i:s'),
            'delivered_at' => $this->delivered_at?->format('Y-m-d H:i:s'),
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            // Relaciones
            'order' => $this->whenLoaded('order', function () {
                return [
                    'id' => $this->order->id,
                    'order_number' => $this->order->order_number,
                    'total' => $this->order->total,
                    'status' => $this->order->status,
                    'user' => $this->whenLoaded('order.user', function () {
                        return [
                            'id' => $this->order->user->id,
                            'name' => $this->order->user->name,
                            'email' => $this->order->user->email,
                        ];
                    }),
                    'shipping_address' => $this->whenLoaded('order.shippingAddress', function () {
                        return [
                            'full_name' => $this->order->shippingAddress->full_name,
                            'phone' => $this->order->shippingAddress->phone,
                            'address_line_1' => $this->order->shippingAddress->address_line_1,
                            'city' => $this->order->shippingAddress->city,
                            'state' => $this->order->shippingAddress->state,
                            'postal_code' => $this->order->shippingAddress->postal_code,
                            'country' => $this->order->shippingAddress->country,
                        ];
                    }),
                    'items' => $this->whenLoaded('order.items', function () {
                        return $this->order->items->map(function ($item) {
                            return [
                                'product_name' => $item->product_name,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                            ];
                        });
                    }),
                ];
            }),

            // Información calculada
            'days_in_transit' => $this->getDaysInTransit(),
            'is_delayed' => $this->isDelayed(),
        ];
    }

    /**
     * Obtener etiqueta del estado
     */
    private function getStatusLabel()
    {
        $labels = [
            'pending' => 'Pendiente',
            'in_transit' => 'En Tránsito',
            'delivered' => 'Entregado',
            'failed' => 'Fallido',
            'returned' => 'Devuelto',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Calcular días en tránsito
     */
    private function getDaysInTransit()
    {
        if (!$this->shipped_at) {
            return null;
        }

        $endDate = $this->delivered_at ?? now();
        return $this->shipped_at->diffInDays($endDate);
    }

    /**
     * Verificar si está retrasado (más de 7 días en tránsito)
     */
    private function isDelayed()
    {
        if ($this->status === 'delivered' || !$this->shipped_at) {
            return false;
        }

        return $this->shipped_at->diffInDays(now()) > 7;
    }
}
