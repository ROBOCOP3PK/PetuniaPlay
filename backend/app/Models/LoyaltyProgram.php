<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    // Relaciones

    /**
     * Get all rewards associated with this loyalty program.
     */
    public function rewards()
    {
        return $this->hasMany(LoyaltyReward::class);
    }

    // Métodos

    /**
     * Check if the loyalty program is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the program settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings ?? [];
    }

    /**
     * Get a specific setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSetting(string $key, $default = null)
    {
        $settings = $this->getSettings();
        return $settings[$key] ?? $default;
    }
}
