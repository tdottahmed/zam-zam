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

    public function index()
    {
        $creditNotes = CreditNote::with(['order', 'user'])
            ->latest()
            ->paginate(15);


        return view('admin.credit_notes.index', compact('creditNotes'));
    }

    public function show(CreditNote $creditNote)
    {
        $creditNote->load(['items.product', 'order', 'user']);
        return view('admin.credit_notes.show', compact('creditNote'));
    }

    public function update(Request $request, CreditNote $creditNote)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,refunded',
            'admin_note' => 'nullable|string'
        ]);

        // Basic status update for now. 
        // In a real scenario, approval might trigger refund logic via service.
        $creditNote->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? $creditNote->admin_note
        ]);

        return back()->with('success', 'Credit note status updated successfully.');
    }
}
