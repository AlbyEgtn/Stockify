<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $query = Product::with('category');

        // FILTER KATEGORI
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // =========================
        // SUMMARY
        // =========================

        $totalProducts = Product::count();

        $totalStockValue = Product::sum(
            DB::raw('stock * purchase_price')
        );

        $criticalProducts = Product::whereColumn(
            'stock','<=','minimum_stock'
        )->get();

        $warningProducts = Product::whereColumn(
            'stock','>','minimum_stock'
        )->whereRaw(
            'stock <= minimum_stock * 2'
        )->get();

        $safeProducts = Product::whereRaw(
            'stock > minimum_stock * 2'
        )->get();

        $categories = Category::all();

        return view('admin.reports.stock', compact(
            'products',
            'categories',
            'totalProducts',
            'totalStockValue',
            'criticalProducts',
            'warningProducts',
            'safeProducts'
        ));
    }



    /**
     * =========================
     * LAPORAN MUTASI STOK
     * =========================
     */
    public function transactions(Request $request)
    {

        $query = StockTransaction::with([
            'product.category',
            'user'
        ])->orderBy('created_at','desc');

        // =========================
        // FILTER TANGGAL
        // =========================

        if ($request->filled('start_date') && $request->filled('end_date')) {

            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at',[$start,$end]);
        }

        // =========================
        // FILTER KATEGORI
        // =========================

        if ($request->filled('category_id')) {

            $query->whereHas('product', function($q) use ($request){
                $q->where('category_id',$request->category_id);
            });

        }

        // =========================
        // FILTER TIPE
        // =========================

        if ($request->filled('type')) {
            $query->where('type',$request->type);
        }

        // =========================
        // SUMMARY
        // =========================

        $totalIn = (clone $query)
            ->where('type','IN')
            ->sum('quantity');

        $totalOut = (clone $query)
            ->where('type','OUT')
            ->sum('quantity');

        // =========================
        // PAGINATION
        // =========================

        $transactions = $query
            ->paginate(10)
            ->withQueryString();

        $categories = Category::all();

        return view('admin.reports.transactions', compact(
            'transactions',
            'totalIn',
            'totalOut',
            'categories'
        ));
    }

    public function mutation(Request $request)
    {
        $query = Product::with('category');

        // ======================
        // FILTER KATEGORI
        // ======================
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // ======================
        // DATA PRODUK
        // ======================
        $products = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // ======================
        // SUMMARY DATA
        // ======================
        $criticalProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->get();

        $warningProducts = Product::whereColumn('stock', '>', 'minimum_stock')
            ->whereRaw('stock <= minimum_stock * 2')
            ->get();

        $safeProducts = Product::whereRaw('stock > minimum_stock * 2')->get();

        $totalStockValue = Product::sum(\DB::raw('stock * purchase_price'));

        $categories = Category::orderBy('name')->get();

        return view('admin.reports.mutation', [
            'products' => $products,
            'categories' => $categories,
            'criticalProducts' => $criticalProducts,
            'warningProducts' => $warningProducts,
            'safeProducts' => $safeProducts,
            'totalStockValue' => $totalStockValue
        ]);
    }

}