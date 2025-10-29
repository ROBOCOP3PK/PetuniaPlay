<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteConfigController extends Controller
{
    /**
     * Obtener la configuración actual del sitio (público)
     */
    public function index()
    {
        try {
            $config = SiteConfig::current();

            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la configuración del sitio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar configuración del sitio (solo admin)
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'whatsapp_number' => 'required|string|max:20',
                'whatsapp_enabled' => 'required|boolean',
                'whatsapp_message' => 'required|string|max:500',
            ], [
                'whatsapp_number.required' => 'El número de WhatsApp es requerido',
                'whatsapp_number.max' => 'El número no puede tener más de 20 caracteres',
                'whatsapp_message.required' => 'El mensaje predeterminado es requerido',
                'whatsapp_message.max' => 'El mensaje no puede tener más de 500 caracteres',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $config = SiteConfig::current();

            $config->update([
                'whatsapp_number' => $request->whatsapp_number,
                'whatsapp_enabled' => $request->whatsapp_enabled,
                'whatsapp_message' => $request->whatsapp_message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Configuración del sitio actualizada exitosamente',
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
