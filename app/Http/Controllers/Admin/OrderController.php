<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Http\Requests\Admin\StoreOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('shipping_address->name', 'like', "%{$search}%")
                  ->orWhere('shipping_address->email', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(10)->withQueryString();
        
        // Stats
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        return view('admin.orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'totalRevenue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // Provide users for selection
        // In a real app, you might want to fetch products via AJAX or load a subset
        $products = Product::all();
        $categories = \App\Models\Category::where('status', true)->get();
        $brands = \App\Models\Brand::where('status', true)->get();
        
        return view('admin.orders.create', compact('users', 'products', 'categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            // Calculate totals
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $totalPrice = $item['quantity'] * $item['unit_price']; // Use provided price or product price? Using provided for flexibility
                $subtotal += $totalPrice;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                    'attributes' => [] // Add attributes logic if needed
                ];
            }
            
            // Tax and Shipping (Should be dynamic, simplified for now)
            $taxAmount = 0; // Implement tax logic if needed
            $shippingAmount = 0; // Implement shipping logic if needed
            $grandTotal = $subtotal + $taxAmount + $shippingAmount;

            $order = Order::create([
                'user_id' => $validated['user_id'],
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'total_amount' => $grandTotal,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'] ?? $validated['shipping_address'], // Fallback to shipping if billing not provided
                'notes' => $validated['notes'],
            ]);

            foreach ($itemsData as $data) {
                $order->items()->create($data);
            }
        });

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'user', 'invoice', 'creditNotes');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load('items', 'user');
        $users = User::all();
        $products = Product::all();
        $categories = \App\Models\Category::where('status', true)->get();
        $brands = \App\Models\Brand::where('status', true)->get();

        return view('admin.orders.edit', compact('order', 'users', 'products', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $order) {
                // ... (transaction logic remains same as per user's view, we are just wrapping it)
                // 1. Update Order Details
                $order->update([
                    'user_id' => $validated['user_id'],
                    'status' => $validated['status'],
                    'payment_status' => $validated['payment_status'],
                    'shipping_address' => $validated['shipping_address'],
                    'billing_address' => $validated['billing_address'] ?? $validated['shipping_address'],
                    'notes' => $validated['notes'],
                ]);

                // 2. Sync Items (Smart Sync to handle FK constraints)
                $existingItems = $order->items->keyBy('product_id');
                $submittedProductIds = [];
                $subtotal = 0;

                foreach ($validated['items'] as $item) {
                    $productId = $item['product_id'];
                    $submittedProductIds[] = $productId;
                    
                    $product = Product::find($productId);
                    $totalPrice = $item['quantity'] * $item['unit_price'];
                    $subtotal += $totalPrice;

                    if ($existingItems->has($productId)) {
                        // Update existing item
                        $existingItems[$productId]->update([
                            'product_name' => $product->name,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'total_price' => $totalPrice,
                        ]);
                    } else {
                        // Create new item
                        $order->items()->create([
                            'product_id' => $productId,
                            'product_name' => $product->name,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'total_price' => $totalPrice,
                            'attributes' => [] 
                        ]);
                    }
                }

                // 3. Remove items not present in submission
                // Note: This will correctly fail if trying to delete an item that has an associated credit note
                $order->items()->whereNotIn('product_id', $submittedProductIds)->delete();

                // 4. Update Totals
                 $taxAmount = 0; 
                 $shippingAmount = 0;
                 $grandTotal = $subtotal + $taxAmount + $shippingAmount;
                 
                 $order->update([
                     'total_amount' => $grandTotal,
                     'shipping_amount' => $shippingAmount,
                     'tax_amount' => $taxAmount,
                 ]);
            });

            return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Check for Integrity Constraint Violation (1451)
            if ($e->getCode() == '23000' && str_contains($e->getMessage(), 'credit_note_items_order_item_id_foreign')) {
                return back()
                    ->withInput()
                    ->withErrors(['items' => 'Cannot remove an item that has an associated Credit Note. Please check the Credit Notes section or reject/delete the credit note first.']);
            }
            
            // Generic fallback
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the order: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * Search Users API
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        
        $users = User::query()
            ->with('addresses')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    /**
     * Search Products API
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');
        
        $products = Product::query()
            ->with(['category:id,name', 'brand:id,name', 'unit:id,name', 'tax']) // Eager load relationships
            ->when($query, function($q) use ($query) {
                $q->where(function ($q2) use ($query) {
                    $q2->where('name', 'like', "%{$query}%")
                       ->orWhere('product_code', 'like', "%{$query}%");
                });
            })
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($brandId, function($q) use ($brandId) {
                $q->where('brand_id', $brandId);
            })
            ->select(['id', 'name', 'product_code', 'unit_price as price', 'box_price', 'category_id', 'brand_id', 'unit_id', 'tax_id', 'quantity', 'image', 'pcs_in_ctn']) 
            ->paginate(12);

        return response()->json($products);
    }

    /**
     * Export Order as PDF
     */
    public function exportPdf(Order $order, \App\Services\OrderPdfService $pdfService)
    {
        return $pdfService->generate($order);
    }
}
