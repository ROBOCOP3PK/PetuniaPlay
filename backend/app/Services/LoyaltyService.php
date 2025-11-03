<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyRedemption;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    /**
     * Get the currently active loyalty program.
     *
     * @return \App\Models\LoyaltyProgram|null
     */
    public function getActiveProgram(): ?LoyaltyProgram
    {
        return LoyaltyProgram::where('is_active', true)->first();
    }

    /**
     * Check which rewards are eligible for a user.
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function checkEligibleRewards(User $user): array
    {
        $program = $this->getActiveProgram();

        if (!$program) {
            return [];
        }

        // Get all current rewards (active and within date range)
        $rewards = LoyaltyReward::where('loyalty_program_id', $program->id)
            ->with('product.primaryImage')
            ->current()
            ->get();

        $eligibleRewards = [];

        foreach ($rewards as $reward) {
            if ($reward->isEligible($user)) {
                $eligibleRewards[] = [
                    'id' => $reward->id,
                    'name' => $reward->name,
                    'description' => $reward->description,
                    'type' => $reward->type,
                    'required_purchases' => $reward->required_purchases,
                    'product' => $reward->product ? [
                        'id' => $reward->product->id,
                        'name' => $reward->product->name,
                        'price' => $reward->product->price,
                        'image' => $reward->product->primaryImage ? $reward->product->primaryImage->url : null,
                    ] : null,
                    'priority' => $reward->priority,
                    'start_date' => $reward->start_date,
                    'end_date' => $reward->end_date,
                ];
            }
        }

        return $eligibleRewards;
    }

    /**
     * Redeem a loyalty reward for a user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\LoyaltyReward $reward
     * @return \App\Models\LoyaltyRedemption
     * @throws \Exception
     */
    public function redeemReward(User $user, LoyaltyReward $reward): LoyaltyRedemption
    {
        DB::beginTransaction();

        try {
            // Verify the user is eligible
            if (!$reward->isEligible($user)) {
                throw new \Exception('No eres elegible para canjear esta recompensa.');
            }

            // Verify the product exists and has stock
            if (!$reward->product) {
                throw new \Exception('El producto de recompensa no está disponible.');
            }

            if ($reward->product->stock < 1) {
                throw new \Exception('El producto de recompensa está agotado.');
            }

            // Create the redemption record
            $redemption = LoyaltyRedemption::create([
                'user_id' => $user->id,
                'loyalty_reward_id' => $reward->id,
                'status' => 'pending',
                'redeemed_at' => now(),
            ]);

            DB::commit();

            return $redemption;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process a redemption by creating the special order.
     *
     * @param \App\Models\LoyaltyRedemption $redemption
     * @return \App\Models\Order
     * @throws \Exception
     */
    public function processRedemption(LoyaltyRedemption $redemption): Order
    {
        DB::beginTransaction();

        try {
            // Load relationships
            $redemption->load(['user', 'loyaltyReward.product']);

            $user = $redemption->user;
            $reward = $redemption->loyaltyReward;
            $product = $reward->product;

            // Verify stock
            if ($product->stock < 1) {
                throw new \Exception('El producto está agotado.');
            }

            // Get user's default address or create a placeholder
            $defaultAddress = $user->addresses()->where('is_default', true)->first();

            if (!$defaultAddress) {
                $defaultAddress = $user->addresses()->first();
            }

            if (!$defaultAddress) {
                throw new \Exception('El usuario debe tener al menos una dirección registrada.');
            }

            // Create the loyalty order
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $defaultAddress->id,
                'billing_address_id' => $defaultAddress->id,
                'subtotal' => 0,
                'tax' => 0,
                'shipping_cost' => 0,
                'discount' => 0,
                'total' => 0,
                'status' => 'processing',
                'payment_status' => 'paid',
                'notes' => 'Orden de recompensa de lealtad - ' . $reward->name,
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'price' => 0,
                'quantity' => 1,
                'subtotal' => 0,
            ]);

            // Update product stock
            $product->decrement('stock', 1);

            // Update redemption with order_id and status
            $redemption->update([
                'order_id' => $order->id,
                'status' => 'completed',
            ]);

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update user's completed purchases count.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function updateUserPurchaseCount(User $user): void
    {
        // Count only delivered orders (excluding loyalty redemption orders)
        $completedPurchases = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->where('total', '>', 0) // Exclude loyalty orders (total = 0)
            ->count();

        $user->update(['completed_purchases' => $completedPurchases]);
    }

    /**
     * Get loyalty program statistics for admin dashboard.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $program = $this->getActiveProgram();

        if (!$program) {
            return [
                'program_active' => false,
                'total_rewards' => 0,
                'active_rewards' => 0,
                'total_redemptions' => 0,
                'pending_redemptions' => 0,
                'completed_redemptions' => 0,
                'top_rewards' => [],
            ];
        }

        // Total rewards
        $totalRewards = LoyaltyReward::where('loyalty_program_id', $program->id)->count();
        $activeRewards = LoyaltyReward::where('loyalty_program_id', $program->id)
            ->active()
            ->count();

        // Redemptions statistics
        $totalRedemptions = LoyaltyRedemption::whereHas('loyaltyReward', function ($query) use ($program) {
            $query->where('loyalty_program_id', $program->id);
        })->count();

        $pendingRedemptions = LoyaltyRedemption::whereHas('loyaltyReward', function ($query) use ($program) {
            $query->where('loyalty_program_id', $program->id);
        })->pending()->count();

        $completedRedemptions = LoyaltyRedemption::whereHas('loyaltyReward', function ($query) use ($program) {
            $query->where('loyalty_program_id', $program->id);
        })->completed()->count();

        // Top redeemed rewards
        // NOTA: Se usa DB::table() aquí porque es un query complejo de reporting con
        // joins, agregaciones y group by. DB::table() es más eficiente y legible que
        // Eloquent para queries de estadísticas/analytics.
        $topRewards = DB::table('loyalty_redemptions')
            ->join('loyalty_rewards', 'loyalty_redemptions.loyalty_reward_id', '=', 'loyalty_rewards.id')
            ->where('loyalty_rewards.loyalty_program_id', $program->id)
            ->select('loyalty_rewards.name', DB::raw('COUNT(*) as redemption_count'))
            ->groupBy('loyalty_rewards.id', 'loyalty_rewards.name')
            ->orderBy('redemption_count', 'desc')
            ->limit(5)
            ->get();

        return [
            'program_active' => true,
            'program_name' => $program->name,
            'total_rewards' => $totalRewards,
            'active_rewards' => $activeRewards,
            'total_redemptions' => $totalRedemptions,
            'pending_redemptions' => $pendingRedemptions,
            'completed_redemptions' => $completedRedemptions,
            'top_rewards' => $topRewards,
        ];
    }

    /**
     * Get user's redemption history.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserRedemptions(User $user)
    {
        return LoyaltyRedemption::where('user_id', $user->id)
            ->with(['loyaltyReward.product.primaryImage', 'order'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
