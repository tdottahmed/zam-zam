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
        $query->when($request->category, function ($q, $slugs) {
            $slugs = is_array($slugs) ? $slugs : [$slugs];
            $q->whereHas('category', function ($subQ) use ($slugs) {
                $subQ->whereIn('slug', $slugs);
            });
        });
        
        // Filter by Category ID (if passed directly)
        $query->when($request->category_id, function ($q, $ids) {
            $ids = is_array($ids) ? $ids : [$ids];
            $q->whereIn('category_id', $ids);
        });

        // Filter by Brand
        $query->when($request->brand, function ($q, $slugs) {
            $slugs = is_array($slugs) ? $slugs : [$slugs];
            $q->whereHas('brand', function ($subQ) use ($slugs) {
                $subQ->whereIn('slug', $slugs);
            });
        });

         // Filter by Brand ID (if passed directly)
         $query->when($request->brand_id, function ($q, $ids) {
            $ids = is_array($ids) ? $ids : [$ids];
            $q->whereIn('brand_id', $ids);
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

        if ($request->wantsJson()) {
            return $products;
        }

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

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'unit']);
        return Inertia::render('Shop/Show', [
            'product' => $product,
        ]);
    }
}
