<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $category;
    protected $brand;
    protected $lowStock;

    public function __construct($category = null, $brand = null, $lowStock = false)
    {
        $this->category = $category;
        $this->brand = $brand;
        $this->lowStock = $lowStock;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Product::with(['category']);

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        if ($this->brand) {
            $query->where('brand', $this->brand);
        }

        if ($this->lowStock) {
            $query->whereRaw('stock <= low_stock_threshold');
        }

        return $query->orderBy('name')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'SKU',
            'Nombre',
            'Marca',
            'Categoría',
            'Precio',
            'Precio Oferta',
            'Stock',
            'Umbral Stock Bajo',
            'Estado',
            'Fecha de Creación',
        ];
    }

    /**
     * @param Product $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->sku,
            $product->name,
            $product->brand ?? 'N/A',
            $product->category ? $product->category->name : 'Sin categoría',
            '$' . number_format($product->price, 0, ',', '.'),
            $product->sale_price ? '$' . number_format($product->sale_price, 0, ',', '.') : 'N/A',
            $product->stock,
            $product->low_stock_threshold,
            $this->getStatusLabel($product->is_active),
            $product->created_at->format('Y-m-d H:i'),
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
            'B' => 35,
            'C' => 20,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 10,
            'H' => 20,
            'I' => 12,
            'J' => 20,
        ];
    }

    private function getStatusLabel($isActive)
    {
        return $isActive ? 'Activo' : 'Inactivo';
    }
}
