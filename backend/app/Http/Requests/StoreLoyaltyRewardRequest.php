<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoyaltyRewardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:permanent,campaign',
            'target_audience' => 'required|in:new_only,all,recurring_only',
            'required_purchases' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
            'priority' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        // For campaign type, dates are required
        if ($this->type === 'campaign') {
            $rules['start_date'] = 'required|date|after_or_equal:today';
            $rules['end_date'] = 'required|date|after:start_date';
        } else {
            $rules['start_date'] = 'nullable|date';
            $rules['end_date'] = 'nullable|date|after:start_date';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la recompensa es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'type.required' => 'El tipo de recompensa es obligatorio',
            'type.in' => 'El tipo debe ser permanente o campaña',
            'target_audience.required' => 'El público objetivo es obligatorio',
            'target_audience.in' => 'El público objetivo debe ser "solo nuevos", "todos" o "solo recurrentes"',
            'required_purchases.required' => 'El número de compras requeridas es obligatorio',
            'required_purchases.min' => 'El número de compras debe ser al menos 0',
            'product_id.required' => 'El producto es obligatorio',
            'product_id.exists' => 'El producto seleccionado no existe',
            'discount_percentage.min' => 'El porcentaje de descuento debe ser al menos 0',
            'discount_percentage.max' => 'El porcentaje de descuento no puede ser mayor a 100',
            'start_date.required' => 'La fecha de inicio es obligatoria para campañas',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior',
            'end_date.required' => 'La fecha de fin es obligatoria para campañas',
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
        ];
    }
}
