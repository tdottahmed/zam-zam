<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        
        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.email' => 'required|email',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.postal_code' => 'required|string',
            'shipping_address.country' => 'required|string',
            'billing_address' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

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
        $order->load('items.product', 'user', 'invoice');
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

        return view('admin.orders.edit', compact('order', 'users', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.email' => 'required|email',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.postal_code' => 'required|string',
            'shipping_address.country' => 'required|string',
            'billing_address' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $order) {
            // 1. Update Order Details
            $order->update([
                'user_id' => $validated['user_id'],
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'] ?? $validated['shipping_address'],
                'notes' => $validated['notes'],
            ]);

            // 2. Sync Items (Simplified: Delete all and recreate)
            // A better approach for production might be diffing, but this ensures consistency
            $order->items()->delete();

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $totalPrice = $item['quantity'] * $item['unit_price'];
                $subtotal += $totalPrice;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                    'attributes' => []
                ]);
            }

            // 3. Update Totals
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
        
        $products = Product::query()
            ->with(['category:id,name', 'brand:id,name', 'unit:id,name', 'tax']) // Eager load relationships
            ->where('name', 'like', "%{$query}%")
            ->orWhere('product_code', 'like', "%{$query}%")
            ->select(['id', 'name', 'product_code', 'unit_price as price', 'category_id', 'brand_id', 'unit_id', 'tax_id', 'quantity']) 
            ->limit(20)
            ->get();

        return response()->json($products);
    }
}
