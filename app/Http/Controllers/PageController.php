<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Display the page matching the given slug.
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return Inertia::render('Page', [
            'page' => [
                'title'      => $page->title,
                'slug'       => $page->slug,
                'content'    => $page->content,
                'updated_at' => $page->updated_at?->format('F j, Y'),
            ],
        ]);
    }
}
