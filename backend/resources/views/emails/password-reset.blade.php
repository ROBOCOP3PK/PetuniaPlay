<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #A97447 0%, #8B5E3C 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f8f4ec;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: #A97447;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
        }
        .button:hover {
            background: #8B5E3C;
        }
        .info-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #A97447;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 Recuperación de Contraseña</h1>
    </div>

    <div class="content">
        <p>Hola,</p>

        <p>
            Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Petunia Play</strong>.
        </p>

        <p>
            Para crear una nueva contraseña, haz clic en el siguiente botón:
        </p>

        <div style="text-align: center;">
            <a href="{{ config('app.frontend_url') }}/reset-password?token={{ $token }}&email={{ urlencode($email) }}" class="button">
                Restablecer Contraseña
            </a>
        </div>

        <div class="info-box">
            <p style="margin: 0;"><strong>⏰ Este enlace expirará en 60 minutos.</strong></p>
            <p style="margin: 10px 0 0 0; font-size: 14px;">
                Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:
            </p>
            <p style="margin: 10px 0 0 0; font-size: 12px; word-break: break-all; color: #6b7280;">
                {{ config('app.frontend_url') }}/reset-password?token={{ $token }}&email={{ urlencode($email) }}
            </p>
        </div>

        <div class="warning">
            <p style="margin: 0; font-weight: bold;">⚠️ Seguridad</p>
            <p style="margin: 5px 0 0 0; font-size: 14px;">
                Si no solicitaste este cambio de contraseña, ignora este correo.
                Tu cuenta permanecerá segura y no se realizarán cambios.
            </p>
        </div>

        <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
            Si tienes alguna pregunta, contáctanos a través del sistema de contacto en nuestra aplicación.
        </p>
    </div>

    <div class="footer">
        <p><strong>Petunia Play</strong></p>
        <p>Tu tienda de confianza para mascotas</p>
        <p style="margin-top: 10px; font-size: 12px;">
            Este es un email automático, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>
