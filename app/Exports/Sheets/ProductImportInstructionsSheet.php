<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductImportInstructionsSheet implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
    public function array(): array
    {
        return [
            ['Product Excel Import – Instructions'],
            [],
            ['Required columns (first row of the "Products" sheet must contain these exact headers):'],
            ['  name              - Product name (required).'],
            ['  product_code      - SKU / barcode (optional).'],
            ['  category          - Category name. If it does not exist, it will be created and used.'],
            ['  brand             - Brand name. If it does not exist, it will be created and used.'],
            ['  unit_value        - Numeric size, e.g. 500 (for 500GM, 500ML).'],
            ['  unit              - Unit code or name (e.g. GM, ML, Gram). If it does not exist, it will be created and used.'],
            ['  stock_unit        - piece, dozen, or box (optional, default piece).'],
            ['  pcs_in_ctn        - Pieces per box/carton (required, integer, min 1).'],
            ['  unit_buying_price - Cost per unit (optional).'],
            ['  profit_margin     - Profit margin % (optional; used to safely compute selling_price if missing).'],
            ['  tax               - Tax name or value %; must match an existing tax.'],
            ['  selling_price_unit- Price per single unit (optional if profit_margin provided).'],
            ['  box_price         - Price per box (optional; computed from pcs_in_ctn * selling_price_unit if blank).'],
            ['  quantity          - Initial stock (optional, default 0).'],
            ['  alert_quantity    - Low-stock alert threshold (optional).'],
            ['  notes             - Internal notes (optional).'],
            ['  is_featured       - yes / no or 1 / 0 (optional, default no).'],
            [],
            ['Tips:'],
            ['  • Download the template from the Import modal and fill the "Products" sheet.'],
            ['  • Category, Brand, and Unit: new ones are created automatically if the name/code does not exist.'],
            ['  • Tax must match an existing tax name or value in the system.'],
            ['  • Leave optional columns blank if not needed.'],
            ['  • Row 1 = headers; data starts from row 2.'],
        ];
    }

    public function title(): string
    {
        return 'Instructions';
    }

    public function columnWidths(): array
    {
        return ['A' => 80];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
        ];
    }
}
