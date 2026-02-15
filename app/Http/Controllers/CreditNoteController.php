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

    public function create(Order $order)
    {
        // Ensure order belongs to user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return Inertia::render('Orders/CreateCreditNote', [
            'order' => $order
        ]);
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

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

        try {
            $this->creditNoteService->createDraft(
                $order,
                $selectedItems->toArray(),
                $validated['reason'],
                $validated['description']
            );

            return redirect()->route('orders.show', $order)->with('success', 'Credit note request submitted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to submit request: ' . $e->getMessage()]);
        }
    }

    public function show(CreditNote $creditNote)
    {
        if ($creditNote->user_id !== auth()->id()) {
            abort(403);
        }

        $creditNote->load(['items.product', 'order:id,created_at']);

        return Inertia::render('Orders/CreditNoteDetails', [
            'creditNote' => $creditNote
        ]);
    }
}
