<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;
    protected $endDate;
    protected $groupBy;

    public function __construct($startDate = null, $endDate = null, $groupBy = 'day')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->groupBy = $groupBy; // day, week, month
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Order::query()
            ->whereIn('status', ['processing', 'shipped', 'delivered']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        // Group by period
        switch ($this->groupBy) {
            case 'week':
                $dateFormat = '%Y-%u'; // Year-Week
                $selectDate = DB::raw("DATE_FORMAT(created_at, '%Y-W%u') as period");
                break;
            case 'month':
                $dateFormat = '%Y-%m'; // Year-Month
                $selectDate = DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period");
                break;
            default: // day
                $dateFormat = '%Y-%m-%d'; // Year-Month-Day
                $selectDate = DB::raw("DATE(created_at) as period");
                break;
        }

        $results = $query
            ->select(
                $selectDate,
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('AVG(total) as average_order'),
                DB::raw('COUNT(DISTINCT user_id) as unique_customers')
            )
            ->groupBy('period')
            ->orderBy('period', 'desc')
            ->get();

        return $results;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Período',
            'Total Órdenes',
            'Ventas Totales',
            'Ticket Promedio',
            'Clientes Únicos',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $this->formatPeriod($row->period),
            $row->total_orders,
            '$' . number_format($row->total_sales, 0, ',', '.'),
            '$' . number_format($row->average_order, 0, ',', '.'),
            $row->unique_customers,
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
            'A' => 20,
            'B' => 15,
            'C' => 20,
            'D' => 20,
            'E' => 18,
        ];
    }

    private function formatPeriod($period)
    {
        switch ($this->groupBy) {
            case 'week':
                // Format: 2025-W43 -> Semana 43, 2025
                $parts = explode('-W', $period);
                return "Semana {$parts[1]}, {$parts[0]}";
            case 'month':
                // Format: 2025-10 -> Octubre 2025
                $date = \Carbon\Carbon::createFromFormat('Y-m', $period);
                return $date->locale('es')->isoFormat('MMMM YYYY');
            default: // day
                // Format: 2025-10-26 -> 26 de Octubre, 2025
                $date = \Carbon\Carbon::parse($period);
                return $date->locale('es')->isoFormat('D [de] MMMM, YYYY');
        }
    }
}
