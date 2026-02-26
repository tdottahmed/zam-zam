<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tax;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithBatchInserts
{
    use \Maatwebsite\Excel\Concerns\SkipsFailures;

    /** @var array<string, int> unit code/name (lowercase) => id */
    protected $units;

    /** @var array<string, int> category name (lowercase) => id */
    protected $categories;

    /** @var array<string, int> brand name (lowercase) => id */
    protected $brands;

    /** @var array tax by name (lowercase) or value string => id */
    protected $taxes;

    public function __construct()
    {
        $this->units = Unit::all()->mapWithKeys(function (Unit $unit) {
            return [
                strtolower($unit->name) => $unit->id,
                strtolower($unit->code) => $unit->id,
            ];
        })->toArray();

        $this->categories = Category::where('status', true)->get()->mapWithKeys(function (Category $cat) {
            return [strtolower(trim($cat->name)) => $cat->id];
        })->toArray();

        $this->brands = Brand::where('status', true)->get()->mapWithKeys(function (Brand $brand) {
            return [strtolower(trim($brand->name)) => $brand->id];
        })->toArray();

        $this->taxes = Tax::where('is_active', true)->get()->flatMap(function (Tax $tax) {
            $id = $tax->id;
            $byName = [strtolower(trim($tax->name)) => $id];
            $byValue = [(string) $tax->value => $id];
            return array_merge($byName, $byValue);
        })->toArray();
    }

    /**
     * @param array $row Keys are slugified headers (e.g. name, product_code, category, brand, ...)
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Support canonical 'name' or legacy 'name_of_the_items'
        $name = $this->trim($row['name'] ?? $row['name_of_the_items'] ?? null);
        if ($name === null || $name === '') {
            return null;
        }

        $unitValue = $this->trim($row['unit_value'] ?? null);
        $pcsInCtn = $this->intOrNull($row['pcs_in_ctn'] ?? $row['pcsctn'] ?? $row['pcs'] ?? null);

        // Extract unit_value and pcs_in_ctn from product name if not in columns (e.g. "250ML X 24 PCS", "500 GM")
        if (preg_match('/(\d+(?:\.\d+)?)\s*(ML|GM|KG|L|LTR|G|GRAM|POUCH|PCS)/i', $name, $m)) {
            if ($unitValue === null || $unitValue === '') {
                $unitValue = $m[1];
            }
        }
        if (preg_match('/(?:X|[*x])\s*(\d+)\s*(PCS|PACK|PKT)/i', $name, $m) || preg_match('/(\d+)\s*(PCS|PACK|PKT)/i', $name, $m)) {
            if ($pcsInCtn === null || $pcsInCtn < 1) {
                $pcsInCtn = (int) $m[1];
            }
        }

        if ($pcsInCtn === null || $pcsInCtn < 1) {
            $pcsInCtn = 1;
        }

        $boxPrice = $this->floatOrNull($row['box_price'] ?? $row['price'] ?? null);
        $unitPrice = $this->floatOrNull($row['unit_price'] ?? null);
        if ($unitPrice === null && $boxPrice !== null && $pcsInCtn > 0) {
            $unitPrice = round($boxPrice / $pcsInCtn, 2);
        }

        $categoryId = $this->resolveCategory($row['category'] ?? null);
        $brandId = $this->resolveBrand($row['brand'] ?? null);
        $unitId = $this->resolveUnit($row['unit'] ?? null, $name);
        $taxId = $this->resolveTax($row['tax'] ?? null);

        $stockUnit = $this->normalizeStockUnit($row['stock_unit'] ?? null);
        $quantity = (int) ($this->intOrNull($row['quantity'] ?? null) ?? 0);
        $alertQuantity = $this->intOrNull($row['alert_quantity'] ?? null);
        $isFeatured = $this->boolFromExcel($row['is_featured'] ?? null);

        return new Product([
            'name' => $name,
            'slug' => $this->uniqueSlug($name),
            'product_code' => $this->trim($row['product_code'] ?? null) ?: null,
            'category_id' => $categoryId,
            'brand_id' => $brandId,
            'unit_value' => $unitValue !== null && $unitValue !== '' ? $unitValue : null,
            'unit_id' => $unitId,
            'pcs_in_ctn' => $pcsInCtn,
            'box_price' => $boxPrice,
            'unit_price' => $unitPrice,
            'buying_price' => $this->floatOrNull($row['buying_price'] ?? null),
            'tax_id' => $taxId,
            'quantity' => $quantity,
            'stock_unit' => $stockUnit,
            'alert_quantity' => $alertQuantity ?? 0,
            'notes' => $this->trim($row['notes'] ?? null) ?: null,
            'is_featured' => $isFeatured,
        ]);
    }

    protected function trim($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $s = trim((string) $value);
        return $s === '' ? null : $s;
    }

    protected function intOrNull($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        return is_numeric($value) ? (int) $value : null;
    }

    protected function floatOrNull($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        return is_numeric($value) ? (float) $value : null;
    }

    protected function resolveCategory($value): ?int
    {
        $name = $value !== null && $value !== '' ? trim((string) $value) : null;
        if ($name === null || $name === '') {
            return null;
        }
        $key = strtolower($name);
        if (isset($this->categories[$key])) {
            return $this->categories[$key];
        }
        $category = Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'status' => true]
        );
        $this->categories[$key] = $category->id;
        return $category->id;
    }

    protected function resolveBrand($value): ?int
    {
        $name = $value !== null && $value !== '' ? trim((string) $value) : null;
        if ($name === null || $name === '') {
            return null;
        }
        $key = strtolower($name);
        if (isset($this->brands[$key])) {
            return $this->brands[$key];
        }
        $brand = Brand::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'status' => true]
        );
        $this->brands[$key] = $brand->id;
        return $brand->id;
    }

    protected function resolveUnit($value, string $name): ?int
    {
        if ($value !== null && $value !== '') {
            $id = $this->units[strtolower(trim((string) $value))] ?? null;
            if ($id !== null) {
                return $id;
            }
        }
        // Fallback: try to infer from product name (e.g. 500GM, 250ML)
        if (preg_match('/(\d+(\.\d+)?)\s*(ML|GM|KG|L|LTR|G|GRAM|POUCH|PCS)/i', $name, $m)) {
            $hint = strtolower($m[3]);
            $map = ['ml' => 'ml', 'gm' => 'gm', 'g' => 'gm', 'gram' => 'gm', 'kg' => 'kg', 'l' => 'ltr', 'ltr' => 'ltr', 'pcs' => 'pc', 'pc' => 'pc'];
            $code = $map[$hint] ?? $hint;
            return $this->units[$code] ?? null;
        }
        return null;
    }

    protected function resolveTax($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $v = trim((string) $value);
        $byName = $this->taxes[strtolower($v)] ?? null;
        if ($byName !== null) {
            return $byName;
        }
        if (is_numeric($v)) {
            return $this->taxes[$v] ?? null;
        }
        return null;
    }

    protected function normalizeStockUnit($value): string
    {
        $v = $value !== null && $value !== '' ? strtolower(trim((string) $value)) : 'piece';
        return in_array($v, ['piece', 'dozen', 'box'], true) ? $v : 'piece';
    }

    protected function boolFromExcel($value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }
        $v = is_string($value) ? strtolower(trim($value)) : $value;
        return in_array($v, [true, 1, '1', 'yes', 'y', 'true'], true);
    }

    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $n = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n;
            $n++;
        }
        return $slug;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'pcs_in_ctn' => 'nullable|integer|min:1',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name must not exceed 255 characters.',
            'pcs_in_ctn.min' => 'Pieces per box must be at least 1.',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }
}
