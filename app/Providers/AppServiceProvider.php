<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\UserActivity;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    function logActivity($type, $description)
    {
        UserActivity::create([
            'user_id'      => auth()->id(),
            'activity_type'=> $type,
            'description'  => $description,
        ]);
    }

    public function boot()
    {
        View::composer('*', function ($view) {

            $setting = Setting::first();

            $systemName = $setting?->system_name ?? 'Stockify';
            $systemLogo = $setting?->logo ?? null;

            $view->with([
            'global_system_name' => $setting?->system_name ?? 'Stockify',
            'global_system_logo' => $setting?->logo ?? null,
            ]);
        });
    }
}
