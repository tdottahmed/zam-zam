<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $mailTemplates = MailTemplate::query()
            ->when($search, function ($query, $search) {
                $query->where('type', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.mail_templates.index', compact('mailTemplates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mail_templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'      => 'required|string|max:255|unique:mail_templates,type',
            'subject'   => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        MailTemplate::create($validated);

        return redirect()->route('admin.mail-templates.index')
            ->with('success', 'Mail template created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not necessary for templates typically, but we'll return the edit form or skip
        return redirect()->route('admin.mail-templates.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MailTemplate $mailTemplate)
    {
        return view('admin.mail_templates.edit', compact('mailTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MailTemplate $mailTemplate)
    {
        $validated = $request->validate([
            'type'      => 'required|string|max:255|unique:mail_templates,type,' . $mailTemplate->id,
            'subject'   => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $mailTemplate->update($validated);

        return redirect()->route('admin.mail-templates.index')
            ->with('success', 'Mail template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailTemplate $mailTemplate)
    {
        $mailTemplate->delete();

        return redirect()->route('admin.mail-templates.index')
            ->with('success', 'Mail template deleted successfully.');
    }
}
