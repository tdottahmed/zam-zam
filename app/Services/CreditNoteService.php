<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CreditNote;
use App\Repositories\CreditNoteRepository;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class CreditNoteService
{
    protected $repository;

    public function __construct(CreditNoteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createDraft(User $user, array $itemsData, string $reason, ?string $description): mixed
    {
        return DB::transaction(function () use ($user, $itemsData, $reason, $description) {
            
            // Determine the order_id. If all items belong to the same order, set it. Otherwise, leave null.
            $orderItemIds = array_filter(array_column($itemsData, 'order_item_id'));
            $orderItems = count($orderItemIds) > 0 ? OrderItem::whereIn('id', $orderItemIds)->get() : collect();
            $uniqueOrderIds = $orderItems->pluck('order_id')->unique();
            $orderId = $uniqueOrderIds->count() === 1 ? $uniqueOrderIds->first() : null;

            // 1. Create Credit Note Header
            $creditNote = $this->repository->create([
                'order_id' => $orderId,
                'user_id' => $user->id,
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
                $orderItem = isset($itemData['order_item_id']) ? OrderItem::find($itemData['order_item_id']) : null;

                $quantity = $itemData['quantity'];
                
                // Validate Quantity (Basic check, robust check should consider previous credits)
                if ($orderItem && $quantity > $orderItem->quantity) {
                    throw new Exception("Credit quantity cannot exceed ordered quantity for {$orderItem->product_name}");
                }

                $unitPrice = $itemData['unit_price'];
                $lineTotal = $unitPrice * $quantity;
                // Assuming tax is included or calculated separately. For now, 0 tax.
                $taxRate = 0; 
                
                $orderedQuantity = $orderItem ? $orderItem->quantity : null;

                $this->repository->createItem($creditNote, [
                    'order_item_id' => $orderItem ? $orderItem->id : null,
                    'product_id' => $itemData['product_id'],
                    'ordered_quantity' => $orderedQuantity,
                    'delivered_quantity' => $orderedQuantity ?? 0, // Assuming all delivered for now
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

    public function updateDraft(CreditNote $creditNote, array $itemsData, string $reason, ?string $description): mixed
    {
        return DB::transaction(function () use ($creditNote, $itemsData, $reason, $description) {
            
            // Determine the order_id based on new items.
            $orderItemIds = array_filter(array_column($itemsData, 'order_item_id'));
            $orderItems = count($orderItemIds) > 0 ? OrderItem::whereIn('id', $orderItemIds)->get() : collect();
            $uniqueOrderIds = $orderItems->pluck('order_id')->unique();
            $orderId = $uniqueOrderIds->count() === 1 ? $uniqueOrderIds->first() : null;

            // 1. Update Header
            $this->repository->update($creditNote, [
                'order_id' => $orderId,
                'reason' => $reason,
                'admin_notes' => $description,
            ]);

            // 2. Remove old items
            $this->repository->deleteItems($creditNote);

            $subtotal = 0;
            $taxAmount = 0;

            // 3. Process New Items
            foreach ($itemsData as $itemData) {
                $orderItem = isset($itemData['order_item_id']) ? OrderItem::find($itemData['order_item_id']) : null;

                $quantity = $itemData['quantity'];
                
                if ($orderItem && $quantity > $orderItem->quantity) {
                    throw new Exception("Credit quantity cannot exceed ordered quantity for {$orderItem->product_name}");
                }

                $unitPrice = $itemData['unit_price'];
                $lineTotal = $unitPrice * $quantity;
                $taxRate = 0; 
                
                $orderedQuantity = $orderItem ? $orderItem->quantity : null;

                $this->repository->createItem($creditNote, [
                    'order_item_id' => $orderItem ? $orderItem->id : null,
                    'product_id' => $itemData['product_id'],
                    'ordered_quantity' => $orderedQuantity,
                    'delivered_quantity' => $orderedQuantity ?? 0,
                    'previously_credited_quantity' => 0,
                    'credit_quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'line_total' => $lineTotal,
                    'reason' => $itemData['reason'] ?? null,
                ]);

                $subtotal += $lineTotal;
            }

            // 4. Update Totals
            $grandTotal = $subtotal + $taxAmount; 
            
            $this->repository->update($creditNote, [
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
            ]);

            return $creditNote;
        });
    }

    public function deleteDraft(CreditNote $creditNote): bool
    {
        return DB::transaction(function () use ($creditNote) {
            $this->repository->deleteItems($creditNote);
            return $this->repository->delete($creditNote);
        });
    }
}
