<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ShippingMethod;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_shipping_methods()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.shipping-methods.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_shipping_method()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.shipping-methods.store'), [
            'name' => 'Express',
            'cost' => 15.00,
            'estimated_delivery_time' => '1-2 Days',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.shipping-methods.index'));
        $this->assertDatabaseHas('shipping_methods', ['name' => 'Express']);
    }

    public function test_admin_can_create_shipping_method_without_cost()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.shipping-methods.store'), [
            'name' => 'Pickup',
            'cost' => null,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.shipping-methods.index'));
        $this->assertDatabaseHas('shipping_methods', [
            'name' => 'Pickup',
            'cost' => null,
        ]);
    }

    public function test_customer_can_see_shipping_methods_at_checkout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a shipping method
        ShippingMethod::create([
            'name' => 'Standard',
            'cost' => 5.00,
            'is_active' => true,
        ]);

        // Add item to cart to access checkout
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('shippingMethods', 1)
        );
    }
}
