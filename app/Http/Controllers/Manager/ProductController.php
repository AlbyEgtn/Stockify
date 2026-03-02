<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Manager\ProductService;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductAttribute;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::with(['category', 'supplier', 'productAttributes'])
                            ->latest()
                            ->paginate(10);

        return view('manager.products.index', compact('products'));
    }


    public function create()
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('manager.products.create', compact('categories', 'suppliers'));
    }


    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'stock' => $request->stock,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'minimum_stock' => $request->minimum_stock,
            'description' => $request->description,
            'attributes' => $request->attributes, // ← SIMPAN JSON
        ]);

        return redirect()->route('manager.products.index');
    }


    public function update(Request $request, Product $product)
    {
        $this->productService->update($product, $request->all());

        return redirect()
            ->route('manager.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return redirect()
            ->route('manager.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
    public function show(Product $product)
    {
        return redirect()->route('manager.products.index');
    }

    public function edit(Product $product)
    {
        return view('manager.products.edit', compact('product'));
    }

}

