<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $admin = User::factory()->create(['user_type' => 'admin']);
        $this->actingAs($admin);
    }

    public function test_admin_can_import_products_from_excel()
    {
        \App\Models\Category::factory()->create(['name' => 'Beverages']);
        \App\Models\Brand::factory()->create(['name' => 'Sajeeb']);
        \App\Models\Brand::factory()->create(['name' => 'Pure']);
        \App\Models\Unit::create(['name' => 'Gram', 'code' => 'GM', 'is_active' => true]);
        \App\Models\Unit::create(['name' => 'Milliliter', 'code' => 'ML', 'is_active' => true]);
        \App\Models\Tax::create(['name' => 'VAT 15%', 'value' => 15, 'is_active' => true]);

        $export = new \App\Exports\ProductImportTemplateExport();
        $raw = \Maatwebsite\Excel\Facades\Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        $filePath = sys_get_temp_dir() . '/product-import-test-' . uniqid() . '.xlsx';
        file_put_contents($filePath, $raw);
        $this->assertFileExists($filePath);

        $file = new UploadedFile(
            $filePath,
            'products.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertTrue(
            $response->getSession()->has('success') || $response->getSession()->has('warning'),
            'Import should flash success or warning'
        );
        $this->assertGreaterThan(0, Product::count(), 'At least one product should be imported from template');
        $this->assertDatabaseHas('products', ['name' => 'Soft Drink Powder Mango 500 GM']);
    }

    public function test_import_validation_requires_file()
    {
        $response = $this->post(route('admin.products.import'), []);
        $response->assertSessionHasErrors('file');
    }

    public function test_name_parsing_logic_extracts_unit_and_pcs()
    {
        \App\Models\Unit::create(['name' => 'Gram', 'code' => 'gm', 'is_active' => true]);
        \App\Models\Unit::create(['name' => 'Milliliter', 'code' => 'ml', 'is_active' => true]);

        $import = new \App\Imports\ProductImport();

        // Case 1: Unit and PCS from name when columns not provided (legacy name_of_the_items)
        $row1 = ['name_of_the_items' => 'SAJEEB TAMARIND FRUIT DRINKS 250ML X 24 PCS'];
        $product1 = $import->model($row1);
        $this->assertNotNull($product1);
        $this->assertEquals(250, $product1->unit_value);
        $this->assertEquals(24, $product1->pcs_in_ctn);
        $this->assertEquals(\App\Models\Unit::where('code', 'ml')->first()->id, $product1->unit_id);

        // Case 2: GM unit and PCS from name
        $row2 = ['name_of_the_items' => 'SAJEEB BUTTER COOKIES (800 GM X 06 PCS JAR)'];
        $product2 = $import->model($row2);
        $this->assertNotNull($product2);
        $this->assertEquals(800, $product2->unit_value);
        $this->assertEquals(6, $product2->pcs_in_ctn);
        $this->assertEquals(\App\Models\Unit::where('code', 'gm')->first()->id, $product2->unit_id);

        // Case 3: Canonical 'name' column and unit from name
        $row3 = ['name' => 'SAJEEB SOFT DRINK POWDER MANGO FLAVOUR 500 GM'];
        $product3 = $import->model($row3);
        $this->assertNotNull($product3);
        $this->assertEquals(500, $product3->unit_value);
        $this->assertEquals(1, $product3->pcs_in_ctn);
        $this->assertEquals(\App\Models\Unit::where('code', 'gm')->first()->id, $product3->unit_id);
    }

    public function test_missing_optional_fields_leave_nulls_or_defaults()
    {
        $import = new \App\Imports\ProductImport();

        $row = ['name' => 'Minimal Product', 'pcs_in_ctn' => 1];
        $product = $import->model($row);

        $this->assertNotNull($product);
        $this->assertEquals('Minimal Product', $product->name);
        $this->assertNull($product->category_id);
        $this->assertNull($product->brand_id);
        $this->assertNull($product->tax_id);
        $this->assertEquals(0, $product->quantity);
        $this->assertNull($product->box_price);
        $this->assertNull($product->unit_price);
        $this->assertEquals('piece', $product->stock_unit);
    }

    public function test_template_download_returns_xlsx()
    {
        $response = $this->get(route('admin.products.import.template'));
        $response->assertOk();
        $this->assertStringContainsString('spreadsheet', $response->headers->get('content-type') ?? '');
    }
}
