<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get user's orders
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $orders = $this->orderService->getUserOrders($userId);

        return OrderResource::collection($orders);
    }

    /**
     * Create a new order
     */
    public function store(CreateOrderRequest $request)
    {
        try {
            $userId = Auth::check() ? Auth::id() : null;

            // Permitir pedidos sin autenticación (guest checkout)
            // Los datos del cliente vienen en el request
            $order = $this->orderService->createOrder($userId, $request->validated());

            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order by order number
     */
    public function show(string $orderNumber)
    {
        try {
            $order = $this->orderService->getOrderByNumber($orderNumber);

            // Verificar que el usuario tenga acceso a este pedido
            // Permitir acceso si: es el dueño de la orden O es admin autenticado
            if ($order->user_id !== Auth::id()) {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                if (!$user || !$user->isAdmin()) {
                    return response()->json(['message' => 'No autorizado'], 403);
                }
            }

            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, string $id)
    {
        // Verificar que el usuario tenga permisos de manager o admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasManagerAccess()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        try {
            $order = $this->orderService->updateOrderStatus($id, $request->status);

            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(string $id)
    {
        try {
            $order = $this->orderService->cancelOrder($id);

            return response()->json([
                'message' => 'Pedido cancelado exitosamente',
                'data' => new OrderResource($order)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cancelar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all orders (admin only)
     */
    public function adminIndex(Request $request)
    {
        // Verificar que el usuario tenga permisos de manager o admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasManagerAccess()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $filters = $request->only(['status', 'payment_status', 'from_date', 'to_date']);
        $orders = $this->orderService->getAllOrders($filters);

        return OrderResource::collection($orders);
    }

    /**
     * Export orders to Excel (admin only)
     */
    public function exportExcel(Request $request)
    {
        // Verificar que el usuario tenga permisos de manager o admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasManagerAccess()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $filename = 'ordenes_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new OrdersExport($startDate, $endDate, $status),
            $filename
        );
    }

    /**
     * Export orders to PDF (admin only)
     */
    public function exportPdf(Request $request)
    {
        // Verificar que el usuario tenga permisos de manager o admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasManagerAccess()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $export = new OrdersExport($startDate, $endDate, $status);
        $orders = $export->collection();

        $pdf = Pdf::loadView('exports.orders', [
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status
        ]);

        $filename = 'ordenes_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get orders pending shipment (admin only)
     * Órdenes que están listas para despachar (status processing y sin shipment)
     */
    public function pendingShipment(Request $request)
    {
        // Verificar que el usuario tenga permisos de manager o admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasManagerAccess()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $orders = \App\Models\Order::with(['user', 'shippingAddress', 'items.product'])
            ->whereIn('status', ['pending', 'processing'])
            ->whereDoesntHave('shipment')
            ->where('payment_status', 'paid') // Solo órdenes pagadas
            ->orderBy('created_at', 'asc') // Más antiguas primero
            ->paginate($request->get('per_page', 20));

        return OrderResource::collection($orders);
    }

    /**
     * Get orders that have been shipped (admin only)
     * Órdenes que ya fueron despachadas (tienen shipment)
     */
    public function shipped(Request $request)
    {
        // Verificar que el usuario tenga permisos de manager o admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasManagerAccess()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $orders = \App\Models\Order::with(['user', 'shippingAddress', 'items.product', 'shipment'])
            ->whereHas('shipment')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return OrderResource::collection($orders);
    }

    /**
     * Get shipping statistics (admin only)
     * Estadísticas de despacho para el dashboard
     */
    public function shippingStats(Request $request)
    {
        // Verificar que el usuario tenga permisos de manager o admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasManagerAccess()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Usar query base para evitar duplicación
        $baseQuery = \App\Models\Order::whereIn('status', ['pending', 'processing'])
            ->whereDoesntHave('shipment')
            ->where('payment_status', 'paid');

        $pendingShipment = (clone $baseQuery)->count();

        $readyToShip = (clone $baseQuery)
            ->where('status', 'processing')
            ->count();

        $shipped = \App\Models\Order::whereHas('shipment')
            ->whereIn('status', ['shipped', 'delivered'])
            ->count();

        $inTransit = \App\Models\Order::whereHas('shipment', function($query) {
            $query->where('status', 'in_transit');
        })->count();

        $delivered = \App\Models\Order::where('status', 'delivered')->count();

        // Órdenes más antiguas sin despachar con eager loading
        $oldestPending = (clone $baseQuery)
            ->with('user') // Eager loading para evitar N+1
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'order_number' => $order->order_number,
                    'customer' => $order->user ? $order->user->name : 'N/A',
                    'total' => $order->total,
                    'days_waiting' => now()->diffInDays($order->created_at),
                    'created_at' => $order->created_at->format('Y-m-d H:i')
                ];
            });

        return response()->json([
            'pending_shipment' => $pendingShipment,
            'ready_to_ship' => $readyToShip,
            'shipped' => $shipped,
            'in_transit' => $inTransit,
            'delivered' => $delivered,
            'oldest_pending' => $oldestPending,
        ]);
    }
}
