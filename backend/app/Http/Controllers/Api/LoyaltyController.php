<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RedeemRewardRequest;
use App\Models\LoyaltyReward;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Get available rewards for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRewards(Request $request)
    {
        try {
            $user = $request->user();

            // Refresh user's purchase count
            $this->loyaltyService->updateUserPurchaseCount($user);

            $rewards = $this->loyaltyService->checkEligibleRewards($user);

            return response()->json([
                'data' => $rewards,
                'user_purchases' => $user->completed_purchases
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las recompensas disponibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's redemption history.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRedemptions(Request $request)
    {
        try {
            $user = $request->user();

            $redemptions = $this->loyaltyService->getUserRedemptions($user);

            return response()->json([
                'data' => $redemptions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el historial de canjes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Redeem a loyalty reward.
     *
     * @param \App\Http\Requests\RedeemRewardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function redeem(RedeemRewardRequest $request)
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            $reward = LoyaltyReward::findOrFail($validated['loyalty_reward_id']);

            // Attempt to redeem the reward
            $redemption = $this->loyaltyService->redeemReward($user, $reward);

            // Automatically process the redemption to create the order
            $order = $this->loyaltyService->processRedemption($redemption);

            // Reload redemption with relationships
            $redemption->load([
                'loyaltyReward.product.primaryImage',
                'order'
            ]);

            return response()->json([
                'data' => $redemption,
                'order' => $order,
                'message' => 'Recompensa canjeada exitosamente. Se ha creado tu orden: ' . $order->order_number
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al canjear la recompensa',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
