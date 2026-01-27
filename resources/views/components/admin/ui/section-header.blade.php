<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row md:items-center justify-between gap-4']) }}>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $slot }}</h1>
        @if(isset($description))
            <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
        @endif
    </div>
    
    @if(isset($actions))
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
