<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loyalty_reward_id',
        'order_id',
        'status',
        'redeemed_at',
    ];

    protected function casts(): array
    {
        return [
            'redeemed_at' => 'datetime',
        ];
    }

    // Relaciones

    /**
     * Get the user who redeemed this reward.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the loyalty reward that was redeemed.
     */
    public function loyaltyReward()
    {
        return $this->belongsTo(LoyaltyReward::class);
    }

    /**
     * Get the order associated with this redemption.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes

    /**
     * Scope a query to only include pending redemptions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed redemptions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to filter by user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Métodos

    /**
     * Check if the redemption is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the redemption is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the redemption is cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
