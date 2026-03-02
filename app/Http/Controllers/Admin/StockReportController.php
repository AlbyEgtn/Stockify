<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockReportController extends Controller
{
    /**
     * =========================
     * LAPORAN STOK PRODUK
     * =========================
     */
    public function index(Request $request)
    {
        // Semua produk (tanpa filter) → untuk summary
        $allProducts = Product::with(['category','supplier'])->get();

        // Query untuk filter
        $query = Product::with(['category','supplier']);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->status) {
            if ($request->status === 'critical') {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            } elseif ($request->status === 'warning') {
                $query->whereColumn('stock', '>', 'minimum_stock')
                    ->whereRaw('stock <= minimum_stock * 2');
            } elseif ($request->status === 'safe') {
                $query->whereRaw('stock > minimum_stock * 2');
            }
        }

        // Produk hasil filter → untuk tabel
        $products = $query->get();

        $lowStockProducts = $allProducts->filter(function ($product) {
            return $product->stock <= $product->minimum_stock;
        });

        $totalStockValue = $allProducts->sum(function ($product) {
            return $product->stock * $product->purchase_price;
        });

        $categories = Category::all();

        return view('admin.reports.stock', compact(
            'allProducts',
            'products',
            'lowStockProducts',
            'totalStockValue',
            'categories'
        ));
    }

    

    /**
     * =========================
     * LAPORAN BARANG MASUK & KELUAR
     * =========================
     */
    public function transactions(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->orderBy('created_at', 'desc');

        // =========================
        // FILTER TANGGAL (FIXED)
        // =========================
        if ($request->filled('start_date') && $request->filled('end_date')) {

            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$start, $end]);
        }

        // =========================
        // FILTER PRODUK
        // =========================
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // =========================
        // CLONE QUERY UNTUK TOTAL
        // =========================
        $totalIn = (clone $query)
            ->where('type', 'IN')
            ->sum('quantity');

        $totalOut = (clone $query)
            ->where('type', 'OUT')
            ->sum('quantity');

        // =========================
        // PAGINATION
        // =========================
        $transactions = $query
            ->paginate(10)
            ->appends($request->query());

        $products = Product::all();

        return view('admin.reports.transactions', compact(
            'transactions',
            'totalIn',
            'totalOut',
            'products'
        ));
}
}
