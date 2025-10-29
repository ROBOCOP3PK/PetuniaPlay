<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingConfigController extends Controller
{
    /**
     * Obtener la configuración actual de envíos (público)
     */
    public function index()
    {
        try {
            $config = ShippingConfig::current();

            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la configuración de envíos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar configuración de envíos (solo manager/admin)
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'night_delivery_enabled' => 'required|boolean',
                'night_delivery_start_time' => 'required|date_format:H:i',
                'night_delivery_end_time' => 'required|date_format:H:i',
                'free_shipping_min_items' => 'required|integer|min:1',
                'standard_shipping_cost' => 'required|numeric|min:0',
            ], [
                'night_delivery_start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM (ej: 21:00)',
                'night_delivery_end_time.date_format' => 'La hora de fin debe tener el formato HH:MM (ej: 02:00)',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $config = ShippingConfig::current();

            $config->update([
                'night_delivery_enabled' => $request->night_delivery_enabled,
                'night_delivery_start_time' => $request->night_delivery_start_time . ':00',
                'night_delivery_end_time' => $request->night_delivery_end_time . ':00',
                'free_shipping_min_items' => $request->free_shipping_min_items,
                'standard_shipping_cost' => $request->standard_shipping_cost,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Configuración de envíos actualizada exitosamente',
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
