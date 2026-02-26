<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductImportDataSheet implements FromArray, WithTitle, WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * Canonical column headers – must match ProductImport expectations (heading row slug).
     */
    public function headings(): array
    {
        return [
            'name',
            'product_code',
            'category',
            'brand',
            'unit_value',
            'unit',
            'pcs_in_ctn',
            'box_price',
            'unit_price',
            'buying_price',
            'tax',
            'quantity',
            'stock_unit',
            'alert_quantity',
            'notes',
            'is_featured',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Soft Drink Powder Mango 500 GM',
                '8901234567890',
                'Beverages',
                'Sajeeb',
                '500',
                'GM',
                '24',
                '120.00',
                '5.00',
                '3.50',
                'VAT 15%',
                '100',
                'piece',
                '10',
                'Best seller',
                'yes',
            ],
            [
                'Mineral Water 500ML Bottle',
                '8901234567891',
                'Beverages',
                'Pure',
                '500',
                'ML',
                '12',
                '48.00',
                '4.00',
                '2.00',
                'VAT 15%',
                '50',
                'piece',
                '5',
                '',
                'no',
            ],
        ];
    }

    public function title(): string
    {
        return 'Products';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 18,
            'C' => 16,
            'D' => 14,
            'E' => 12,
            'F' => 10,
            'G' => 12,
            'H' => 12,
            'I' => 12,
            'J' => 14,
            'K' => 14,
            'L' => 10,
            'M' => 12,
            'N' => 14,
            'O' => 20,
            'P' => 12,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['wrapText' => true]],
        ];
    }
}
