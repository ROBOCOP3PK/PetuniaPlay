<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'loyalty_program_id',
        'name',
        'description',
        'type',
        'target_audience',
        'required_purchases',
        'product_id',
        'is_active',
        'priority',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'required_purchases' => 'integer',
            'priority' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    // Relaciones

    /**
     * Get the loyalty program that owns this reward.
     */
    public function loyaltyProgram()
    {
        return $this->belongsTo(LoyaltyProgram::class);
    }

    /**
     * Get the product associated with this reward.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all redemptions for this reward.
     */
    public function redemptions()
    {
        return $this->hasMany(LoyaltyRedemption::class);
    }

    // Scopes

    /**
     * Scope a query to only include active rewards.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include permanent rewards.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePermanent($query)
    {
        return $query->where('type', 'permanent');
    }

    /**
     * Scope a query to only include campaign rewards.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCampaigns($query)
    {
        return $query->where('type', 'campaign');
    }

    /**
     * Scope a query to only include current campaigns (active and within date range).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->where('type', 'permanent')
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where('type', 'campaign')
                            ->where('start_date', '<=', $now)
                            ->where('end_date', '>=', $now);
                    });
            });
    }

    // Métodos

    /**
     * Check if the reward is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the reward is available (active and within date range for campaigns).
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Permanent rewards are always available when active
        if ($this->type === 'permanent') {
            return true;
        }

        // Campaign rewards must be within date range
        if ($this->type === 'campaign') {
            $now = now();
            return $this->start_date <= $now && $this->end_date >= $now;
        }

        return false;
    }

    /**
     * Check if the user is eligible for this reward.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function isEligible(User $user): bool
    {
        // Check if reward is available
        if (!$this->isAvailable()) {
            return false;
        }

        // Check target audience
        if ($this->target_audience === 'new_only' && $user->completed_purchases > 0) {
            return false;
        }

        // Check required purchases
        if ($user->completed_purchases < $this->required_purchases) {
            return false;
        }

        // Check if user has already redeemed this reward (for new_only rewards)
        if ($this->target_audience === 'new_only') {
            $alreadyRedeemed = $this->redemptions()
                ->where('user_id', $user->id)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($alreadyRedeemed) {
                return false;
            }
        }

        return true;
    }
}
