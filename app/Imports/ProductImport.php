<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tax;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use \Maatwebsite\Excel\Concerns\SkipsFailures;

    protected $units;
    protected $categoryIds;
    protected $brandIds;
    protected $taxIds;

    public function __construct()
    {
        // Cache units for quick lookup
        $this->units = Unit::all()->mapWithKeys(function ($unit) {
            return [strtolower($unit->name) => $unit->id, strtolower($unit->code) => $unit->id];
        })->toArray();

        // Cache Category and Brand IDs for random assignment
        $this->categoryIds = Category::pluck('id')->toArray();
        $this->brandIds = Brand::pluck('id')->toArray();
        $this->taxIds = Tax::pluck('id')->toArray();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $name = $row['name_of_the_items'] ?? $row['name'] ?? null;

        if (empty($name)) {
            return null;
        }

        // 1. Initial values from Excel columns
        $unitValue = $row['size_in_gmml'] ?? $row['unit_value'] ?? $row['size'] ?? null;
        $pcsInCtn = $row['pcsctn'] ?? $row['pcs_in_ctn'] ?? $row['pcs'] ?? null;
        $productCode = $row['bar_code'] ?? $row['product_code'] ?? $row['barcode'] ?? null;
        $notes = $row['remarks'] ?? $row['notes'] ?? null;
        $quantity = $row['quantity'] ?? $row['stock'] ?? $row['qty'] ?? null;
        $boxPrice = $row['box_price'] ?? $row['price'] ?? null;
        $buyingPrice = $row['buying_price'] ?? $row['cost'] ?? null;
        $categoryId = $row['category_id'] ?? $row['category'] ?? null;
        $brandId = $row['brand_id'] ?? $row['brand'] ?? null;
        $taxId = $row['tax_id'] ?? $row['tax'] ?? null;
        $unitId = null;

        // 2. Advanced Analysis of Title
        // Regex for Unit Value and Type (e.g., 250ML, 500 GM)
        if (preg_match('/(\d+(\.\d+)?)\s*(ML|GM|KG|L|LTR|G|GRAM|POUCH|PCS)/i', $name, $matches)) {
            if (empty($unitValue)) {
                $unitValue = $matches[1];
            }
            $unitHint = strtolower($matches[3]);
            $unitId = $this->resolveUnitId($unitHint);
        }

        // Regex for PCS hint (e.g., X 24 PCS, 96 PCS PACK)
        if (preg_match('/(?:X|[*x])\s*(\d+)\s*(PCS|PACK|PKT)/i', $name, $matches)) {
            if (empty($pcsInCtn)) {
                $pcsInCtn = $matches[1];
            }
        } elseif (preg_match('/(\d+)\s*(PCS|PACK|PKT)/i', $name, $matches)) {
             // Fallback if not already found via PCS column or X hint
             if (empty($pcsInCtn)) {
                $pcsInCtn = $matches[1];
             }
        }

        // 3. Random Assignment and Mandatory Field Defaults
        if (empty($categoryId) && !empty($this->categoryIds)) {
            $categoryId = $this->categoryIds[array_rand($this->categoryIds)];
        }

        if (empty($brandId) && !empty($this->brandIds)) {
            $brandId = $this->brandIds[array_rand($this->brandIds)];
        }

        if (empty($taxId) && !empty($this->taxIds)) {
            // Default to first tax if available, or just random
            $taxId = $this->taxIds[0]; 
        }

        if (empty($quantity) || $quantity == 0) {
            $quantity = rand(10, 100);
        }

        if (empty($boxPrice)) {
            $boxPrice = rand(100, 1000);
        }

        if (empty($buyingPrice)) {
            $buyingPrice = $boxPrice * (rand(70, 90) / 100);
        }

        // Ensure pcs_in_ctn is at least 1 for calculation in controller/model if needed
        $pcsInCtn = $pcsInCtn ?? 1;
        $unitPrice = $boxPrice / $pcsInCtn;

        return new Product([
            'name'         => $name,
            'unit_value'   => $unitValue,
            'pcs_in_ctn'   => $pcsInCtn,
            'product_code' => isset($productCode) ? (string) $productCode : null,
            'notes'        => $notes,
            'quantity'     => $quantity,
            'box_price'    => $boxPrice,
            'unit_price'   => $unitPrice,
            'buying_price' => $buyingPrice,
            'unit_id'      => $unitId,
            'category_id'  => $categoryId,
            'brand_id'     => $brandId,
            'tax_id'       => $taxId,
        ]);
    }

    /**
     * Resolve unit ID from string hint.
     */
    protected function resolveUnitId($hint)
    {
        $mapping = [
            'ml' => 'ml',
            'gm' => 'gm',
            'g' => 'gm',
            'gram' => 'gm',
            'kg' => 'kg',
            'l' => 'ltr',
            'ltr' => 'ltr',
            'pcs' => 'pc',
            'pc' => 'pc',
        ];

        $code = $mapping[$hint] ?? $hint;
        return $this->units[$code] ?? null;
    }

    public function rules(): array
    {
        return [
            'name_of_the_items' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
        ];
    }
}
