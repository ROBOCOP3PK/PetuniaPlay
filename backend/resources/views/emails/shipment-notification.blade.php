<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Envío</title>
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
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-in_transit { background: #dbeafe; color: #1e40af; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-failed { background: #fee2e2; color: #991b1b; }
        .status-returned { background: #fce7f3; color: #831843; }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #A97447;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #6b7280;
        }
        .value {
            color: #111827;
        }
        .tracking-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #A97447;
        }
        .tracking-number {
            font-size: 24px;
            font-weight: bold;
            color: #A97447;
            letter-spacing: 2px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #A97447;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: #8B5E3C;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>
            @if($shipment->status === 'pending')
                📦 Tu pedido ha sido procesado
            @elseif($shipment->status === 'in_transit')
                🚚 Tu pedido está en camino
            @elseif($shipment->status === 'delivered')
                ✅ Tu pedido ha sido entregado
            @elseif($shipment->status === 'failed')
                ⚠️ Problema con tu envío
            @elseif($shipment->status === 'returned')
                ↩️ Tu pedido ha sido devuelto
            @endif
        </h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Pedido #{{ $shipment->order->order_number }}</p>
    </div>

    <div class="content">
        <p>Hola {{ $shipment->order->user->name }},</p>

        @if($shipment->status === 'pending')
            <p>Tu pedido ha sido procesado y está listo para ser enviado. Te notificaremos cuando sea despachado.</p>
        @elseif($shipment->status === 'in_transit')
            <p>¡Buenas noticias! Tu pedido ha sido despachado y está en camino.</p>
        @elseif($shipment->status === 'delivered')
            <p>¡Excelente! Tu pedido ha sido entregado exitosamente. Esperamos que disfrutes tus productos.</p>
            <p>Si tienes algún problema con tu pedido, no dudes en contactarnos.</p>
        @elseif($shipment->status === 'failed')
            <p>Lamentamos informarte que hubo un problema con tu envío. Nuestro equipo está trabajando para resolverlo.</p>
            <p>Te contactaremos pronto para coordinar una nueva entrega.</p>
        @elseif($shipment->status === 'returned')
            <p>Tu pedido ha sido devuelto. Por favor, contáctanos para más información.</p>
        @endif

        <div class="tracking-box">
            <p style="margin: 0; color: #6b7280;">Número de Rastreo</p>
            <div class="tracking-number">{{ $shipment->tracking_number }}</div>
            <p style="margin: 10px 0 0 0; color: #6b7280; font-size: 14px;">
                Transportadora: <strong>{{ $shipment->carrier }}</strong>
            </p>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #A97447;">Detalles del Envío</h3>

            <div class="info-row">
                <span class="label">Estado:</span>
                <span class="value">
                    <span class="status-badge status-{{ $shipment->status }}">
                        @if($shipment->status === 'pending') Pendiente
                        @elseif($shipment->status === 'in_transit') En Tránsito
                        @elseif($shipment->status === 'delivered') Entregado
                        @elseif($shipment->status === 'failed') Fallido
                        @elseif($shipment->status === 'returned') Devuelto
                        @endif
                    </span>
                </span>
            </div>

            @if($shipment->shipped_at)
            <div class="info-row">
                <span class="label">Fecha de Envío:</span>
                <span class="value">{{ $shipment->shipped_at->format('d/m/Y H:i') }}</span>
            </div>
            @endif

            @if($shipment->delivered_at)
            <div class="info-row">
                <span class="label">Fecha de Entrega:</span>
                <span class="value">{{ $shipment->delivered_at->format('d/m/Y H:i') }}</span>
            </div>
            @endif

            <div class="info-row">
                <span class="label">Dirección de Envío:</span>
                <span class="value">
                    {{ $shipment->order->shippingAddress->address_line_1 }}<br>
                    {{ $shipment->order->shippingAddress->city }}, {{ $shipment->order->shippingAddress->state }}<br>
                    {{ $shipment->order->shippingAddress->country }}
                </span>
            </div>
        </div>

        @if($shipment->notes)
        <div class="info-box" style="border-left-color: #f59e0b;">
            <h4 style="margin-top: 0; color: #f59e0b;">📝 Notas Adicionales</h4>
            <p style="margin: 0;">{{ $shipment->notes }}</p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ config('app.frontend_url') }}/account" class="button">
                Ver Mis Pedidos
            </a>
        </div>

        <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
            Si tienes alguna pregunta sobre tu envío, contáctanos a través del sistema de contacto en nuestra aplicación.
        </p>
    </div>

    <div class="footer">
        <p><strong>Petunia Play</strong></p>
        <p>Tu tienda de confianza para mascotas</p>

        <div style="margin-top: 20px; padding: 15px; background-color: #f8f4ec; border-radius: 5px; font-size: 13px; line-height: 1.6; text-align: left;">
            <strong>Protección de Datos Personales (Ley 1581 de 2012)</strong><br>
            Tienes derecho a conocer, actualizar, rectificar y suprimir tus datos personales (Derechos RACS).
            Para ejercer estos derechos o consultar nuestra política de tratamiento de datos, contáctanos a través del sistema de contacto en la aplicación.
        </div>

        <p style="margin-top: 15px; font-size: 12px;">
            <strong>Responsable del tratamiento de datos:</strong><br>
            Petunia Play | Bogotá, Colombia
        </p>

        @php
            $unsubscribeToken = \App\Http\Controllers\Api\UnsubscribeController::generateUnsubscribeToken($shipment->order->user->email);
            $unsubscribeUrl = env('FRONTEND_URL', 'http://localhost:5173') . '/unsubscribe?token=' . urlencode($unsubscribeToken);
        @endphp

        <p style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            Si no deseas recibir más correos de notificaciones, puedes
            <a href="{{ $unsubscribeUrl }}" style="color: #A97447; text-decoration: underline;">darte de baja aquí</a>
        </p>

        <p style="margin-top: 10px; font-size: 11px; color: #999;">
            Este es un email automático, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>
