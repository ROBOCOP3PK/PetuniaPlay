<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f4ec;
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
            border-bottom: 3px solid #a97447;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #a97447;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 10px 0 0 0;
        }
        .order-number {
            background-color: #f8f4ec;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 30px;
        }
        .order-number h2 {
            color: #a97447;
            margin: 0;
            font-size: 24px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            color: #a97447;
            border-bottom: 2px solid #d6b890;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .product-item {
            background-color: #f8f4ec;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .product-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .product-name {
            font-weight: bold;
            color: #333;
        }
        .product-details {
            color: #666;
            font-size: 14px;
        }
        .totals {
            background-color: #f8f4ec;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .total-row.final {
            border-top: 2px solid #a97447;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #a97447;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #a97447;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #fbbf24;
            color: #78350f;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🐾 Petunia Play</h1>
            <p>¡Gracias por tu compra!</p>
        </div>

        <!-- Order Number -->
        <div class="order-number">
            <h2>Pedido #{{ $order->order_number }}</h2>
            <span class="status-badge">{{ strtoupper($order->status) }}</span>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <h3>📋 Información del Cliente</h3>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span>{{ $order->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span>{{ $order->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span>{{ $order->user->phone }}</span>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="section">
            <h3>🚚 Dirección de Envío</h3>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span>{{ $order->shippingAddress->full_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                <span>{{ $order->shippingAddress->address_line_1 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ciudad:</span>
                <span>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">País:</span>
                <span>{{ $order->shippingAddress->country }}</span>
            </div>
        </div>

        <!-- Order Items -->
        <div class="section">
            <h3>📦 Productos</h3>
            @foreach($order->items as $item)
            <div class="product-item">
                <div class="product-row">
                    <span class="product-name">{{ $item->product_name }}</span>
                    <span class="product-name">${{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="product-row">
                    <span class="product-details">SKU: {{ $item->product_sku }}</span>
                    <span class="product-details">Cantidad: {{ $item->quantity }} x ${{ number_format($item->price, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>IVA (19%):</span>
                <span>${{ number_format($order->tax, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Envío:</span>
                <span>{{ $order->shipping_cost == 0 ? '¡Gratis!' : '$' . number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>${{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="section">
            <h3>💳 Información de Pago</h3>
            <div class="info-row">
                <span class="info-label">Estado del Pago:</span>
                <span>{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fecha del Pedido:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        @if($order->notes)
        <div class="section">
            <h3>📝 Notas del Pedido</h3>
            <p style="background-color: #f8f4ec; padding: 15px; border-radius: 5px;">{{ $order->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>¿Tienes preguntas?</strong></p>
            <p>Contáctanos en petuniaplayhub@gmail.com o al +57 305 7594088</p>
            <p style="margin-top: 20px; padding: 15px; background-color: #f8f4ec; border-radius: 5px; font-size: 13px; line-height: 1.6;">
                <strong>Protección de Datos Personales (Ley 1581 de 2012)</strong><br>
                Tienes derecho a conocer, actualizar, rectificar y suprimir tus datos personales (Derechos RACS).
                Para ejercer estos derechos o consultar nuestra política de tratamiento de datos, contáctanos en petuniaplayhub@gmail.com
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                <strong>Responsable del tratamiento de datos:</strong><br>
                Petunia Play | Bogotá, Colombia<br>
                Email: petuniaplayhub@gmail.com | Tel: +57 305 7594088
            </p>
            @php
                $unsubscribeToken = \App\Http\Controllers\Api\UnsubscribeController::generateUnsubscribeToken($order->user->email);
                $unsubscribeUrl = env('FRONTEND_URL', 'http://localhost:5173') . '/unsubscribe?token=' . urlencode($unsubscribeToken);
            @endphp
            <p style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
                Si no deseas recibir más correos de notificaciones, puedes
                <a href="{{ $unsubscribeUrl }}" style="color: #a97447; text-decoration: underline;">darte de baja aquí</a>
            </p>
            <p style="margin-top: 10px; color: #999; font-size: 11px;">
                Este es un correo automático, por favor no respondas directamente a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
