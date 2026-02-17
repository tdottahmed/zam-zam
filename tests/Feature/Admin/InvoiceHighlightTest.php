<?php

namespace Tests\Feature\Admin;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceHighlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_invoice_with_highlighted_items()
    {
        // 1. Create admin user
        $admin = User::factory()->create(['user_type' => 'admin', 'email' => 'admin@example.com']);

        // 2. Create a customer and an order
        $customer = User::factory()->create(['name' => 'Test Customer']);
        $product = Product::factory()->create(['name' => 'Test Product', 'quantity' => 100, 'unit_price' => 50]);
        
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-123',
            'status' => 'pending',
            'total_amount' => 100,
            'payment_status' => 'unpaid',
            'shipping_address' => ['name' => 'Test', 'address' => '123 St', 'city' => 'City', 'zip' => '12345', 'country' => 'Country', 'phone' => '1234567890'],
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'unit_price' => 50,
            'total_price' => 100,
            'tax_amount' => 0,
        ]);

        // 3. Define invoice data with highlight
        $invoiceData = [
            'invoice_number' => 'INV-TEST-001',
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'notes' => 'Test Invoice',
            'items' => [
                $orderItem->id => [
                    'selected' => 'on',
                    'quantity' => 2,
                    'price' => 50,
                    'is_highlighted' => 'on', // Checkbox value
                    'highlight_color' => '#ff0000', // Red highlight
                ]
            ],
            'shipping_amount' => 10,
            'discount_total' => 5,
        ];

        // 4. Submit invoice creation request
        $response = $this->actingAs($admin)->post(route('admin.orders.invoice.store', $order), $invoiceData);

        // 5. Assertions
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(); // Should redirect to invoice show page

        // Verify invoice created
        $this->assertDatabaseHas('invoices', [
            'invoice_number' => 'INV-TEST-001',
            'order_id' => $order->id,
        ]);

        $invoice = Invoice::where('invoice_number', 'INV-TEST-001')->first();

        // Verify invoice item has highlight color
        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'highlight_color' => '#ff0000',
        ]);
    }

    public function test_can_store_invoice_without_highlight()
    {
        // 1. Create admin and data
        $admin = User::factory()->create(['user_type' => 'admin']);
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $admin->id, // just use admin as user for simplicity
            'order_number' => 'ORD-456',
            'status' => 'pending',
            'total_amount' => 50,
            'payment_status' => 'unpaid',
            'shipping_address' => ['name' => 'Test', 'address' => '123 St', 'city' => 'City', 'zip' => '12345', 'country' => 'Country', 'phone' => '1234567890'],
        ]);
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 50,
            'total_price' => 50,
            'tax_amount' => 0,
        ]);

        // 2. Data without highlight
        $invoiceData = [
            'invoice_number' => 'INV-TEST-002',
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'items' => [
                $orderItem->id => [
                    'selected' => 'on',
                    'quantity' => 1,
                    'price' => 50,
                    // No highlight fields
                ]
            ],
        ];

        // 3. Submit
        $response = $this->actingAs($admin)->post(route('admin.orders.invoice.store', $order), $invoiceData);

        // 4. Assertions
        $response->assertSessionHasNoErrors();
        
        $invoice = Invoice::where('invoice_number', 'INV-TEST-002')->first();
        
        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'highlight_color' => null,
        ]);
    }
}
