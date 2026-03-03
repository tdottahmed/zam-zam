<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

        // Handle File Uploads
        $files = ['site_logo', 'site_favicon'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $request->file($file)->store('settings', 'public');
            }
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
        $settings = [
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_password' => config('mail.mailers.smtp.password'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];

        return view('admin.settings.smtp', compact('settings'));
    }

    /**
     * Test SMTP connection.
     */
    public function testSmtpConnection(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            \Mail::raw('This is a test email to verify your SMTP settings.', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test SMTP Connection');
            });

            return redirect()->back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send test email. Error: ' . $e->getMessage());
        }
    }

    public function updateSmtp(Request $request)
    {
        $data = $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        $values = [
            'MAIL_MAILER' => $data['mail_driver'],
            'MAIL_HOST' => $data['mail_host'],
            'MAIL_PORT' => $data['mail_port'],
            'MAIL_USERNAME' => $data['mail_username'],
            'MAIL_PASSWORD' => $data['mail_password'],
            'MAIL_ENCRYPTION' => $data['mail_encryption'],
            'MAIL_FROM_ADDRESS' => $data['mail_from_address'],
            'MAIL_FROM_NAME' => '"' . $data['mail_from_name'] . '"',
        ];

        if ($this->setEnvironmentValue($values)) {
            Artisan::call('optimize:clear');
            return redirect()->back()->with('success', 'SMTP settings updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update .env file.');
        }
    }

    private function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {

                $str .= "\n"; // In case the file doesn't end with a newline
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key exists, replace it
                if (is_bool($keyPosition) && $keyPosition === false) {
                    // Variable doesn't exist, add it
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) {
             return false;
        }
        return true;
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
        $allowed = ['meta_title', 'meta_description', 'meta_keywords', 'google_verification_code'];
        $data = $request->only($allowed);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '', 'group' => 'seo', 'label' => ucwords(str_replace('_', ' ', $key))]
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

    /**
     * Display system health: overview, failed queue jobs, scheduler setup.
     */
    public function health()
    {
        $failedJobs = DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->limit(50)
            ->get();

        $pendingJobsCount = DB::table('jobs')->count();

        $projectPath = base_path();
        $phpBinary = PHP_BINARY ?: 'php';
        $schedulerCommand = "cd " . escapeshellarg($projectPath) . " && {$phpBinary} artisan schedule:run >> /dev/null 2>&1";
        $fullCronLine = "* * * * * " . $schedulerCommand;

        $storageWritable = is_writable(storage_path());

        $health = [
            'php_version' => PHP_VERSION,
            'laravel_version' => \Illuminate\Foundation\Application::VERSION,
            'env' => config('app.env'),
            'queue_connection' => config('queue.default'),
            'cache_driver' => config('cache.default'),
            'storage_writable' => $storageWritable,
            'pending_jobs_count' => $pendingJobsCount,
        ];

        return view('admin.settings.health', compact('health', 'failedJobs', 'fullCronLine', 'schedulerCommand', 'projectPath'));
    }

    /**
     * Retry a single failed job by UUID.
     */
    public function retryFailedJob(Request $request, string $uuid)
    {
        Artisan::call('queue:retry', ['id' => $uuid]);
        return redirect()->route('admin.settings.health')->with('success', 'Job queued for retry.');
    }

    /**
     * Retry all failed jobs.
     */
    public function retryAllFailedJobs(Request $request)
    {
        Artisan::call('queue:retry', ['id' => 'all']);
        return redirect()->route('admin.settings.health')->with('success', 'All failed jobs have been queued for retry.');
    }

    /**
     * Forget (delete) a single failed job.
     */
    public function forgetFailedJob(Request $request, string $uuid)
    {
        Artisan::call('queue:forget', ['id' => $uuid]);
        return redirect()->route('admin.settings.health')->with('success', 'Failed job removed.');
    }

    /**
     * Flush all failed jobs.
     */
    public function flushFailedJobs(Request $request)
    {
        Artisan::call('queue:flush');
        return redirect()->route('admin.settings.health')->with('success', 'All failed jobs have been removed.');
    }
}
