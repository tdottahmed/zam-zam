<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product']) // Eager load items and products if needed for list
            ->latest()
            ->paginate(10);

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new order (customer bulk order).
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->status !== 'approved') {
            return redirect()->route('orders.index')
                ->with('error', 'Your account must be approved before you can place orders.');
        }

        $categories = Category::where('status', true)->orderBy('name')->get(['id', 'name']);
        $brands = Brand::where('status', true)->orderBy('name')->get(['id', 'name']);
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        return Inertia::render('Orders/Create', [
            'categories' => $categories,
            'brands' => $brands,
            'addresses' => $addresses,
        ]);
    }

    /**
     * Store a new order (customer bulk order).
     */
    public function store(StoreCustomerOrderRequest $request)
    {
        $user = Auth::user();
        if ($user->status !== 'approved') {
            return redirect()->route('orders.index')
                ->with('error', 'Your account must be approved before you can place orders.');
        }

        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated, $user) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $totalPrice = $item['quantity'] * $item['unit_price'];
                $subtotal += $totalPrice;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                    'attributes' => [],
                ];
            }

            $taxAmount = 0;
            $shippingAmount = 0;
            $grandTotal = $subtotal + $taxAmount + $shippingAmount;

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_status' => 'pending',
                'total_amount' => $grandTotal,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['shipping_address'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $data) {
                $order->items()->create($data);
            }

            return $order;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order #' . $order->id . ' placed successfully. We will process it shortly.');
    }

    /**
     * Search products for customer order creation (JSON API).
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');
        $page = $request->get('page', 1);

        $products = Product::query()
            ->with(['category:id,name', 'brand:id,name', 'unit:id,name'])
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q2) use ($query) {
                    $q2->where('name', 'like', "%{$query}%")
                        ->orWhere('product_code', 'like', "%{$query}%");
                });
            })
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($brandId, fn ($q) => $q->where('brand_id', $brandId))
            ->select(['id', 'name', 'product_code', 'unit_price as price', 'category_id', 'brand_id', 'unit_id', 'quantity', 'image'])
            ->orderBy('name')
            ->paginate(12, ['*'], 'page', $page);

        return response()->json($products);
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'invoice', 'creditNotes.items.product']);

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'offlinePaymentMethods' => \App\Models\OfflinePaymentMethod::where('is_active', true)->get()
        ]);
    }

    public function submitPayment(Request $request, Order $order)
    {
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        // Must be an offline payment method to submit payment data this way
        $method = \App\Models\OfflinePaymentMethod::where('name', $order->payment_method)->first();

        if (!$method) {
            return back()->with('error', 'Payment submission is only available for active offline payment methods, or the method is invalid.');
        }

        $rules = [];
        $requiredFields = $method->required_fields ?? [];

        foreach ($requiredFields as $field) {
            $rule = [];
            if (!empty($field['is_required'])) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if (($field['type'] ?? 'text') === 'file') {
                $rule[] = 'file';
                $rule[] = 'max:10240'; // 10MB limit
            } elseif (($field['type'] ?? 'text') === 'number') {
                $rule[] = 'numeric';
            } else {
                $rule[] = 'string';
            }
            
            $rules['payment_data.' . $field['name']] = implode('|', $rule);
        }

        $validated = $request->validate($rules);

        $paymentData = $validated['payment_data'] ?? [];

        // Handle File Uploads
        foreach ($requiredFields as $field) {
            if (($field['type'] ?? 'text') === 'file' && $request->hasFile('payment_data.' . $field['name'])) {
                $path = $request->file('payment_data.' . $field['name'])->store('payment_receipts', 'public');
                $paymentData[$field['name']] = $path;
            }
        }

        $order->update([
            'payment_data' => $paymentData,
            'payment_status' => 'pending', // Keeps it pending so admin can verify
        ]);

        return back()->with('success', 'Payment details submitted successfully. We will verify and process your order soon.');
    }

    public function downloadInvoice(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        $invoice = $order->invoice;

        if (!$invoice) {
            return back()->with('error', 'Invoice not generated yet.');
        }

        $disk = \App\Services\InvoicePdfService::INVOICES_DISK;
        
        if ($invoice->pdf_path && \Illuminate\Support\Facades\Storage::disk($disk)->exists($invoice->pdf_path)) {
            $fullPath = \Illuminate\Support\Facades\Storage::disk($disk)->path($invoice->pdf_path);
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="invoice-' . preg_replace('/[^a-zA-Z0-9\-]/', '-', $invoice->invoice_number) . '.pdf"',
            ]);
        }

        // Fallback to generating it if not found, saving it, and then returning it.
        $pdfService = app(\App\Services\InvoicePdfService::class);
        return $pdfService->generate($invoice, false);
    }
}
