<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductQuestion extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'question',
        'answer',
        'answered_by',
        'answered_at',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'answered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['user', 'product', 'answeredBy'];

    /**
     * Producto al que pertenece la pregunta
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Usuario que hizo la pregunta
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Usuario que respondió la pregunta
     */
    public function answeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    /**
     * Scope para preguntas públicas (respondidas)
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true)
                    ->whereNotNull('answer')
                    ->whereNotNull('answered_at');
    }

    /**
     * Scope para preguntas pendientes (sin responder)
     */
    public function scopePending($query)
    {
        return $query->where('is_public', false)
                    ->whereNull('answer');
    }

    /**
     * Scope para ordenar por más recientes primero
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
