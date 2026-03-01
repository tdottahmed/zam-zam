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
            'stock_unit',
            'pcs_in_ctn',
            'unit_buying_price',
            'profit_margin',
            'tax',
            'selling_price_unit',
            'box_price',
            'quantity',
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
                'piece',
                '24',
                '3.50',
                '42.86',
                'VAT 15%',
                '5.00',
                '120.00',
                '100',
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
                'piece',
                '12',
                '2.00',
                '100',
                'VAT 15%',
                '4.00',
                '48.00',
                '50',
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
            'L' => 14,
            'M' => 12,
            'N' => 12,
            'O' => 14,
            'P' => 20,
            'Q' => 12,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['wrapText' => true]],
        ];
    }
}
