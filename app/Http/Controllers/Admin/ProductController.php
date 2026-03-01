<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Tax;
use App\Models\SystemSetting;
use App\Models\Unit;
use App\Imports\ProductImport;
use App\Exports\ProductImportTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

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
                    $query->where(function ($q) use ($search) {
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
     * Toggle product featured flag.
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => ! $product->is_featured]);
        return back()->with('success', $product->is_featured ? 'Product marked as featured.' : 'Product removed from featured.');
    }

    /**
     * Export product list as PDF (respects current index filters).
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $filterBy = $request->input('filter_by');

        $products = Product::with(['tax', 'unit', 'category', 'brand'])
            ->when($search, function ($query, $search) use ($filterBy) {
                if ($filterBy && in_array($filterBy, ['name', 'product_code', 'unit_value', 'box_price', 'unit_price'])) {
                    $query->where($filterBy, 'like', "%{$search}%");
                } else {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('product_code', 'like', "%{$search}%");
                    });
                }
            })
            ->latest()
            ->get();

        $settings = SystemSetting::where('group', 'general')->pluck('value', 'key');
        $company = [
            'name' => $settings['site_name'] ?? 'ZamZam Import and Export Inc.',
            'address' => nl2br(e($settings['address'] ?? "1-283 Morningside Ave\nScarborough, Ontario, M1E 3G1\nCanada")),
            'phone' => $settings['contact_phone'] ?? '+1 416-283-4488',
            'cell' => $settings['contact_cell'] ?? '+1 647-482-1133',
            'email' => $settings['contact_email'] ?? 'zamzamimport2023@gmail.com',
            'tax_id' => $settings['tax_id'] ?? '731247144RT0001',
        ];

        $generatedAt = now()->format('M d, Y g:i A');

        $pdf = PDF::loadView('pdf.products', compact('products', 'company', 'generatedAt'));
        return $pdf->stream('products-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Get the next available SKU.
     */
    public function nextSku()
    {
        $lastProduct = Product::latest('id')->first();
        $nextId = $lastProduct ? $lastProduct->id + 1 : 1;
        $sku = 'PCB-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return response()->json(['sku' => $sku]);
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
        $defaultProfitMargin = SystemSetting::where('group', 'general')
            ->where('key', 'default_profit_margin')
            ->value('value') ?? 0;

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
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_value' => 'nullable|numeric|min:0',
            'unit_id' => 'nullable|exists:units,id',
            'pcs_in_ctn' => 'required|integer|min:1',
            'tax_id' => 'nullable|exists:taxes,id',
            'buying_price_stock_unit' => 'nullable|numeric|min:0',
            'selling_price_stock_unit' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'stock_unit' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        // Default to 1 if not provided or invalid
        if (empty($validated['pcs_in_ctn']) || $validated['pcs_in_ctn'] < 1) {
            $validated['pcs_in_ctn'] = 1;
        }

        $pcs = (int) $validated['pcs_in_ctn'];
        $stockUnit = $validated['stock_unit'] ?? 'piece';
        $multiplier = $stockUnit === 'piece' ? 1 : ($stockUnit === 'dozen' ? 12 : $pcs);

        if (isset($validated['buying_price_stock_unit']) && $validated['buying_price_stock_unit'] !== '' && $multiplier > 0) {
            $validated['buying_price'] = (float) $validated['buying_price_stock_unit'] / $multiplier;
        } else {
            $validated['buying_price'] = null;
        }
        unset($validated['buying_price_stock_unit']);

        if (isset($validated['selling_price_stock_unit']) && $validated['selling_price_stock_unit'] !== '' && $multiplier > 0) {
            $validated['unit_price'] = (float) $validated['selling_price_stock_unit'] / $multiplier;
            $validated['box_price'] = $validated['unit_price'] * $pcs;
        } else {
            $validated['unit_price'] = null;
            $validated['box_price'] = null;
        }
        unset($validated['selling_price_stock_unit']);

        $validated['stock_unit'] = $stockUnit;
        $validated['quantity'] = (int) ($validated['quantity'] ?? 0);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

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
        $defaultProfitMargin = SystemSetting::where('group', 'general')
            ->where('key', 'default_profit_margin')
            ->value('value') ?? 0;

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
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_value' => 'nullable|numeric|min:0',
            'unit_id' => 'nullable|exists:units,id',
            'pcs_in_ctn' => 'required|integer|min:1',
            'tax_id' => 'nullable|exists:taxes,id',
            'buying_price_stock_unit' => 'nullable|numeric|min:0',
            'selling_price_stock_unit' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'stock_unit' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        // Default to 1 if not provided or invalid
        if (empty($validated['pcs_in_ctn']) || $validated['pcs_in_ctn'] < 1) {
            $validated['pcs_in_ctn'] = 1;
        }

        $pcs = (int) $validated['pcs_in_ctn'];
        $stockUnit = $validated['stock_unit'] ?? 'piece';
        $multiplier = $stockUnit === 'piece' ? 1 : ($stockUnit === 'dozen' ? 12 : $pcs);

        if (isset($validated['buying_price_stock_unit']) && $validated['buying_price_stock_unit'] !== '' && $multiplier > 0) {
            $validated['buying_price'] = (float) $validated['buying_price_stock_unit'] / $multiplier;
        } else {
            $validated['buying_price'] = null;
        }
        unset($validated['buying_price_stock_unit']);

        if (isset($validated['selling_price_stock_unit']) && $validated['selling_price_stock_unit'] !== '' && $multiplier > 0) {
            $validated['unit_price'] = (float) $validated['selling_price_stock_unit'] / $multiplier;
            $validated['box_price'] = $validated['unit_price'] * $pcs;
        } else {
            $validated['unit_price'] = null;
            $validated['box_price'] = null;
        }
        unset($validated['selling_price_stock_unit']);

        $validated['stock_unit'] = $stockUnit;
        $validated['quantity'] = (int) ($validated['quantity'] ?? 0);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

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

    /**
     * Bulk remove the specified resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        Product::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.products.index')
            ->with('success', count($request->ids) . ' product(s) deleted successfully.');
    }

    /**
     * Bulk export selected products as PDF.
     */
    public function bulkExportPdf(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        $products = Product::with(['tax', 'unit', 'category', 'brand'])
            ->whereIn('id', $request->ids)
            ->latest()
            ->get();

        $settings = SystemSetting::where('group', 'general')->pluck('value', 'key');
        $company = [
            'name' => $settings['site_name'] ?? 'ZamZam Import and Export Inc.',
            'address' => nl2br(e($settings['address'] ?? "1-283 Morningside Ave\nScarborough, Ontario, M1E 3G1\nCanada")),
            'phone' => $settings['contact_phone'] ?? '+1 416-283-4488',
            'cell' => $settings['contact_cell'] ?? '+1 647-482-1133',
            'email' => $settings['contact_email'] ?? 'zamzamimport2023@gmail.com',
            'tax_id' => $settings['tax_id'] ?? '731247144RT0001',
        ];

        $generatedAt = now()->format('M d, Y g:i A');

        $pdf = PDF::loadView('pdf.products', compact('products', 'company', 'generatedAt'));
        return $pdf->stream('products-bulk-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download the Product Import Excel template (with instructions and sample rows).
     */
    public function downloadImportTemplate()
    {
        $filename = 'product-import-template-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ProductImportTemplateExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * Import products from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new ProductImport;

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()
                ->with('import_failures', $failures)
                ->with('error', 'Some rows have validation errors. Please fix them and try again.');
        } catch (\Exception $e) {
            \Log::error('Import Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error during import: ' . $e->getMessage());
        }

        $failures = $import->failures();
        $failureCount = $failures->count();

        if ($failureCount > 0) {
            return redirect()->route('admin.products.index')
                ->with('import_failures', $failures)
                ->with('warning', "Import completed with issues. Some rows could not be imported ({$failureCount} row(s) failed). Check the details below.");
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Products imported successfully.');
    }
}
