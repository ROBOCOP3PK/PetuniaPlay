<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
            'document' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'document' => $validated['document'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Enviar código de verificación de email
        try {
            VerificationCode::generateAndSend(
                $user->email,
                VerificationCode::TYPE_EMAIL_VERIFICATION
            );
        } catch (\Exception $e) {
            Log::error('Error enviando código de verificación: ' . $e->getMessage());
        }

        return response()->json([
            'user' => $user,
            'token' => $token,
            'requires_email_verification' => true,
            'message' => 'Cuenta creada exitosamente. Te enviamos un código de verificación a tu email.'
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
        // NOTA: Se usa DB::table() aquí porque password_reset_tokens es una tabla
        // del sistema de Laravel sin modelo Eloquent por defecto. Es justificable
        // mantener DB::table() para tablas temporales/transitorias del sistema.
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
            Log::error('Error enviando email de recuperación: ' . $e->getMessage());
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

        // NOTA: Se usa DB::table() para password_reset_tokens (tabla del sistema)
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

    /**
     * Update notification preferences
     */
    public function updateNotificationPreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications_enabled' => 'required|boolean',
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Preferencias de notificación actualizadas exitosamente',
            'data' => $user
        ]);
    }

    /**
     * Send email verification code
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Por seguridad, no revelamos si el email existe o no
            return response()->json([
                'message' => 'Si el email está registrado, recibirás un código de verificación'
            ]);
        }

        // Si el email ya está verificado
        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Este email ya ha sido verificado'
            ], 400);
        }

        try {
            VerificationCode::generateAndSend(
                $request->email,
                VerificationCode::TYPE_EMAIL_VERIFICATION
            );
        } catch (\Exception $e) {
            Log::error('Error enviando código de verificación: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al enviar el código. Intenta de nuevo.'
            ], 500);
        }

        return response()->json([
            'message' => 'Código de verificación enviado exitosamente'
        ]);
    }

    /**
     * Verify email with code
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $verification = VerificationCode::verify(
            $request->email,
            $request->code,
            VerificationCode::TYPE_EMAIL_VERIFICATION
        );

        if (!$verification) {
            return response()->json([
                'message' => 'Código inválido o expirado'
            ], 400);
        }

        // Marcar el email como verificado
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->update(['email_verified_at' => now()]);

            return response()->json([
                'message' => 'Email verificado exitosamente',
                'user' => $user
            ]);
        }

        return response()->json([
            'message' => 'Usuario no encontrado'
        ], 404);
    }

    /**
     * Send password reset code (alternative to forgotPassword with link)
     */
    public function sendPasswordResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Por seguridad, no revelamos si el email existe o no
            return response()->json([
                'message' => 'Si el email existe, recibirás un código de recuperación'
            ]);
        }

        try {
            VerificationCode::generateAndSend(
                $request->email,
                VerificationCode::TYPE_PASSWORD_RESET
            );
        } catch (\Exception $e) {
            Log::error('Error enviando código de recuperación: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al enviar el código. Intenta de nuevo.'
            ], 500);
        }

        return response()->json([
            'message' => 'Si el email existe, recibirás un código de recuperación'
        ]);
    }

    /**
     * Verify password reset code (step 1 of 2-step reset)
     */
    public function verifyPasswordResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $verification = VerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('type', VerificationCode::TYPE_PASSWORD_RESET)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Código inválido o expirado'
            ], 400);
        }

        // Marcar como verificado
        $verification->update(['verified_at' => now()]);

        return response()->json([
            'message' => 'Código verificado. Ahora puedes crear tu nueva contraseña.',
            'verified' => true
        ]);
    }

    /**
     * Reset password with verified code (step 2 of 2-step reset)
     */
    public function resetPasswordWithCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verificar que el código fue previamente verificado
        $verification = VerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('type', VerificationCode::TYPE_PASSWORD_RESET)
            ->whereNotNull('verified_at')
            ->where('verified_at', '>', now()->subMinutes(15)) // 15 min para completar el reset
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Código inválido o sesión expirada. Solicita un nuevo código.'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Eliminar el código usado
        $verification->delete();

        // Eliminar todos los tokens de acceso del usuario (forzar re-login)
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Contraseña restablecida exitosamente'
        ]);
    }

    /**
     * Resend verification code (for both email verification and password reset)
     */
    public function resendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'required|in:email_verification,password_reset',
        ]);

        $type = $request->type === 'password_reset'
            ? VerificationCode::TYPE_PASSWORD_RESET
            : VerificationCode::TYPE_EMAIL_VERIFICATION;

        // Verificar rate limiting - máximo 1 código cada 60 segundos
        $recentCode = VerificationCode::where('email', $request->email)
            ->where('type', $type)
            ->where('created_at', '>', now()->subMinutes(1))
            ->exists();

        if ($recentCode) {
            return response()->json([
                'message' => 'Debes esperar 1 minuto antes de solicitar otro código'
            ], 429);
        }

        try {
            VerificationCode::generateAndSend($request->email, $type);
        } catch (\Exception $e) {
            Log::error('Error reenviando código: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al enviar el código. Intenta de nuevo.'
            ], 500);
        }

        return response()->json([
            'message' => 'Código enviado exitosamente'
        ]);
    }
}
