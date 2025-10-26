<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Console\Command;

class CheckLowStockProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica productos con stock bajo y envía notificaciones a administradores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando productos con stock bajo...');

        // Obtener productos con stock bajo
        $lowStockProducts = Product::lowStock()->with('category')->get();
        $outOfStockProducts = Product::outOfStock()->with('category')->get();

        if ($lowStockProducts->isEmpty() && $outOfStockProducts->isEmpty()) {
            $this->info('✓ No hay productos con stock bajo o agotados.');
            return 0;
        }

        // Obtener todos los managers y admins
        $admins = User::whereIn('role', ['manager', 'admin'])->get();

        if ($admins->isEmpty()) {
            $this->warn('⚠ No hay administradores en el sistema para notificar.');
            return 0;
        }

        $notificationsSent = 0;

        // Crear notificaciones para productos con stock bajo
        foreach ($lowStockProducts as $product) {
            foreach ($admins as $admin) {
                // Verificar si ya existe una notificación reciente (últimas 24 horas)
                $existingNotification = Notification::where('user_id', $admin->id)
                    ->where('type', 'low_stock')
                    ->where('data->product_id', $product->id)
                    ->where('created_at', '>=', now()->subDay())
                    ->exists();

                if (!$existingNotification) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'low_stock',
                        'title' => '⚠️ Stock Bajo',
                        'message' => "El producto '{$product->name}' tiene stock bajo ({$product->stock} unidades, umbral: {$product->low_stock_threshold})",
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
                    $notificationsSent++;
                }
            }
        }

        // Crear notificaciones para productos agotados
        foreach ($outOfStockProducts as $product) {
            foreach ($admins as $admin) {
                // Verificar si ya existe una notificación reciente (últimas 24 horas)
                $existingNotification = Notification::where('user_id', $admin->id)
                    ->where('type', 'out_of_stock')
                    ->where('data->product_id', $product->id)
                    ->where('created_at', '>=', now()->subDay())
                    ->exists();

                if (!$existingNotification) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'out_of_stock',
                        'title' => '❌ Producto Agotado',
                        'message' => "El producto '{$product->name}' está AGOTADO (SKU: {$product->sku})",
                        'data' => [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'product_sku' => $product->sku,
                            'current_stock' => 0,
                            'category' => $product->category->name ?? 'Sin categoría',
                            'link' => '/admin/products'
                        ]
                    ]);
                    $notificationsSent++;
                }
            }
        }

        $this->info("✓ Verificación completada:");
        $this->line("  - Productos con stock bajo: {$lowStockProducts->count()}");
        $this->line("  - Productos agotados: {$outOfStockProducts->count()}");
        $this->line("  - Notificaciones enviadas: {$notificationsSent}");

        return 0;
    }
}
