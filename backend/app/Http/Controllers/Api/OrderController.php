<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
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
        $userId = $request->user()->id;
        $orders = $this->orderService->getUserOrders($userId);

        return OrderResource::collection($orders);
    }

    /**
     * Create a new order
     */
    public function store(CreateOrderRequest $request)
    {
        try {
            $userId = $request->user() ? $request->user()->id : null;

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
            if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
                return response()->json(['message' => 'No autorizado'], 403);
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
        $filters = $request->only(['status', 'payment_status', 'from_date', 'to_date']);
        $orders = $this->orderService->getAllOrders($filters);

        return OrderResource::collection($orders);
    }

    /**
     * Export orders to Excel (admin only)
     */
    public function exportExcel(Request $request)
    {
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
}
