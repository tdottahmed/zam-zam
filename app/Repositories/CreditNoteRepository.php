<?php

namespace App\Repositories;

use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use Illuminate\Support\Collection;

class CreditNoteRepository
{
    public function create(array $data): CreditNote
    {
        return CreditNote::create($data);
    }

    public function createItem(CreditNote $creditNote, array $data): CreditNoteItem
    {
        return $creditNote->items()->create($data);
    }

    public function findByOrder(string $orderId): Collection
    {
        return CreditNote::where('order_id', $orderId)->get();
    }

    public function update(CreditNote $creditNote, array $data): bool
    {
        return $creditNote->update($data);
    }
}
