<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Store a new newsletter subscription (public, no auth).
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $validated['email'];

        if (NewsletterSubscriber::where('email', $email)->exists()) {
            return response()->json(['message' => 'You are already subscribed.'], 200);
        }

        NewsletterSubscriber::create(['email' => $email]);

        return response()->json(['message' => 'Thanks for subscribing!'], 201);
    }
}
