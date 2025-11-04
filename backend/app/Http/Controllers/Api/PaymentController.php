<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    /**
     * Crear una preferencia de pago de Mercado Pago
     */
    public function createPreference(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
            ]);

            Log::info('MP: Iniciando creación de preferencia', ['order_id' => $validated['order_id']]);

            // Obtener la orden
            $order = Order::with(['items.product.images', 'user'])->findOrFail($validated['order_id']);

            // Verificar que la orden no tenga ya un pago aprobado
            if ($order->status === 'paid' || $order->status === 'processing') {
                return response()->json([
                    'message' => 'Esta orden ya ha sido pagada o está en proceso',
                ], 400);
            }

            // Preparar datos para Mercado Pago
            $orderData = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer' => [
                    'name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                    'document' => $order->customer_document,
                ],
                'shipping' => [
                    'address' => $order->shipping_address,
                    'addressLine2' => $order->shipping_address_line_2,
                    'city' => $order->shipping_city,
                    'state' => $order->shipping_state,
                    'zipCode' => $order->shipping_postal_code,
                    'notes' => $order->notes,
                    'nightDelivery' => $order->night_delivery,
                ],
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'product_description' => $item->product->description ?? 'Producto de Petunia Play',
                        'product_image' => $item->product->primary_image->image_url ?? null,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                })->toArray(),
            ];

            // Crear preferencia en Mercado Pago
            $result = $this->mercadoPagoService->createPreference($orderData);

            if (!$result['success']) {
                Log::error('MP: Error al crear preferencia', ['error' => $result['error']]);
                return response()->json([
                    'message' => $result['error'],
                    'details' => $result['details'] ?? null,
                ], 500);
            }

            Log::info('MP: Preferencia creada exitosamente', ['preference_id' => $result['preference_id']]);

            // Guardar la preferencia en la base de datos
            $order->update([
                'payment_preference_id' => $result['preference_id'],
            ]);

            // Crear registro de pago pendiente
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'mercadopago',
                'amount' => $order->total,
                'status' => 'pending',
                'preference_id' => $result['preference_id'],
            ]);

            return response()->json([
                'success' => true,
                'preference_id' => $result['preference_id'],
                'init_point' => config('mercadopago.test_mode')
                    ? $result['sandbox_init_point']
                    : $result['init_point'],
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('MP: Excepción al crear preferencia', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Error al procesar la solicitud de pago',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook para recibir notificaciones de Mercado Pago
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('MP: Webhook recibido', ['type' => $request->input('type'), 'id' => $request->input('data.id')]);

            // Mercado Pago envía diferentes tipos de notificaciones
            $type = $request->input('type');
            $dataId = $request->input('data.id');

            // Solo procesamos notificaciones de pagos
            if ($type !== 'payment') {
                return response()->json(['status' => 'ignored'], 200);
            }

            // Obtener información del pago desde Mercado Pago
            $paymentInfo = $this->mercadoPagoService->getPayment($dataId);

            if (!$paymentInfo) {
                Log::error('No se pudo obtener información del pago', ['payment_id' => $dataId]);
                return response()->json(['status' => 'error'], 500);
            }

            // Buscar la orden por external_reference (order_number)
            $order = Order::where('order_number', $paymentInfo['external_reference'])->first();

            if (!$order) {
                Log::error('Orden no encontrada', ['external_reference' => $paymentInfo['external_reference']]);
                return response()->json(['status' => 'order_not_found'], 404);
            }

            // Actualizar o crear el registro de pago
            $payment = Payment::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'payment_method' => 'mercadopago',
                ],
                [
                    'mercadopago_payment_id' => $paymentInfo['id'],
                    'amount' => $paymentInfo['transaction_amount'],
                    'status' => $this->mapPaymentStatus($paymentInfo['status']),
                    'payment_details' => json_encode($paymentInfo),
                ]
            );

            // Actualizar estado de la orden según el estado del pago
            $this->updateOrderStatus($order, $paymentInfo['status']);

            Log::info('MP: Pago procesado', ['order' => $order->order_number, 'status' => $paymentInfo['status']]);

            return response()->json(['status' => 'processed'], 200);

        } catch (\Exception $e) {
            Log::error('MP: Error en webhook', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Consultar el estado de un pago
     */
    public function getPaymentStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
            ]);

            $order = Order::with('payments')->findOrFail($validated['order_id']);
            $payment = $order->payments()->where('payment_method', 'mercadopago')->latest()->first();

            if (!$payment) {
                return response()->json([
                    'status' => 'not_found',
                    'message' => 'No se encontró información de pago',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'payment' => [
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                ],
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'total' => $order->total,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al consultar el estado del pago',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mapear estados de Mercado Pago a nuestros estados
     */
    private function mapPaymentStatus($mpStatus)
    {
        $statusMap = [
            'approved' => 'paid',
            'pending' => 'pending',
            'in_process' => 'pending',
            'rejected' => 'failed',
            'cancelled' => 'failed',
            'refunded' => 'refunded',
        ];

        return $statusMap[$mpStatus] ?? 'pending';
    }

    /**
     * Actualizar el estado de la orden según el pago
     */
    private function updateOrderStatus(Order $order, $paymentStatus)
    {
        $statusMap = [
            'approved' => 'paid',
            'pending' => 'pending',
            'in_process' => 'pending',
            'rejected' => 'cancelled',
            'cancelled' => 'cancelled',
        ];

        $newStatus = $statusMap[$paymentStatus] ?? 'pending';

        $order->update(['status' => $newStatus]);

        // Si el pago fue aprobado, podrías enviar email de confirmación aquí
        if ($newStatus === 'paid') {
            // TODO: Enviar email de confirmación
            Log::info('Pago aprobado para orden', ['order_number' => $order->order_number]);
        }
    }
}
