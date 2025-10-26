<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function dashboard()
    {
        $stats = [
            // Totales
            'total_users' => User::where('role', 'customer')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),

            // Ventas
            'total_revenue' => Order::whereNotIn('status', ['cancelled'])->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),

            // Productos
            'products_low_stock' => Product::where('stock', '<=', 10)->where('stock', '>', 0)->count(),
            'products_out_of_stock' => Product::where('stock', 0)->count(),
            'featured_products' => Product::where('is_featured', true)->count(),

            // Ventas recientes (último mes)
            'monthly_revenue' => Order::where('created_at', '>=', now()->subMonth())
                ->whereNotIn('status', ['cancelled'])
                ->sum('total'),
            'monthly_orders' => Order::where('created_at', '>=', now()->subMonth())->count(),
        ];

        // Últimos pedidos
        $recent_orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Productos con bajo stock
        $low_stock_products = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Productos más vendidos (último mes)
        $top_products = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subMonth())
            ->whereNotIn('orders.status', ['cancelled'])
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'low_stock_products' => $low_stock_products,
            'top_products' => $top_products,
        ]);
    }

    /**
     * Get all users (admin only)
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Update user role (admin only)
     */
    public function updateUserRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:customer,manager,admin'
        ]);

        $user = User::findOrFail($id);

        // No permitir que un admin se quite su propio rol de admin
        if ($user->id === $request->user()->id && $validated['role'] !== 'admin') {
            return response()->json([
                'message' => 'No puedes cambiar tu propio rol de administrador'
            ], 403);
        }

        $user->update(['role' => $validated['role']]);

        return response()->json([
            'data' => $user,
            'message' => 'Rol actualizado exitosamente'
        ]);
    }

    /**
     * Toggle user active status (admin only)
     */
    public function toggleUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // No permitir que un admin se desactive a sí mismo
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'No puedes desactivar tu propia cuenta'
            ], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'data' => $user,
            'message' => $user->is_active ? 'Usuario activado' : 'Usuario desactivado'
        ]);
    }

    /**
     * Get sales statistics
     */
    public function salesStats(Request $request)
    {
        $period = $request->get('period', 'week'); // week, month, year

        $startDate = match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        $sales = Order::where('created_at', '>=', $startDate)
            ->whereNotIn('status', ['cancelled'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'data' => $sales,
            'period' => $period
        ]);
    }
}
