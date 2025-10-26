<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Mail\PasswordResetMail;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'document' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'document' => $validated['document'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Eliminar tokens antiguos (opcional, para mantener limpio)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'data' => $request->user()
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:50',
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente'
        ]);
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Por seguridad, no revelamos si el email existe o no
            return response()->json([
                'message' => 'Si el email existe, recibirás un enlace de recuperación'
            ]);
        }

        // Generar token
        $token = Str::random(60);

        // Eliminar tokens antiguos y crear nuevo
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Enviar email
        try {
            Mail::to($user->email)->send(new PasswordResetMail($token, $user->email));
        } catch (\Exception $e) {
            \Log::error('Error enviando email de recuperación: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Si el email existe, recibirás un enlace de recuperación'
        ]);
    }

    /**
     * Reset password using token
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'message' => 'Token inválido o expirado'
            ], 400);
        }

        // Verificar que el token no haya expirado (60 minutos)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'message' => 'El token ha expirado'
            ], 400);
        }

        // Verificar el token
        if (!Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'message' => 'Token inválido'
            ], 400);
        }

        // Actualizar contraseña
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Eliminar todos los tokens de acceso del usuario (forzar re-login)
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Contraseña restablecida exitosamente'
        ]);
    }
}
