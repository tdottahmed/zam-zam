<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\CreditNoteRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class CreditNoteService
{
    protected $repository;

    public function __construct(CreditNoteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createDraft(Order $order, array $itemsData, string $reason, ?string $description): mixed
    {
        return DB::transaction(function () use ($order, $itemsData, $reason, $description) {
            
            // 1. Create Credit Note Header
            $creditNote = $this->repository->create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => 'draft',
                'credit_type' => 'adjust_against_invoice', // Default, user can change later if needed
                'reason' => $reason,
                'admin_notes' => $description, // Mapping description to admin_notes for now or add description field
                'created_by' => auth()->id(),
            ]);

            $subtotal = 0;
            $taxAmount = 0;

            // 2. Process Items
            foreach ($itemsData as $itemData) {
                $orderItem = OrderItem::find($itemData['id']);
                
                if (!$orderItem) continue;

                $quantity = $itemData['quantity'];
                
                // Validate Quantity (Basic check, robust check should consider previous credits)
                if ($quantity > $orderItem->quantity) {
                    throw new Exception("Credit quantity cannot exceed ordered quantity for {$orderItem->product_name}");
                }

                $unitPrice = $orderItem->unit_price;
                $lineTotal = $unitPrice * $quantity;
                // Assuming tax is included or calculated separately. For now, 0 tax.
                $taxRate = 0; 

                $this->repository->createItem($creditNote, [
                    'order_item_id' => $orderItem->id,
                    'product_id' => $orderItem->product_id,
                    'ordered_quantity' => $orderItem->quantity,
                    'delivered_quantity' => $orderItem->quantity, // Assuming all delivered for now
                    'previously_credited_quantity' => 0, // Need to calculate this from other CNs
                    'credit_quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'line_total' => $lineTotal,
                    'reason' => $itemData['reason'] ?? null,
                ]);

                $subtotal += $lineTotal;
            }

            // 3. Update Totals
            $grandTotal = $subtotal + $taxAmount; // + shipping - discount
            
            $this->repository->update($creditNote, [
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
            ]);

            return $creditNote;
        });
    }
}
