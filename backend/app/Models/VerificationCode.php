<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class VerificationCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'type',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Tipos de verificación disponibles
     */
    const TYPE_EMAIL_VERIFICATION = 'email_verification';
    const TYPE_PASSWORD_RESET = 'password_reset';

    /**
     * Minutos de expiración por defecto
     */
    const EXPIRATION_MINUTES = 10;

    /**
     * Genera y envía un nuevo código de verificación
     *
     * @param string $email
     * @param string $type
     * @param int $expiresInMinutes
     * @return self
     */
    public static function generateAndSend(string $email, string $type = self::TYPE_EMAIL_VERIFICATION, int $expiresInMinutes = self::EXPIRATION_MINUTES): self
    {
        // Eliminar códigos anteriores no verificados del mismo email y tipo
        self::where('email', $email)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->delete();

        // Generar código de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Crear el nuevo registro
        $verification = self::create([
            'email' => $email,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);

        // Enviar email con el código
        Mail::to($email)->send(new VerificationCodeMail($code, $expiresInMinutes, $type));

        return $verification;
    }

    /**
     * Verifica si el código es válido
     *
     * @param string $email
     * @param string $code
     * @param string $type
     * @return self|null
     */
    public static function verify(string $email, string $code, string $type = self::TYPE_EMAIL_VERIFICATION): ?self
    {
        $verification = self::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($verification) {
            $verification->update(['verified_at' => now()]);
        }

        return $verification;
    }

    /**
     * Verifica si el código ha expirado
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Verifica si el código ya fue usado
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Obtiene el título del tipo de verificación para emails
     *
     * @return string
     */
    public function getTypeTitle(): string
    {
        return match($this->type) {
            self::TYPE_EMAIL_VERIFICATION => 'Verificación de Email',
            self::TYPE_PASSWORD_RESET => 'Restablecimiento de Contraseña',
            default => 'Verificación',
        };
    }
}
