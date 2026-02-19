<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSlugTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['user_type' => 'admin']);
    }

    public function test_can_create_product_with_explicit_slug()
    {
        $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'slug' => 'test-product-custom-slug',
            'pcs_in_ctn' => 1,
            'description' => 'Test Description',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'slug' => 'test-product-custom-slug',
        ]);
    }

    public function test_can_create_product_with_auto_generated_slug()
    {
        $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
            'name' => 'Test Product Auto',
            'pcs_in_ctn' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product Auto',
            'slug' => 'test-product-auto',
        ]);
    }

    public function test_can_update_product_slug()
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'slug' => 'old-slug',
        ]);

        $response = $this->actingAs($this->user)->put(route('admin.products.update', $product), [
            'name' => 'Old Name',
            'slug' => 'new-slug',
            'pcs_in_ctn' => $product->pcs_in_ctn,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'slug' => 'new-slug',
        ]);
    }

    public function test_slug_must_be_unique()
    {
        Product::factory()->create(['slug' => 'unique-slug']);

        $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
            'name' => 'Another Product',
            'slug' => 'unique-slug',
        ]);

        $response->assertSessionHasErrors('slug');
    }
}
