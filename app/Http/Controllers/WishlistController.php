<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Wishlist;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::with(['product.unit', 'product.brand'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->pluck('product');

        return Inertia::render('Wishlist/Index', [
            'products' => $wishlistItems
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        
        // Toggle logic: if exists, remove it. If not, add it.
        // Or strictly 'add' based on REST? Usually 'toggle' is easier for frontend 'heart' button.
        // Let's implement toggle for 'store' or a specific 'toggle' route?
        // Standard 'store' usually implies adding. Let's do add, and catch duplicate.
        // Actually, the user requirement mentions "toggleWishlist logic" in frontend. 
        // Let's support a toggle endpoint or handle logic here. 
        // To be RESTful: store = add, destroy = remove. Frontend decides which to call.
        
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $request->product_id
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }

    public function destroy(Product $product)
    {
        // We accept Product because frontend usually works with Product IDs.
        // We delete the wishlist entry for this user and product.
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Product removed from wishlist.');
    }
}
