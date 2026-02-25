<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CreditNote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

use App\Services\CreditNoteService;

class CreditNoteController extends Controller
{
    protected $creditNoteService;

    public function __construct(CreditNoteService $creditNoteService)
    {
        $this->creditNoteService = $creditNoteService;
    }

    public function index()
    {
        $creditNotes = CreditNote::where('user_id', auth()->id())
            ->with(['order:id,created_at'])
            ->latest()
            ->paginate(10);

        return Inertia::render('CreditNotes/Index', [
            'creditNotes' => $creditNotes
        ]);
    }

    public function create()
    {
        return Inertia::render('CreditNotes/Create');
    }

    public function searchOrderedItems(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Find items this user has ordered that match the search query
        $items = \App\Models\OrderItem::whereHas('order', function($q) {
            $q->where('user_id', auth()->id())
              ->whereIn('status', ['completed', 'processing']);
        })
        ->where(function($q) use ($query) {
            $q->where('product_name', 'like', "%{$query}%")
              ->orWhereHas('product', function($q2) use ($query) {
                  $q2->where('product_code', 'like', "%{$query}%");
              });
        })
        ->with(['order:id,created_at,status', 'product:id,product_code,image'])
        ->get();

        // Group by product_id so we can easily tell if it was in multiple orders
        $grouped = $items->groupBy('product_id')->map(function($productItems) {
            // we have identical products, maybe from different orders or the same order (unlikely if unique lines, but possible)
            $first = $productItems->first();
            
            // Map the distinct orders this product was bought in
            $orders = $productItems->map(function($item) {
                return [
                    'order_item_id' => $item->id,
                    'order_id' => $item->order_id,
                    'order_date' => $item->order->created_at->format('M d, Y'),
                    'quantity_bought' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ];
            })->unique('order_id')->values();

            return [
                'product_id' => $first->product_id,
                'product_name' => $first->product_name,
                'product_code' => $first->product->product_code ?? '',
                'image' => $first->product->image ?? null,
                'orders' => $orders
            ];
        })->values();

        return response()->json($grouped);
    }
    
    public function getOrderItems(Order $order)
    {
        // Fetch all items from a specific order to populate the return table
        if ($order->user_id != auth()->id()) {
            abort(403);
        }
        
        $order->load('items.product:id,product_code,image');
        
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.selected' => 'required|boolean',
            'items.*.reason' => 'nullable|string', // Per-item reason
        ]);

        // Filter only selected items
        $selectedItems = collect($validated['items'])->filter(fn($item) => $item['selected']);

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['items' => 'Please select at least one item to return.']);
        }

        // Validate that all selected items belong to orders owned by this user
        $orderItemIds = $selectedItems->pluck('id');
        $validItemsCount = \App\Models\OrderItem::whereIn('id', $orderItemIds)
            ->whereHas('order', function($q) {
                $q->where('user_id', auth()->id());
            })->count();

        if ($validItemsCount !== $selectedItems->count()) {
            abort(403, 'Unauthorized access to some order items.');
        }

        try {
            $creditNote = $this->creditNoteService->createDraft(
                auth()->user(),
                $selectedItems->toArray(),
                $validated['reason'],
                $validated['description']
            );

            // Notify Admins
            \App\Models\User::where('user_type', 'admin')->get()->each->notify(new \App\Notifications\CreditNoteCreatedNotification($creditNote));

            return redirect()->route('credit-notes.show', $creditNote)->with('success', 'Credit note request submitted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to submit request: ' . $e->getMessage()]);
        }
    }

    public function show(CreditNote $creditNote)
    {
        if ($creditNote->user_id != auth()->id()) {
            abort(403);
        }

        $creditNote->load(['items.product', 'order:id,created_at', 'items.orderItem.order:id,created_at']);

        return Inertia::render('CreditNotes/Show', [
            'creditNote' => $creditNote
        ]);
    }

    public function edit(CreditNote $creditNote)
    {
        if ($creditNote->user_id != auth()->id() || $creditNote->status !== 'draft') {
            abort(403);
        }

        $creditNote->load(['items.product', 'items.orderItem.order:id,created_at']);

        return Inertia::render('CreditNotes/Edit', [
            'creditNote' => $creditNote
        ]);
    }

    public function update(Request $request, CreditNote $creditNote)
    {
        if ($creditNote->user_id != auth()->id() || $creditNote->status !== 'draft') {
            abort(403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.selected' => 'required|boolean',
            'items.*.reason' => 'nullable|string',
        ]);

        $selectedItems = collect($validated['items'])->filter(fn($item) => $item['selected']);

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['items' => 'Please select at least one item to return.']);
        }

        // Validate that all selected items belong to orders owned by this user
        $orderItemIds = $selectedItems->pluck('id');
        $validItemsCount = \App\Models\OrderItem::whereIn('id', $orderItemIds)
            ->whereHas('order', function($q) {
                $q->where('user_id', auth()->id());
            })->count();

        if ($validItemsCount !== $selectedItems->count()) {
            abort(403, 'Unauthorized access to some order items.');
        }

        try {
            $this->creditNoteService->updateDraft(
                $creditNote,
                $selectedItems->toArray(),
                $validated['reason'],
                $validated['description']
            );

            return redirect()->route('credit-notes.show', $creditNote)->with('success', 'Credit note updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update request: ' . $e->getMessage()]);
        }
    }

    public function destroy(CreditNote $creditNote)
    {
        if ($creditNote->user_id != auth()->id() || $creditNote->status !== 'draft') {
            abort(403);
        }

        $this->creditNoteService->deleteDraft($creditNote);

        return redirect()->route('credit-notes.index')->with('success', 'Credit note deleted successfully.');
    }
}
