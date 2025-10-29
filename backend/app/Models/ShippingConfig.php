<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingConfig extends Model
{
    protected $fillable = [
        'night_delivery_enabled',
        'night_delivery_start_time',
        'night_delivery_end_time',
        'free_shipping_min_items',
        'standard_shipping_cost',
    ];

    protected function casts(): array
    {
        return [
            'night_delivery_enabled' => 'boolean',
            'standard_shipping_cost' => 'decimal:2',
        ];
    }

    /**
     * Obtener la configuración actual (siempre devuelve el primer registro)
     */
    public static function current()
    {
        return self::first() ?? self::create([
            'night_delivery_enabled' => true,
            'night_delivery_start_time' => '21:00:00',
            'night_delivery_end_time' => '02:00:00',
            'free_shipping_min_items' => 4,
            'standard_shipping_cost' => 10000,
        ]);
    }
}
