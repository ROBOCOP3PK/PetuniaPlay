<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Order::with(['user', 'items.product']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'N° Orden',
            'Cliente',
            'Email',
            'Total',
            'Estado',
            'Método de Pago',
            'Items',
            'Fecha de Creación',
        ];
    }

    /**
     * @param Order $order
     * @return array
     */
    public function map($order): array
    {
        return [
            $order->order_number,
            $order->user ? $order->user->name : 'Invitado',
            $order->user ? $order->user->email : 'N/A',
            '$' . number_format($order->total, 0, ',', '.'),
            $this->getStatusLabel($order->status),
            $this->getPaymentMethodLabel($order->payment_method),
            $order->items->count(),
            $order->created_at->format('Y-m-d H:i'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 20,
            'G' => 10,
            'H' => 20,
        ];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Pendiente',
            'processing' => 'Procesando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado'
        ];

        return $labels[$status] ?? $status;
    }

    private function getPaymentMethodLabel($method)
    {
        $methods = [
            'credit_card' => 'Tarjeta de Crédito',
            'debit_card' => 'Tarjeta de Débito',
            'cash' => 'Efectivo',
            'transfer' => 'Transferencia'
        ];

        return $methods[$method] ?? $method;
    }
}
