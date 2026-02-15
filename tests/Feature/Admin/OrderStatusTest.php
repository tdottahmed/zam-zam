<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_order_status()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
    }

    public function test_non_admin_cannot_update_order_status()
    {
        $user = User::factory()->create(['user_type' => 'customer']);
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($user)->patch(route('admin.orders.update-status', $order), [
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);

        $response->assertForbidden(); // Assuming there's a middleware or policy. If not, maybe 403 or redirect.
        // Based on web.php, it's under 'auth' and 'admin' middleware.
    }

    public function test_invalid_status_is_rejected()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);
        $order = Order::factory()->create();

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'invalid_status',
            'payment_status' => 'paid',
        ]);

        $response->assertSessionHasErrors(['status']);
    }
}
