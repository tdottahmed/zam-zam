<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class ProfitMarginController extends Controller
{
    /**
     * Display the profit margin settings.
     */
    public function index()
    {
        // Fetch the single profit margin setting, or create it if missing (safety check)
        $profitMargin = SystemSetting::firstOrCreate(
            ['group' => 'profit_margin', 'key' => 'default_profit_margin'],
            ['label' => 'Default Profit Margin', 'value' => 0.00, 'is_active' => true]
        );

        return view('admin.profit_margins.edit', compact('profitMargin'));
    }

    /**
     * Update the profit margin setting.
     */
    public function update(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric|min:0',
        ]);

        $profitMargin = SystemSetting::where('group', 'profit_margin')
                                     ->where('key', 'default_profit_margin')
                                     ->firstOrFail();

        $profitMargin->update([
            'value' => $request->value,
             // We can keep is_active logic if needed, or assume always active. 
             // Implementing minimal change: only updating value as requested "ONE SINGLE VALUE".
        ]);

        return redirect()->route('admin.profit-margin.index')
            ->with('success', 'Profit Margin updated successfully.');
    }
}
