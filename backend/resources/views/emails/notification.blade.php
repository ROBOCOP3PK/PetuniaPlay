<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="content">
        <p>Hola {{ $user->name }},</p>

        <p>{!! nl2br(e($message)) !!}</p>

        @if(isset($data['action_url']) && isset($data['action_text']))
        <div style="text-align: center;">
            <a href="{{ $data['action_url'] }}" class="button">
                {{ $data['action_text'] }}
            </a>
        </div>
        @endif

        @if(isset($data['info']))
        <div class="info-box">
            {!! nl2br(e($data['info'])) !!}
        </div>
        @endif

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
        <p style="margin-top: 10px; font-size: 12px;">
            Si no deseas recibir notificaciones por correo, puedes desactivarlas desde tu perfil.
        </p>
    </div>
</body>
</html>
