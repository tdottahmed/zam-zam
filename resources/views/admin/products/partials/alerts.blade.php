@if (session('success'))
  <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="alert">
    {{ session('success') }}
  </div>
@endif
@if (session('warning'))
  <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
    {{ session('warning') }}
  </div>
@endif
@if (session('error'))
  <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
    {{ session('error') }}
  </div>
@endif
@if (session('import_failures') && count(session('import_failures')) > 0)
  <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3" role="alert">
    <p class="font-medium text-amber-800">Import issues (row → errors):</p>
    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-amber-700">
      @foreach (session('import_failures') as $failure)
        <li><strong>Row {{ $failure->row() }}</strong>: {{ implode(' ', $failure->errors()) }}</li>
      @endforeach
    </ul>
  </div>
@endif
