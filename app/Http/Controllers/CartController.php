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

        return back()->with('success', 'Product added to cart.');
    }
    public function index()
    {
        return \Inertia\Inertia::render('Cart/Index');
    }

    public function update(Request $request, CartItem $item)
    {
        // Ensure user owns the cart item
        if ((int) $item->cart->user_id !== (int) Auth::id()) {
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
        if ((int) $item->cart->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
