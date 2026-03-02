<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserActivityService;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    protected UserActivityService $userActivityService;

    public function __construct(UserActivityService $userActivityService)
    {
        $this->userActivityService = $userActivityService;
    }

    public function index(Request $request)
    {
        $activities = $this->userActivityService
            ->getRecentActivities(10, $request->search);

        return view('admin.reports.activities', compact('activities'));
    }
}
