<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyRedemption;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyRedemptionController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Display a listing of redemptions.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = LoyaltyRedemption::with([
                'user',
                'loyaltyReward.product.primaryImage',
                'order'
            ]);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by date range
            if ($request->has('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            $redemptions = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 20));

            return response()->json($redemptions);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los canjes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified redemption.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $redemption = LoyaltyRedemption::with([
                'user',
                'loyaltyReward.product.primaryImage',
                'order.items',
                'order.shippingAddress'
            ])->findOrFail($id);

            return response()->json([
                'data' => $redemption
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el canje',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a pending redemption manually.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(int $id)
    {
        try {
            $redemption = LoyaltyRedemption::findOrFail($id);

            // Check if already processed
            if ($redemption->status !== 'pending') {
                return response()->json([
                    'message' => 'Este canje ya fue procesado'
                ], 422);
            }

            // Process the redemption
            $order = $this->loyaltyService->processRedemption($redemption);

            // Reload redemption with relationships
            $redemption->load([
                'user',
                'loyaltyReward.product.primaryImage',
                'order'
            ]);

            return response()->json([
                'data' => $redemption,
                'order' => $order,
                'message' => 'Canje procesado exitosamente. Orden creada: ' . $order->order_number
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al procesar el canje',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
