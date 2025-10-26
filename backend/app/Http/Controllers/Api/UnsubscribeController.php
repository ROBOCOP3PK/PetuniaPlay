<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UnsubscribeController extends Controller
{
    /**
     * Desuscribir usuario de notificaciones por email
     */
    public function unsubscribe(Request $request, $token)
    {
        try {
            // Desencriptar el email del token
            $email = Crypt::decryptString($token);

            // Buscar usuario por email
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Desactivar notificaciones por email
            $user->update(['email_notifications' => false]);

            return response()->json([
                'message' => 'Te has desuscrito exitosamente de las notificaciones por email.',
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token inválido o expirado',
                'success' => false
            ], 400);
        }
    }

    /**
     * Re-suscribir usuario a notificaciones por email
     */
    public function resubscribe(Request $request, $token)
    {
        try {
            // Desencriptar el email del token
            $email = Crypt::decryptString($token);

            // Buscar usuario por email
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Activar notificaciones por email
            $user->update(['email_notifications' => true]);

            return response()->json([
                'message' => 'Te has suscrito exitosamente a las notificaciones por email.',
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token inválido o expirado',
                'success' => false
            ], 400);
        }
    }

    /**
     * Generar token de desuscripción para un email
     */
    public static function generateUnsubscribeToken($email)
    {
        return Crypt::encryptString($email);
    }
}
