<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Services\admin\ProductService;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    protected $productService;
    
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $products = Product::active()->latest()->get();
        $query = Product::with(['category','supplier','productAttributes']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhereHas('category', function ($cat) use ($search) {
                    $cat->where('name', 'like', "%{$search}%");
                });
            });

            logActivity('search_product', 'Mencari produk: '.$search);
        }

        $products = $query->latest()->paginate(5);
        $products->appends($request->query());

        $totalProducts    = Product::count();
        $lowStockProducts = Product::whereColumn('stock','<=','minimum_stock')->count();
        $totalCategories  = Category::count();
        $totalSuppliers   = Supplier::count();

        return view('admin.products.index', compact(
            'products',
            'search',
            'totalProducts',
            'lowStockProducts',
            'totalCategories',
            'totalSuppliers'
        ));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
            'suppliers'  => Supplier::all(),
        ]);
    }

    public function minimumStock(Request $request)
    {
        $query = Product::query();

        // 🔎 SEARCH
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            }

            if ($request->status === 'safe') {
                $query->whereColumn('stock', '>', 'minimum_stock');
            }
            
        }

        $products = $query->paginate(10);
        $products->appends($request->query());
        

        return view('admin.products.minimum-stock', compact('products'));
    }

    public function updateMinimumStock(Request $request, Product $product)
    {
        $request->validate([
            'minimum_stock' => 'required|integer|min:0'
        ]);

        $old = $product->minimum_stock;

        $product->update([
            'minimum_stock' => $request->minimum_stock
        ]);

        logActivity(
            'update_minimum_stock',
            'Mengubah minimum stock produk "' . $product->name .
            '" dari ' . $old . ' menjadi ' . $request->minimum_stock
        );

        return back()->with('success','Minimum stock berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $product = $this->productService->storeProduct($request);

        logActivity(
            'create_product',
            'Menambahkan produk: ' . $request->name
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $product->load('productAttributes');

        return view('admin.products.edit', [
            'product'    => $product,
            'categories' => Category::all(),
            'suppliers'  => Supplier::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $name = $product->name;

        $this->productService->updateProduct($request, $product);

            logActivity(
        'update_product',
        'Memperbarui produk: ' . $name
    );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function stockMutationReport(Request $request)
    {
        $categories = Category::all();

        $query = StockTransaction::with(['product.category','product.supplier'])
                    ->latest();

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->category_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $transactions = $query->paginate(10);
        $transactions->appends($request->query());

        $totalIn = (clone $query)->where('type','IN')->sum('quantity');
        $totalOut = (clone $query)->where('type','OUT')->sum('quantity');

        $allProducts = Product::with('category')->get();

        $criticalProducts = $allProducts->filter(fn($p) =>
            $p->stock <= $p->minimum_stock
        );

        $warningProducts = $allProducts->filter(fn($p) =>
            $p->stock > $p->minimum_stock &&
            $p->stock <= $p->minimum_stock * 2
        );

        $safeProducts = $allProducts->filter(fn($p) =>
            $p->stock > $p->minimum_stock * 2
        );

        $totalStockValue = $allProducts->sum(fn($p) =>
            $p->stock * $p->purchase_price
        );

        return view('admin.reports.mutation', compact(
            'transactions',
            'categories',
            'totalIn',
            'totalOut',
            'criticalProducts',
            'warningProducts',
            'safeProducts',
            'totalStockValue'
        ));
    }

    public function stockHistory(Request $request)
    {
        $query = StockTransaction::with(['product','user'])
                    ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->product) {
            $query->where('product_id', $request->product);
        }

        $transactions = $query->paginate(10);
        $transactions->appends($request->query());

        $products = Product::all();

        return view('admin.stocks.index', compact('transactions','products'));
    }


    public function destroy(Product $product)
    {
        $name = $product->name;

        $this->productService->deleteProduct($product);

        logActivity(
            'delete_product',
            'Menghapus produk: ' . $name
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
            
        logActivity(
        'delete_product',
        'Menghapus produk: ' . $name
    );
    }

    public function exportCsv()
    {
        $fileName = 'data-produk-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () {

            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Nama Produk',
                'Kategori',
                'Supplier',
                'Harga',
                'Stok'
            ]);

            Product::with('category','supplier')
                ->chunk(500, function ($products) use ($handle) {
                    foreach ($products as $product) {
                        fputcsv($handle, [
                            $product->name,
                            $product->category->name ?? '-',
                            $product->supplier->name ?? '-',
                            $product->price,
                            $product->stock,
                        ]);
                    }
                });

            fclose($handle);

        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
    public function toggleStatus(Product $product)
    {
        $product->status = $product->status === 'active' ? 'draft' : 'active';
        $product->save();

        return response()->json([
            'status' => $product->status
        ]);
    }

    public function ajaxSearchProducts(Request $request)
    {
        $search = $request->q;

        $products = Product::query()
            ->when($search, function($query) use ($search){
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get(['id','name','sku','stock']);

        return response()->json(
            $products->map(function($p){
                return [
                    'value' => $p->id,
                    'text'  => $p->name,
                    'sku'   => $p->sku,
                    'stock' => $p->stock
                ];
            })
        );
    }
    public function showImport()
    {
        return view('admin.products.import');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {

            Excel::import(new ProductsImport, $request->file('file'));

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Import produk berhasil dilakukan.');

        } catch (\Exception $e) {

            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());

        }
    }
    public function export()
    {
        return Excel::download(
            new ProductsExport,
            'produk-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
