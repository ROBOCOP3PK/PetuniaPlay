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
            // Customer information
            'customer.name' => 'required|string|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.phone' => 'required|string|max:20',
            'customer.document' => 'required|string|max:50',

            // Shipping information
            'shipping.address' => 'required|string|max:500',
            'shipping.city' => 'required|string|max:100',
            'shipping.state' => 'required|string|max:100',
            'shipping.zipCode' => 'nullable|string|max:20',
            'shipping.notes' => 'nullable|string|max:1000',

            // Payment information
            'payment.method' => 'required|in:card,pse,cash,mercadopago',
            'payment.amount' => 'required|numeric|min:0',

            // Order items
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',

            // Totals
            'totals.subtotal' => 'required|numeric|min:0',
            'totals.tax' => 'required|numeric|min:0',
            'totals.shipping' => 'required|numeric|min:0',
            'totals.total' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'customer.name.required' => 'El nombre es obligatorio',
            'customer.email.required' => 'El email es obligatorio',
            'customer.email.email' => 'El email debe ser válido',
            'customer.phone.required' => 'El teléfono es obligatorio',
            'customer.document.required' => 'El documento es obligatorio',
            'shipping.address.required' => 'La dirección de envío es obligatoria',
            'shipping.city.required' => 'La ciudad es obligatoria',
            'shipping.state.required' => 'El departamento es obligatorio',
            'payment.method.required' => 'El método de pago es obligatorio',
            'items.required' => 'Debes agregar al menos un producto',
            'items.min' => 'Debes agregar al menos un producto',
        ];
    }
}
