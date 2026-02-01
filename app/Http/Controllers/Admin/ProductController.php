<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Tax;
use App\Models\SystemSetting;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterBy = $request->input('filter_by');

        $products = Product::with(['tax', 'unit', 'category', 'brand'])
            ->when($search, function ($query, $search) use ($filterBy) {
                if ($filterBy && in_array($filterBy, ['name', 'product_code', 'unit_value', 'box_price', 'unit_price'])) {
                    $query->where($filterBy, 'like', "%{$search}%");
                } else {
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('product_code', 'like', "%{$search}%");
                    });
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taxes = Tax::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $defaultProfitMargin = SystemSetting::where('group', 'profit_margin')
            ->where('key', 'default_profit_margin')
            ->value('value');

        return view('admin.products.create', compact('taxes', 'units', 'categories', 'brands', 'defaultProfitMargin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_value' => 'nullable|numeric|min:0',
            'unit_id' => 'nullable|exists:units,id',
            'pcs_in_ctn' => 'nullable|string|max:255',
            'box_price' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'tax_id' => 'nullable|exists:taxes,id',
            'buying_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $taxes = Tax::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $defaultProfitMargin = SystemSetting::where('group', 'profit_margin')
            ->where('key', 'default_profit_margin')
            ->value('value');

        return view('admin.products.edit', compact('product', 'taxes', 'units', 'categories', 'brands', 'defaultProfitMargin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_value' => 'nullable|numeric|min:0',
            'unit_id' => 'nullable|exists:units,id',
            'pcs_in_ctn' => 'nullable|string|max:255',
            'box_price' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'tax_id' => 'nullable|exists:taxes,id',
            'buying_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
