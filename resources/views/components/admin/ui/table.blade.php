<div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
    @if(isset($search) && $search->isNotEmpty())
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
             {{ $search }}
        </div>
    @endif

    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        <thead class="bg-gray-50">
            <tr>
                {{ $head }}
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            {{ $body }}
        </tbody>
    </table>
</div>
