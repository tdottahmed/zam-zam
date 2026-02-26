<?php

namespace App\Exports;

use App\Exports\Sheets\ProductImportInstructionsSheet;
use App\Exports\Sheets\ProductImportDataSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductImportTemplateExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        // Products first so import (which reads the first sheet) works when user uploads the template.
        return [
            new ProductImportDataSheet,
            new ProductImportInstructionsSheet,
        ];
    }
}
