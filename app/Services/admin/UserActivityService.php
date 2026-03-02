<?php

namespace App\Services\Admin;

use App\Models\UserActivity;

class UserActivityService
{
    public function getRecentActivities($perPage = 10, $search = null)
    {
        return UserActivity::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('activity_type', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }
}
