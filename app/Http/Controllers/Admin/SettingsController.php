<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Display general settings.
     */
    public function general()
    {
        $settings = SystemSetting::get()->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        });

        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle checkbox for auto_send_invoice
        if (!isset($data['auto_send_invoice'])) {
            $data['auto_send_invoice'] = '0';
        }

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general', 'label' => ucwords(str_replace('_', ' ', $key))]
            );
        }

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Display SMTP settings.
     */
    public function smtp()
    {
        $settings = SystemSetting::get()->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        });

        return view('admin.settings.smtp', compact('settings'));
    }

    /**
     * Update SMTP settings.
     */
    public function updateSmtp(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'smtp', 'label' => ucwords(str_replace('_', ' ', $key))]
            );
        }

        return redirect()->back()->with('success', 'SMTP settings updated successfully.');
    }

    /**
     * Display SEO settings.
     */
    public function seo()
    {
        $settings = SystemSetting::get()->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        });

        return view('admin.settings.seo', compact('settings'));
    }

    /**
     * Update SEO settings.
     */
    public function updateSeo(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'seo', 'label' => ucwords(str_replace('_', ' ', $key))]
            );
        }

        return redirect()->back()->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Display Third Party settings.
     */
    public function thirdParty()
    {
        $settings = SystemSetting::get()->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        });

        return view('admin.settings.third-party', compact('settings'));
    }

    /**
     * Update Third Party settings.
     */
    public function updateThirdParty(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'third_party', 'label' => ucwords(str_replace('_', ' ', $key))]
            );
        }

        return redirect()->back()->with('success', 'Third party settings updated successfully.');
    }
}
