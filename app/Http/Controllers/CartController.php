<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id]
        );

        $cartItem = $cart->items()->where('product_id', $validated['product_id'])->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        // Remove from wishlist if exists
        \App\Models\Wishlist::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->delete();

        return back()->with('success', 'Product added to cart.');
    }
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('items.product.unit')->first();

        $subtotal = 0;
        $items = [];
        $tax = 0;
        $shipping = 0; // Flat rate or calculated
        $total = 0;

        if ($cart) {
            foreach ($cart->items as $item) {
                // Ensure product exists
                if (!$item->product) continue;

                $itemTotal = $item->product->unit_price * $item->quantity;
                $subtotal += $itemTotal;

                $items[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'slug' => $item->product->slug ?? 'product',
                    'image' => $item->product->image,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->unit_price,
                    // Use unit name (code) or fallback to unit_value + ' Units' or just 'unit'
                    'unit' => $item->product->unit ? $item->product->unit->code : ($item->product->unit_value ? $item->product->unit_value : 'unit'), 
                    'total' => $itemTotal,
                ];
            }
            
             // Calculate Tax (Get active tax rate)
            $taxRate = \App\Models\Tax::where('is_active', true)->sum('value');
            $tax = $subtotal * ($taxRate / 100);

            // Shipping Logic (Example: Free over $100, else $15)
            $shipping = $subtotal > 100 ? 0 : 15.00;

            $total = $subtotal + $tax + $shipping;
        }

        return \Inertia\Inertia::render('Cart/Index', [
            'cart' => [
                'items' => $items,
                'summary' => [
                    'subtotal' => round($subtotal, 2),
                    'tax' => round($tax, 2),
                    'shipping' => round($shipping, 2),
                    'total' => round($total, 2),
                    'tax_rate' => $taxRate,
                ]
            ]
        ]);
    }

    public function update(Request $request, CartItem $item)
    {
        // Ensure user owns the cart item
        if ($item->cart->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->update(['quantity' => $request->quantity]);

        return back();
    }

    public function destroy(CartItem $item)
    {
        // Ensure user owns the cart item
        if ($item->cart->user_id != Auth::id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
