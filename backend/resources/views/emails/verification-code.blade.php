<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f3f4f6;
        }
        .container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #A97447 0%, #8B5E3C 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #ffffff;
            padding: 30px;
        }
        .code-container {
            text-align: center;
            margin: 30px 0;
        }
        .code {
            display: inline-block;
            background: linear-gradient(135deg, #f8f4ec 0%, #f3ebe0 100%);
            padding: 20px 40px;
            border-radius: 12px;
            border: 2px dashed #A97447;
        }
        .code-digits {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 12px;
            color: #A97447;
            font-family: 'Courier New', monospace;
        }
        .code-label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .info-box {
            background: #fef3c7;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
        }
        .info-box strong {
            color: #92400e;
        }
        .security-box {
            background: #f0fdf4;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #22c55e;
        }
        .security-box p {
            margin: 0;
            font-size: 14px;
            color: #166534;
        }
        .footer {
            text-align: center;
            padding: 20px 30px;
            background: #f8f4ec;
            color: #6b7280;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .emoji {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($type === 'password_reset')
                <h1>🔐 Restablecer Contraseña</h1>
            @else
                <h1>✉️ Verificación de Email</h1>
            @endif
        </div>

        <div class="content">
            <p>Hola,</p>

            @if($type === 'password_reset')
                <p>
                    Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Petunia Play</strong>.
                    Usa el siguiente código para continuar:
                </p>
            @else
                <p>
                    Gracias por registrarte en <strong>Petunia Play</strong>.
                    Para verificar tu dirección de email, ingresa el siguiente código:
                </p>
            @endif

            <div class="code-container">
                <div class="code">
                    <span class="code-digits">{{ $code }}</span>
                    <span class="code-label">Código de verificación</span>
                </div>
            </div>

            <div class="info-box">
                <p>
                    <span class="emoji">⏰</span>
                    <strong>Este código expira en {{ $expiresInMinutes }} minutos.</strong>
                </p>
            </div>

            <div class="security-box">
                <p>
                    <span class="emoji">🔒</span>
                    <strong>Seguridad:</strong> Si no solicitaste este código, puedes ignorar este mensaje.
                    Tu cuenta permanecerá segura.
                </p>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 25px;">
                Si tienes problemas, contáctanos en <strong>petuniaplay@gmail.com</strong>
            </p>
        </div>

        <div class="footer">
            <p><strong>Petunia Play</strong></p>
            <p>Tu tienda de confianza para mascotas</p>
            <p style="font-size: 12px; margin-top: 10px;">
                Este es un email automático, por favor no respondas a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
