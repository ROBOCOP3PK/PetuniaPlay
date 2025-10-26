<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Órdenes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .header-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4a5568;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #e6f7ff;
            border-left: 4px solid #1890ff;
        }
    </style>
</head>
<body>
    <h1>Reporte de Órdenes - Petunia Play</h1>

    <div class="header-info">
        <strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}<br>
        @if($startDate)
            <strong>Desde:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}<br>
        @endif
        @if($endDate)
            <strong>Hasta:</strong> {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}<br>
        @endif
        @if($status)
            <strong>Estado:</strong> {{ $status }}<br>
        @endif
        <strong>Total de órdenes:</strong> {{ $orders->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>N° Orden</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Método Pago</th>
                <th>Items</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSales = 0;
            @endphp
            @foreach($orders as $order)
                @php
                    $totalSales += $order->total;
                @endphp
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user ? $order->user->name : 'Invitado' }}</td>
                    <td>{{ $order->user ? $order->user->email : 'N/A' }}</td>
                    <td>${{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->payment_method }}</td>
                    <td>{{ $order->items->count() }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Ventas Totales:</strong> ${{ number_format($totalSales, 0, ',', '.') }}<br>
        <strong>Ticket Promedio:</strong> ${{ $orders->count() > 0 ? number_format($totalSales / $orders->count(), 0, ',', '.') : 0 }}
    </div>

    <div class="footer">
        <p>Este documento fue generado automáticamente por Petunia Play</p>
        <p>&copy; {{ date('Y') }} Petunia Play. Todos los derechos reservados.</p>
    </div>
</body>
</html>
