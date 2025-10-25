<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address_id.required' => 'La dirección de envío es obligatoria',
            'items.required' => 'Debes agregar al menos un producto',
            'items.min' => 'Debes agregar al menos un producto',
        ];
    }
}
