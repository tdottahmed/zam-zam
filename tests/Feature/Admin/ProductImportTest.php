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
        $filePath = public_path('Sajeeb Barcodes.xlsx');
        
        // Ensure the file exists for testing
        if (!file_exists($filePath)) {
            $this->markTestSkipped('Sample excel file not found for testing.');
        }

        $file = new UploadedFile(
            $filePath,
            'Sajeeb Barcodes.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        // Check if some products were imported
        $this->assertGreaterThan(0, Product::count());
        
        // Check for a specific product from the shared strings we saw
        $this->assertDatabaseHas('products', [
            'name' => 'SAJEEB SOFT DRINK POWDER MANGO FLAVOUR 500 GM',
        ]);
    }

    public function test_import_validation_requires_file()
    {
        $response = $this->post(route('admin.products.import'), []);
        $response->assertSessionHasErrors('file');
    }

    public function test_name_parsing_logic_extracts_unit_and_pcs()
    {
        // Seed some units
        \App\Models\Unit::create(['name' => 'Gram', 'code' => 'gm', 'is_active' => true]);
        \App\Models\Unit::create(['name' => 'Milliliter', 'code' => 'ml', 'is_active' => true]);

        $import = new \App\Imports\ProductImport();

        // Case 1: Unit and PCS hint
        $row1 = ['name_of_the_items' => 'SAJEEB TAMARIND FRUIT DRINKS 250ML X 24 PCS'];
        $product1 = $import->model($row1);
        
        $this->assertEquals(250, $product1->unit_value);
        $this->assertEquals(24, $product1->pcs_in_ctn);
        $this->assertEquals(\App\Models\Unit::where('code', 'ml')->first()->id, $product1->unit_id);

        // Case 2: GM unit and PCS hint in parentheses
        $row2 = ['name_of_the_items' => 'SAJEEB BUTTER COOKIES (800 GM X 06 PCS JAR)'];
        $product2 = $import->model($row2);
        
        $this->assertEquals(800, $product2->unit_value);
        $this->assertEquals(6, $product2->pcs_in_ctn);
        $this->assertEquals(\App\Models\Unit::where('code', 'gm')->first()->id, $product2->unit_id);

        // Case 3: Just unit hint
        $row3 = ['name_of_the_items' => 'SAJEEB SOFT DRINK POWDER MANGO FLAVOUR 500 GM'];
        $product3 = $import->model($row3);
        
        $this->assertEquals(500, $product3->unit_value);
        $this->assertEquals(1, $product3->pcs_in_ctn); // Default 1
        $this->assertEquals(\App\Models\Unit::where('code', 'gm')->first()->id, $product3->unit_id);
    }

    public function test_missing_fields_are_populated_with_random_defaults()
    {
        // Seed necessary data
        \App\Models\Category::factory()->create();
        \App\Models\Brand::factory()->create();
        \App\Models\Tax::create(['name' => 'No Tax', 'value' => 0, 'is_active' => true]);

        $import = new \App\Imports\ProductImport();

        $row = ['name_of_the_items' => 'Minimal Product'];
        $product = $import->model($row);

        $this->assertNotNull($product->category_id);
        $this->assertNotNull($product->brand_id);
        $this->assertNotNull($product->tax_id);
        $this->assertGreaterThanOrEqual(10, $product->quantity);
        $this->assertLessThanOrEqual(100, $product->quantity);
        $this->assertGreaterThanOrEqual(100, $product->box_price);
        $this->assertLessThanOrEqual(1000, $product->box_price);
        $this->assertNotNull($product->buying_price);
        $this->assertNotNull($product->unit_price);
    }
}
