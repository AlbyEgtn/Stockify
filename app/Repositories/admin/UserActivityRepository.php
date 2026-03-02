<?php

namespace App\Repositories\admin;

use App\Models\UserActivity;

class UserActivityRepository
{
    public function getRecentActivities()
    {
        return UserActivity::latest()->with('user')->take(10)->get();
    }

    public function createActivity($userId, $activityType, $description)
    {
        return UserActivity::create([
            'user_id' => $userId,
            'activity_type' => $activityType,
            'description' => $description,
        ]);
    }
}
