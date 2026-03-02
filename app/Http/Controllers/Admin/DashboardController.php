<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        // SUMMARY
        $totalProducts   = Product::count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalCategories = Category::count();
        $totalSuppliers  = Supplier::count();

        // RECENT PRODUCTS
        $recentProducts = Product::with('productAttributes')
            ->latest()
            ->take(5)
            ->get();

        // 🔥 DATA UNTUK GRAFIK STOK
        $chartProducts = Product::select('name','stock','minimum_stock')
            ->orderBy('stock','desc')
            ->take(8)
            ->get();

        return view('dashboard.admin', compact(
            'totalProducts',
            'lowStockProducts',
            'totalCategories',
            'totalSuppliers',
            'recentProducts',
            'chartProducts'
        ));
    }
}
