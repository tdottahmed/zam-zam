<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CreditNote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CreditNoteController extends Controller
{
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
        ]);

        // Filter only selected items
        $selectedItems = collect($validated['items'])->filter(fn($item) => $item['selected']);

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['items' => 'Please select at least one item to return.']);
        }

        DB::beginTransaction();

        try {
            $creditNote = $order->creditNotes()->create([
                'user_id' => auth()->id(),
                'reason' => $validated['reason'],
                'description' => $validated['description'],
                'status' => 'pending',
                'total_refund_amount' => 0, // Calculated below
            ]);

            $totalRefund = 0;

            foreach ($selectedItems as $itemData) {
                $orderItem = $order->items()->find($itemData['id']);
                
                if (!$orderItem) continue;

                if ($itemData['quantity'] > $orderItem->quantity) {
                    throw new \Exception("Return quantity cannot exceed purchased quantity for {$orderItem->product_name}");
                }

                $itemTotal = $orderItem->unit_price * $itemData['quantity'];
                $totalRefund += $itemTotal;

                $creditNote->items()->create([
                    'product_id' => $orderItem->product_id,
                    'order_item_id' => $orderItem->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $orderItem->unit_price,
                    'total_price' => $itemTotal,
                ]);
            }

            $creditNote->update(['total_refund_amount' => $totalRefund]);

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Credit note request submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to submit request: ' . $e->getMessage()]);
        }
    }
}
