<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Services\CreditNoteService; // Assuming this exists, based on previous context
use Illuminate\Http\Request;

class CreditNoteController extends Controller
{
    protected $creditNoteService;

    public function __construct(CreditNoteService $creditNoteService)
    {
        $this->creditNoteService = $creditNoteService;
    }

    public function index(Request $request)
    {
        $query = CreditNote::with(['user'])->withCount('items')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('credit_note_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $creditNotes = $query->paginate(15)->withQueryString();

        return view('admin.credit_notes.index', compact('creditNotes'));
    }

    public function searchOrderedItems(Request $request)
    {
        $query = $request->get('q');
        $userId = $request->get('user_id');
        
        if (strlen($query) < 2 || !$userId) {
            return response()->json([]);
        }

        $items = \App\Models\OrderItem::whereHas('order', function($q) use ($userId) {
            $q->where('user_id', $userId)
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

        $grouped = $items->groupBy('product_id')->map(function($productItems) {
            $first = $productItems->first();
            
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
                'name' => $first->product_name,
                'code' => $first->product->product_code ?? '',
                'image' => $first->product->image ?? null,
                'orders' => $orders
            ];
        })->values();

        return response()->json($grouped);
    }

    public function create()
    {
        return view('admin.credit_notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
            'admin_notes' => 'nullable|string',
            'status' => 'required|in:draft,approved,refunded',
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.reason' => 'nullable|string',
        ]);
        
        $user = \App\Models\User::findOrFail($validated['user_id']);
        
        $selectedItems = collect($validated['items'])->map(function($item) {
            return [
                'id' => $item['order_item_id'],
                'quantity' => $item['quantity'],
                'reason' => $item['reason'] ?? null,
                'selected' => true
            ];
        });

        try {
            $creditNote = $this->creditNoteService->createDraft(
                $user,
                $selectedItems->toArray(),
                $validated['reason'],
                $validated['admin_notes']
            );

            if ($validated['status'] !== 'draft') {
                $creditNote->update(['status' => $validated['status']]);
            }

            return redirect()->route('admin.credit-notes.show', $creditNote)->with('success', 'Credit note created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create credit note: ' . $e->getMessage());
        }
    }

    public function show(CreditNote $creditNote)
    {
        $creditNote->load(['items.product', 'items.orderItem.order', 'user']);
        return view('admin.credit_notes.show', compact('creditNote'));
    }

    public function edit(CreditNote $creditNote)
    {
        $creditNote->load(['items.product', 'items.orderItem.order:id,created_at', 'user']);
        return view('admin.credit_notes.edit', compact('creditNote'));
    }

    public function update(Request $request, CreditNote $creditNote)
    {
        if ($request->has('items')) {
            $validated = $request->validate([
                'reason' => 'required|string|max:255',
                'admin_notes' => 'nullable|string',
                'status' => 'required|in:draft,pending,approved,rejected,refunded',
                'items' => 'required|array|min:1',
                'items.*.order_item_id' => 'required|exists:order_items,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.reason' => 'nullable|string',
            ]);

            $selectedItems = collect($validated['items'])->map(fn($item) => [
                'id' => $item['order_item_id'],
                'quantity' => $item['quantity'],
                'reason' => $item['reason'] ?? null,
                'selected' => true
            ]);

            try {
                $this->creditNoteService->updateDraft(
                    $creditNote,
                    $selectedItems->toArray(),
                    $validated['reason'],
                    $validated['admin_notes']
                );
                
                if ($creditNote->status !== $validated['status']) {
                    $creditNote->update(['status' => $validated['status']]);
                }

                return redirect()->route('admin.credit-notes.show', $creditNote)->with('success', 'Credit note updated successfully.');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Failed to update credit note: ' . $e->getMessage());
            }
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,pending,approved,rejected,refunded',
            'admin_note' => 'nullable|string'
        ]);

        $creditNote->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_note'] ?? $creditNote->admin_notes
        ]);

        return back()->with('success', 'Credit note status updated successfully.');
    }

    public function destroy(CreditNote $creditNote)
    {
        try {
            $this->creditNoteService->deleteDraft($creditNote);
            return redirect()->route('admin.credit-notes.index')->with('success', 'Credit note deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete credit note: ' . $e->getMessage());
        }
    }
}
