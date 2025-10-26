<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'admin' ? 'Nuevo Mensaje de Contacto' : 'Confirmación de Mensaje' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #8B5CF6;
            margin-bottom: 30px;
        }
        .logo {
            width: 60px;
            height: 60px;
            background-color: #8B5CF6;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 10px;
        }
        h1 {
            color: #8B5CF6;
            margin: 10px 0;
            font-size: 24px;
        }
        .info-section {
            background-color: #f9fafb;
            border-left: 4px solid #8B5CF6;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }
        .info-value {
            color: #1f2937;
            font-size: 15px;
        }
        .message-box {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            white-space: pre-wrap;
            line-height: 1.8;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .admin-note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .customer-note {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">PP</div>
            <h1>
                @if($type === 'admin')
                    Nuevo Mensaje de Contacto
                @else
                    Hemos Recibido tu Mensaje
                @endif
            </h1>
        </div>

        @if($type === 'admin')
            <!-- Admin Email Content -->
            <div class="admin-note">
                <strong>⚠️ Acción Requerida:</strong> Un cliente ha enviado un nuevo mensaje a través del formulario de contacto.
            </div>

            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">👤 Nombre del Cliente</span>
                    <span class="info-value">{{ $contactData['name'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📧 Correo Electrónico</span>
                    <span class="info-value">
                        <a href="mailto:{{ $contactData['email'] }}" style="color: #8B5CF6;">
                            {{ $contactData['email'] }}
                        </a>
                    </span>
                </div>
                @if(!empty($contactData['phone']))
                <div class="info-row">
                    <span class="info-label">📱 Teléfono</span>
                    <span class="info-value">{{ $contactData['phone'] }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">📋 Asunto</span>
                    <span class="info-value"><strong>{{ $contactData['subject'] }}</strong></span>
                </div>
            </div>

            <div style="margin: 20px 0;">
                <span class="info-label">💬 Mensaje</span>
                <div class="message-box">{{ $contactData['message'] }}</div>
            </div>

            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin-top: 20px;">
                <p style="margin: 0; font-size: 14px; color: #6b7280;">
                    <strong>Responde directamente:</strong> Puedes responder a este email directamente para contactar al cliente.
                </p>
            </div>

        @else
            <!-- Customer Email Content -->
            <p style="font-size: 16px; color: #1f2937;">Hola <strong>{{ $contactData['name'] }}</strong>,</p>

            <div class="customer-note">
                <strong>✅ ¡Mensaje Recibido!</strong> Hemos recibido tu mensaje y nuestro equipo lo está revisando.
            </div>

            <p>Gracias por ponerte en contacto con <strong>Petunia Play</strong>. Nos esforzamos por responder todos los mensajes en un plazo de 24-48 horas hábiles.</p>

            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">📋 Asunto de tu Mensaje</span>
                    <span class="info-value">{{ $contactData['subject'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📧 Tu Correo</span>
                    <span class="info-value">{{ $contactData['email'] }}</span>
                </div>
            </div>

            <div style="margin: 20px 0;">
                <span class="info-label">Tu Mensaje</span>
                <div class="message-box">{{ $contactData['message'] }}</div>
            </div>

            <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; margin-top: 20px;">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280;">
                    Si necesitas contactarnos nuevamente, puedes:
                </p>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #6b7280;">
                    <li>Responder directamente a este correo</li>
                    <li>Visitar nuestra página de contacto</li>
                    <li>Llamarnos al: +57 (123) 456-7890</li>
                </ul>
            </div>

            <p style="margin-top: 20px; color: #6b7280;">
                Atentamente,<br>
                <strong style="color: #8B5CF6;">El equipo de Petunia Play</strong>
            </p>
        @endif

        <div class="footer">
            <p style="margin: 5px 0;">
                <strong>Petunia Play</strong><br>
                Tu tienda de juguetes de confianza
            </p>
            <p style="margin: 5px 0; font-size: 12px;">
                Este es un correo automático, por favor no respondas a esta dirección.
            </p>
        </div>
    </div>
</body>
</html>
