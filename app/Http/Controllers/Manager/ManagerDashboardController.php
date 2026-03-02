<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransaction;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        // =========================
        // STATISTIK UTAMA
        // =========================

        $totalProducts = Product::count();

        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->count();

        // =========================
        // PRODUK MASUK & KELUAR HARI INI
        // =========================

        $productsInToday = StockTransaction::where('type', 'IN')
            ->whereDate('created_at', now())
            ->sum('quantity');

        $productsOutToday = StockTransaction::where('type', 'OUT')
            ->whereDate('created_at', now())
            ->sum('quantity');

        // =========================
        // TRANSAKSI TERBARU
        // =========================

        $latestIncoming = StockTransaction::with('product')
            ->where('type', 'IN')
            ->latest()
            ->take(5)
            ->get();

        $latestOutgoing = StockTransaction::with('product')
            ->where('type', 'OUT')
            ->latest()
            ->take(5)
            ->get();

        // =========================
        // PRODUK STOK MINIMUM
        // =========================

        $lowStockList = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->get();

        return view('dashboard.manager', compact(
            'totalProducts',
            'lowStockProducts',
            'productsInToday',
            'productsOutToday',
            'latestIncoming',
            'latestOutgoing',
            'lowStockList'
        ));
    }
}   
