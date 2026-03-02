@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 dark:text-gray-200 text-3xl font-medium">System Health</h3>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-4 py-3 text-sm text-green-800 dark:text-green-200" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        <!-- Overview Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">System Overview</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#C41E3A]/10 flex items-center justify-center text-[#C41E3A]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">PHP</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $health['php_version'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#C41E3A]/10 flex items-center justify-center text-[#C41E3A]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Laravel</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $health['laravel_version'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#C41E3A]/10 flex items-center justify-center text-[#C41E3A]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Environment</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $health['env'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#C41E3A]/10 flex items-center justify-center text-[#C41E3A]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Queue</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $health['queue_connection'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#C41E3A]/10 flex items-center justify-center text-[#C41E3A]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Cache</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $health['cache_driver'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="flex-shrink-0 w-10 h-10 rounded-lg {{ $health['storage_writable'] ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Storage</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $health['storage_writable'] ? 'Writable' : 'Check permissions' }}</p>
                    </div>
                </div>
            </div>
            @if($health['pending_jobs_count'] > 0)
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium">{{ $health['pending_jobs_count'] }}</span> job(s) waiting in the queue. Ensure the <a href="#scheduler" class="text-[#C41E3A] hover:underline">scheduler cron</a> is set up.
                </p>
            @endif
        </div>

        <!-- Failed Queue Jobs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Failed Queue Jobs</h4>
                @if($failedJobs->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        <form action="{{ route('admin.settings.health.retry-all') }}" method="POST" class="inline" onsubmit="return confirm('Re-queue all {{ $failedJobs->count() }} failed job(s) for retry?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-[#C41E3A] text-white text-sm font-medium rounded-lg hover:bg-[#a01830] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Retry all
                            </button>
                        </form>
                        <form action="{{ route('admin.settings.health.flush') }}" method="POST" class="inline" onsubmit="return confirm('Permanently remove all failed jobs? This cannot be undone.');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Flush all
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            @if($failedJobs->isEmpty())
                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-medium">No failed jobs</p>
                    <p class="text-sm mt-1">Failed queue jobs will appear here for retry or removal.</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-2">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Queue / Connection</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Failed at</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Exception</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($failedJobs as $job)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                        <span class="font-mono">{{ $job->queue }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">/ {{ $job->connection }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($job->failed_at)->format('M j, Y g:i A') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ str()->limit($job->exception, 200) }}">
                                        {{ str()->limit($job->exception, 60) }}
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <form action="{{ route('admin.settings.health.retry', $job->uuid) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[#C41E3A] hover:underline text-sm font-medium">Retry</button>
                                        </form>
                                        <span class="text-gray-300 dark:text-gray-600 mx-1">|</span>
                                        <form action="{{ route('admin.settings.health.forget', $job->uuid) }}" method="POST" class="inline" onsubmit="return confirm('Remove this failed job? It will not be retried.');">
                                            @csrf
                                            <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 text-sm font-medium">Forget</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Showing up to 50 most recent failed jobs.</p>
            @endif
        </div>

        <!-- Scheduler / Cron -->
        <div id="scheduler" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 scroll-mt-4">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-1 pb-2 border-b border-gray-100 dark:border-gray-700">Queue & Scheduler (Cron)</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Run this single cron job every minute so queued jobs (e.g. invoice emails) are processed. The path below is your project root.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project path</label>
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $projectPath }}" class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-mono text-sm px-4 py-2.5">
                        <button type="button" onclick="copyToClipboard(this.previousElementSibling.value); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000)" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition whitespace-nowrap">
                            Copy
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full cron command (copy this into your hosting cron)</label>
                    <div class="flex gap-2 flex-wrap">
                        <input type="text" id="cron-command" readonly value="{{ $fullCronLine }}" class="flex-1 min-w-0 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-mono text-sm px-4 py-2.5">
                        <button type="button" onclick="const el = document.getElementById('cron-command'); copyToClipboard(el.value); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000)" class="px-4 py-2.5 bg-[#C41E3A] text-white text-sm font-medium rounded-lg hover:bg-[#a01830] transition whitespace-nowrap">
                            Copy command
                        </button>
                    </div>
                </div>
            </div>

            <details class="mt-6 group/details rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                <summary class="flex cursor-pointer list-none items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition select-none">
                    <svg class="h-4 w-4 text-[#C41E3A] transition group-open/details:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    How to set up the cron job
                </summary>
                <div class="border-t border-gray-200 dark:border-gray-600 px-4 py-4 text-sm text-gray-600 dark:text-gray-400 space-y-3 bg-gray-50/50 dark:bg-gray-700/20">
                    <ol class="list-decimal list-inside space-y-2">
                        <li>Log in to your hosting control panel (cPanel, Plesk, etc.) and open <strong>Cron Jobs</strong>.</li>
                        <li>Add a new cron job with schedule <strong>Every minute</strong> (or <code class="bg-gray-200 dark:bg-gray-600 px-1 rounded">* * * * *</code>).</li>
                        <li>In the <strong>Command</strong> field, paste the full cron command shown above (use the Copy button).</li>
                        <li>Save. The scheduler will run every minute and process queued jobs (e.g. <code class="bg-gray-200 dark:bg-gray-600 px-1 rounded">queue:work --stop-when-empty --max-time=55</code>).</li>
                    </ol>
                    <p class="text-xs text-gray-500 dark:text-gray-500">For more detail, see <code class="bg-gray-200 dark:bg-gray-600 px-1 rounded">docs/QUEUE_CRON_SETUP.md</code> in the project.</p>
                </div>
            </details>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text);
    } else {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed'; ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
    }
}
</script>
@endsection
