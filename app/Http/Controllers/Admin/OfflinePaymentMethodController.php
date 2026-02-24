<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfflinePaymentMethod;
use Illuminate\Http\Request;

class OfflinePaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $methods = OfflinePaymentMethod::latest()->get();
        return view('admin.offline_payment_methods.index', compact('methods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.offline_payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_fields' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        OfflinePaymentMethod::create($validated);

        return redirect()->route('admin.offline-payment-methods.index')
            ->with('success', 'Offline payment method created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfflinePaymentMethod $offlinePaymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfflinePaymentMethod $offlinePaymentMethod)
    {
        return view('admin.offline_payment_methods.edit', compact('offlinePaymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfflinePaymentMethod $offlinePaymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_fields' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $offlinePaymentMethod->update($validated);

        return redirect()->route('admin.offline-payment-methods.index')
            ->with('success', 'Offline payment method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfflinePaymentMethod $offlinePaymentMethod)
    {
        $offlinePaymentMethod->delete();

        return redirect()->route('admin.offline-payment-methods.index')
            ->with('success', 'Offline payment method deleted successfully.');
    }
}
