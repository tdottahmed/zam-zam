<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'unit']);

        // Filter by Search
        $query->when($request->search, function ($q, $search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%");
            });
        });

        // Filter by Category
        $query->when($request->category, function ($q, $slug) {
            $q->whereHas('category', function ($subQ) use ($slug) {
                $subQ->where('slug', $slug);
            });
        });
        
        // Filter by Category ID (if passed directly)
        $query->when($request->category_id, function ($q, $id) {
            $q->where('category_id', $id);
        });

        // Filter by Brand
        $query->when($request->brand, function ($q, $slug) {
            $q->whereHas('brand', function ($subQ) use ($slug) {
                $subQ->where('slug', $slug);
            });
        });

         // Filter by Brand ID (if passed directly)
         $query->when($request->brand_id, function ($q, $id) {
            $q->where('brand_id', $id);
        });

        // Filter by Price Range
        $query->when($request->min_price, function ($q, $price) {
            $q->where('unit_price', '>=', $price);
        });
        $query->when($request->max_price, function ($q, $price) {
            $q->where('unit_price', '<=', $price);
        });

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return Inertia::render('Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['search', 'category', 'brand', 'min_price', 'max_price', 'category_id', 'brand_id']),
        ]);
    }

    public function brands()
    {
        $brands = Brand::where('status', true)->get();
        return Inertia::render('Shop/Brands', [
            'brands' => $brands,
        ]);
    }
}
