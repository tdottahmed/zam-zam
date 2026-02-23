<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of newsletter subscribers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $subscribers = NewsletterSubscriber::query()
            ->when($search, function ($query, $search) {
                $query->where('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.newsletter_subscribers.index', compact('subscribers'));
    }

    /**
     * Remove the specified subscriber.
     */
    public function destroy(NewsletterSubscriber $newsletterSubscriber)
    {
        $newsletterSubscriber->delete();

        return redirect()->route('admin.newsletter-subscribers.index')
            ->with('success', 'Subscriber removed.');
    }
}
