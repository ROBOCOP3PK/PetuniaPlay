<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase_amount' => $this->min_purchase_amount,
            'usage_limit' => $this->usage_limit,
            'usage_count' => $this->usage_count,
            'remaining_uses' => $this->usage_limit ? ($this->usage_limit - $this->usage_count) : null,
            'valid_from' => $this->valid_from?->format('Y-m-d H:i:s'),
            'valid_until' => $this->valid_until?->format('Y-m-d H:i:s'),
            'is_active' => $this->is_active,
            'is_valid' => $this->isValid(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
