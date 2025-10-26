<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exports\SalesReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export sales report to Excel (admin only)
     */
    public function salesReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'group_by' => 'nullable|in:day,week,month'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $groupBy = $request->input('group_by', 'day');

        $filename = 'reporte_ventas_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new SalesReportExport($startDate, $endDate, $groupBy),
            $filename
        );
    }
}
