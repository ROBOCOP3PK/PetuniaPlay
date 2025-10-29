<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    protected $fillable = [
        'whatsapp_number',
        'whatsapp_enabled',
        'whatsapp_message',
    ];

    protected function casts(): array
    {
        return [
            'whatsapp_enabled' => 'boolean',
        ];
    }

    /**
     * Obtener la configuración actual (siempre devuelve el primer registro)
     */
    public static function current()
    {
        return self::first() ?? self::create([
            'whatsapp_number' => '573057594088',
            'whatsapp_enabled' => true,
            'whatsapp_message' => 'Buen día Tengo una duda con un producto',
        ]);
    }
}
