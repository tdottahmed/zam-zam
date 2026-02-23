@extends('layouts.admin')

@section('header')
    {{ $page->title }}
@endsection

@section('content')
    <div class="mx-auto max-w-8xl">
        {{-- Top actions --}}
        <div class="mb-6 flex items-center justify-between">
            <x-admin.actions.button href="{{ route('admin.pages.index') }}" variant="secondary" size="sm">
                &larr; Back to Pages
            </x-admin.actions.button>

            <div class="flex items-center gap-3">
                <x-admin.actions.button href="{{ route('admin.pages.edit', $page) }}" variant="primary" size="sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Page
                </x-admin.actions.button>

                <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete this page? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <x-admin.actions.button type="submit" variant="danger" size="sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </x-admin.actions.button>
                </form>
            </div>
        </div>

        {{-- Page preview card --}}
        <x-admin.ui.card>
            {{-- Meta bar --}}
            <div class="flex flex-wrap items-center gap-4 pb-5 mb-6 border-b border-gray-100">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span class="font-mono bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">/{{ $page->slug }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Created {{ $page->created_at->format('M d, Y') }}
                </div>
                @if($page->updated_at != $page->created_at)
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Updated {{ $page->updated_at->diffForHumans() }}
                    </div>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>

            {{-- Content --}}
            @if($page->content)
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed page-content">
                    {!! $page->content !!}
                </div>
            @else
                <div class="flex flex-col items-center gap-3 py-12 text-gray-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-sm">This page has no content yet.</p>
                    <x-admin.actions.button href="{{ route('admin.pages.edit', $page) }}" variant="primary" size="sm">
                        Add Content
                    </x-admin.actions.button>
                </div>
            @endif
        </x-admin.ui.card>

        {{-- Quill content styles --}}
        <style>
            .page-content h1 { font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem; color: #111827; }
            .page-content h2 { font-size: 1.4rem; font-weight: 600; margin-bottom: 0.75rem; color: #1f2937; }
            .page-content p { margin-bottom: 0.875rem; }
            .page-content ul, .page-content ol { margin-left: 1.5rem; margin-bottom: 0.875rem; }
            .page-content ul { list-style-type: disc; }
            .page-content ol { list-style-type: decimal; }
            .page-content a { color: #C41E3A; text-decoration: underline; }
            .page-content blockquote { border-left: 4px solid #e5e7eb; padding-left: 1rem; color: #6b7280; font-style: italic; margin-bottom: 0.875rem; }
            .page-content pre { background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; font-size: 0.8rem; overflow-x: auto; margin-bottom: 0.875rem; }
            .page-content strong { font-weight: 600; }
        </style>
    </div>
@endsection
