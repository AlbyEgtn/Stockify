<?php

use App\Models\Setting;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Cache;


if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $value = Setting::where('key', $key)->value('value');

            return $value !== null ? $value : $default;
        });
    }
}


if (! function_exists('logActivity')) {
    function logActivity(string $type, string $description): void
    {
        if (auth()->check()) {
            UserActivity::create([
                'user_id'       => auth()->id(),
                'activity_type' => $type,
                'description'   => $description,
            ]);
        }
    }
}
