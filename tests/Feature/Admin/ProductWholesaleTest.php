<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductWholesaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_wholesale_product_logic()
    {
        // Simple user creation
        $email = 'admin_' . Str::random(10) . '@example.com';
        $user = User::factory()->create([
            'user_type' => 'admin',
            'email' => $email,
        ]);

        $this->assertDatabaseHas('users', ['email' => $email]);

        $code = 'WTP-' . Str::random(10);

        $response = $this->actingAs($user)->post(route('admin.products.store'), [
            'name' => 'Wholesale Test Product',
            'product_code' => $code,
            'pcs_in_ctn' => 12,
            'box_price' => 120,
            'buying_price' => 100,
            'quantity' => 100,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'product_code' => $code,
            'pcs_in_ctn' => 12,
            'unit_price' => 10, // 120 / 12 = 10
        ]);
    }

    public function test_default_pcs_in_ctn_validation()
    {
         $user = User::factory()->create(['user_type' => 'admin', 'email' => 'admin_2_' . Str::random(10) . '@example.com']);
        
        $response = $this->actingAs($user)->post(route('admin.products.store'), [
            'name' => 'Wholesale Test Product 2',
            'product_code' => 'WTP-' . Str::random(10),
            // 'pcs_in_ctn' => omitted
             'box_price' => 50,
        ]);

        $response->assertSessionHasErrors(['pcs_in_ctn']);
    }
}
