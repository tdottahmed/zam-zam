<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart;
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $addresses = $request->user()->addresses()->latest()->get();

        return Inertia::render('Checkout/Index', [
            'addresses' => $addresses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.zip' => 'required|string',
            'shipping_address.country' => 'required|string',
            'payment_method' => 'required|string|in:cod', // Only COD for now
            'save_address' => 'boolean',
            'address_id' => 'nullable', // NEW: Allow validation of address_id
        ]);

        $user = $request->user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        try {
            DB::beginTransaction();

            // Save address if requested
            // Save address if requested AND it's a new address
            if ($request->boolean('save_address') && $request->input('address_id') === 'new') {
                $user->addresses()->create([
                    'type' => 'shipping',
                    'name' => $validated['shipping_address']['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address_line_1' => $validated['shipping_address']['address'],
                    'city' => $validated['shipping_address']['city'],
                    'postal_code' => $validated['shipping_address']['zip'],
                    'country' => $validated['shipping_address']['country'],
                    'is_default' => $user->addresses()->doesntExist(), // First address is default
                ]);
            }

            // Calculate totals
            $subtotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->unit_price);
            $shipping = 0; // Free shipping for now
            $tax = 0; // Tax calculation logic later
            $total = $subtotal + $shipping + $tax;

            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $total,
                'shipping_amount' => $shipping,
                'tax_amount' => $tax,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['shipping_address'], // Use shipping as billing for now
            ]);

            // Create Order Items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->unit_price,
                    'total_price' => $item->quantity * $item->product->unit_price,
                    'attributes' => null, // Add attributes if needed
                ]);
            }

            // Clear Cart
            $cart->items()->delete();
            // Optional: $cart->delete(); if you want to remove the cart itself, but usually we keep the cart shell

            DB::commit();

            // Dispatch Order Created Event
            \App\Events\OrderCreated::dispatch($order);

            // Notify Admins
            \App\Models\User::where('user_type', 'admin')->get()->each->notify(new \App\Notifications\OrderPlacedNotification($order));

            return redirect()->route('shop.index')->with('success', 'Order placed successfully! Order ID: ' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to place order: ' . $e->getMessage()]);
        }
    }
}
