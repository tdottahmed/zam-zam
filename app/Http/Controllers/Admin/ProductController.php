<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('product_code', 'like', "%{$search}%");
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
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'pcs_in_ctn' => 'nullable|string|max:255',
            'box_price' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
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
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'pcs_in_ctn' => 'nullable|string|max:255',
            'box_price' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
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
