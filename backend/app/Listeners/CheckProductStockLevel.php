<?php

namespace App\Listeners;

use App\Events\ProductStockUpdated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// Listener registrado automáticamente por Laravel 12
// Si Intelephense muestra warning, ignóralo - el atributo existe en Laravel 12
#[\Illuminate\Events\Attributes\ListensTo(ProductStockUpdated::class)]
class CheckProductStockLevel implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductStockUpdated $event): void
    {
        $product = $event->product;
        $previousStock = $event->previousStock;
        $currentStock = $product->stock;

        // Solo notificar si el stock disminuyó (no aumentó)
        if ($currentStock >= $previousStock) {
            return;
        }

        // Verificar si ahora está en bajo stock o agotado
        $isNowLowStock = $product->is_low_stock;
        $isNowOutOfStock = $product->is_out_of_stock;

        // Verificar si pasó a estado crítico
        $wasAboveThreshold = $previousStock > $product->low_stock_threshold;
        $isNowBelowThreshold = $currentStock <= $product->low_stock_threshold;

        // Si pasó de stock normal a bajo stock o agotado, notificar
        if (($wasAboveThreshold && $isNowBelowThreshold) || $isNowOutOfStock) {
            $this->sendNotifications($product, $isNowOutOfStock);
        }
    }

    /**
     * Enviar notificaciones a administradores
     */
    private function sendNotifications($product, $isOutOfStock): void
    {
        // Obtener todos los managers y admins
        $admins = User::whereIn('role', ['manager', 'admin'])->get();

        foreach ($admins as $admin) {
            // Verificar si ya existe una notificación reciente (últimas 6 horas)
            $type = $isOutOfStock ? 'out_of_stock' : 'low_stock';

            $existingNotification = Notification::where('user_id', $admin->id)
                ->where('type', $type)
                ->where('data->product_id', $product->id)
                ->where('created_at', '>=', now()->subHours(6))
                ->exists();

            if (!$existingNotification) {
                $title = $isOutOfStock ? '❌ Producto Agotado' : '⚠️ Stock Bajo';
                $message = $isOutOfStock
                    ? "El producto '{$product->name}' se ha AGOTADO (SKU: {$product->sku})"
                    : "El producto '{$product->name}' tiene stock bajo ({$product->stock} unidades, umbral: {$product->low_stock_threshold})";

                Notification::create([
                    'user_id' => $admin->id,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'data' => [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'current_stock' => $product->stock,
                        'threshold' => $product->low_stock_threshold,
                        'category' => $product->category->name ?? 'Sin categoría',
                        'link' => '/admin/products'
                    ]
                ]);
            }
        }
    }
}
