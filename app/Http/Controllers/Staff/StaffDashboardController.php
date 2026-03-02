<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\Staff\StaffDashboardService;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Http\Controllers\Staff\StaffStockController;

class StaffDashboardController extends Controller
{
    protected $service;

    public function __construct(StaffDashboardService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    public function index()
    {
        $totalProducts = Product::count();

        $lowStock = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        $incomingToday = StockTransaction::where('type', 'IN')
            ->whereDate('created_at', today())
            ->count();

        $outgoingToday = StockTransaction::where('type', 'OUT')
            ->whereDate('created_at', today())
            ->count();

        $incomingTasks = StockTransaction::with(['product','supplier'])
            ->get();$incomingTasks = StockTransaction::with(['product','supplier'])
            ->where('type', 'IN')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $outgoingTasks = StockTransaction::with('product')
            ->where('type', 'OUT')
            ->whereIn('status', ['approved','pending'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.staff', compact(
            'totalProducts',
            'lowStock',
            'incomingToday',
            'outgoingToday',
            'incomingTasks',
            'outgoingTasks'
        ));
    }
}
