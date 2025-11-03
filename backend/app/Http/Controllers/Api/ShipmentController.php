<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\ShipmentResource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\ShipmentNotification;

class ShipmentController extends Controller
{
    /**
     * Lista todos los envíos (admin)
     */
    public function index(Request $request)
    {
        $query = Shipment::with(['order.user', 'order.shippingAddress']);

        // Filtros
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('carrier') && $request->carrier !== '') {
            $query->where('carrier', $request->carrier);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        // Ordenar por más recientes
        $query->orderBy('created_at', 'desc');

        $shipments = $query->paginate($request->get('per_page', 20));

        return ShipmentResource::collection($shipments);
    }

    /**
     * Crear un nuevo envío para una orden
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tracking_number' => 'required|string|max:255|unique:shipments,tracking_number',
            'carrier' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Verificar que la orden no tenga ya un envío
        $existingShipment = Shipment::where('order_id', $validated['order_id'])->first();
        if ($existingShipment) {
            return response()->json([
                'message' => 'Esta orden ya tiene un envío asignado'
            ], 422);
        }

        // Crear el envío
        $shipment = Shipment::create([
            'order_id' => $validated['order_id'],
            'tracking_number' => $validated['tracking_number'],
            'carrier' => $validated['carrier'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Actualizar estado de la orden
        $order = Order::findOrFail($validated['order_id']);
        $order->update(['status' => 'processing']);

        // Cargar relaciones
        $shipment->load(['order.user', 'order.shippingAddress']);

        // Enviar email de notificación (verificar preferencias del usuario)
        try {
            if ($order->user && $order->user->email && $order->user->email_notifications) {
                Mail::to($order->user->email)
                    ->send(new ShipmentNotification($shipment));
            }
        } catch (\Exception $e) {
            Log::error('Error enviando email de envío: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Envío creado exitosamente',
            'data' => new ShipmentResource($shipment)
        ], 201);
    }

    /**
     * Obtener un envío específico
     */
    public function show($id)
    {
        $shipment = Shipment::with(['order.user', 'order.shippingAddress', 'order.items.product'])
            ->findOrFail($id);

        return new ShipmentResource($shipment);
    }

    /**
     * Actualizar un envío
     */
    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $validated = $request->validate([
            'tracking_number' => 'sometimes|string|max:255|unique:shipments,tracking_number,' . $id,
            'carrier' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:pending,in_transit,delivered,failed,returned',
            'notes' => 'nullable|string',
        ]);

        // Si el estado cambia a "in_transit", establecer shipped_at
        if (isset($validated['status']) && $validated['status'] === 'in_transit' && !$shipment->shipped_at) {
            $validated['shipped_at'] = now();
        }

        // Si el estado cambia a "delivered", establecer delivered_at
        if (isset($validated['status']) && $validated['status'] === 'delivered' && !$shipment->delivered_at) {
            $validated['delivered_at'] = now();

            // Actualizar estado de la orden
            $shipment->order->update(['status' => 'delivered']);
        }

        $shipment->update($validated);

        // Cargar relaciones
        $shipment->load(['order.user', 'order.shippingAddress']);

        // Si cambia a in_transit o delivered, enviar notificación
        if (isset($validated['status']) && in_array($validated['status'], ['in_transit', 'delivered'])) {
            try {
                if ($shipment->order->user && $shipment->order->user->email) {
                    Mail::to($shipment->order->user->email)
                        ->send(new ShipmentNotification($shipment));
                }
            } catch (\Exception $e) {
                Log::error('Error enviando email de actualización de envío: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Envío actualizado exitosamente',
            'data' => new ShipmentResource($shipment)
        ]);
    }

    /**
     * Eliminar un envío
     */
    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);

        // Solo permitir eliminar si no ha sido enviado
        if ($shipment->shipped_at) {
            return response()->json([
                'message' => 'No se puede eliminar un envío que ya ha sido despachado'
            ], 422);
        }

        $shipment->delete();

        return response()->json([
            'message' => 'Envío eliminado exitosamente'
        ]);
    }

    /**
     * Obtener el tracking de un envío por tracking number (público)
     */
    public function trackByNumber($trackingNumber)
    {
        $shipment = Shipment::with(['order.shippingAddress'])
            ->where('tracking_number', $trackingNumber)
            ->firstOrFail();

        return new ShipmentResource($shipment);
    }

    /**
     * Estadísticas de envíos (admin)
     */
    public function stats()
    {
        $totalShipments = Shipment::count();
        $pendingShipments = Shipment::where('status', 'pending')->count();
        $inTransitShipments = Shipment::where('status', 'in_transit')->count();
        $deliveredShipments = Shipment::where('status', 'delivered')->count();
        $failedShipments = Shipment::whereIn('status', ['failed', 'returned'])->count();

        // Transportadoras más usadas
        // NOTA: Se usa selectRaw() para agregaciones (COUNT). Es más limpio que select() + DB::raw()
        $topCarriers = Shipment::selectRaw('carrier, count(*) as total')
            ->groupBy('carrier')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Tiempo promedio de entrega (días)
        $avgDeliveryTime = Shipment::whereNotNull('shipped_at')
            ->whereNotNull('delivered_at')
            ->selectRaw('AVG(DATEDIFF(delivered_at, shipped_at)) as avg_days')
            ->value('avg_days');

        return response()->json([
            'total_shipments' => $totalShipments,
            'pending' => $pendingShipments,
            'in_transit' => $inTransitShipments,
            'delivered' => $deliveredShipments,
            'failed' => $failedShipments,
            'top_carriers' => $topCarriers,
            'avg_delivery_days' => $avgDeliveryTime ? round($avgDeliveryTime, 1) : null,
        ]);
    }
}
