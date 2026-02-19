<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Share Global Settings with All Views
        $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');
        
        $globalSettings = [
            'site_name' => $settings['site_name'] ?? config('app.name'),
            'site_logo' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('images/Zam_logo-120x99.png'),
            'site_favicon' => isset($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : asset('favicon.ico'),
        ];
        
        \Illuminate\Support\Facades\View::share('globalSettings', $globalSettings);

        // View Composer for Admin Notifications
        \Illuminate\Support\Facades\View::composer('components.admin.notifications', function ($view) {
            $user = auth()->user();
            if ($user && ($user->user_type === 'admin' || $user->is_admin)) { // adjust check based on your User model
                $view->with('notifications', $user->notifications()->latest()->take(10)->get());
                $view->with('unreadCount', $user->unreadNotifications()->count());
            } else {
                $view->with('notifications', collect([]));
                $view->with('unreadCount', 0);
            }
        });
    }
}
