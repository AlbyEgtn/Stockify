<?php

namespace App\Services\admin;

use App\Repositories\admin\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Store new product
     */
    public function storeProduct(Request $request)
    {
        return DB::transaction(function () use ($request) {

        $request->merge([
            'purchase_price' => str_replace('.', '', $request->purchase_price),
            'selling_price'  => str_replace('.', '', $request->selling_price),
        ]);

            $validated = $request->validate([
                'name'           => 'required|string|max:255',
                'sku'            => 'required|unique:products',
                'category_id'    => 'required|exists:categories,id',
                'supplier_id'    => 'required|exists:suppliers,id',
                'purchase_price' => 'required|numeric',
                'stock'          => 'required|integer',
                'selling_price'  => 'required|numeric',
                'minimum_stock'  => 'required|integer',
                'description'    => 'nullable|string',
                'image'          => 'nullable|image|max:2048',
            ]);

            // =========================
            // HANDLE IMAGE
            // =========================
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')
                    ->store('products', 'public');
            }

            // =========================
            // SIMPAN PRODUCT
            // =========================
            $product = $this->productRepository->createProduct($validated);

            // =========================
            // AMBIL ATTRIBUTES LANGSUNG DARI REQUEST
            // =========================
            $attributes = $request->input('attributes', []);

            if (is_array($attributes) && count($attributes) > 0) {

                foreach ($attributes as $attr) {

                    if (
                        isset($attr['name'], $attr['value']) &&
                        trim($attr['name']) !== '' &&
                        trim($attr['value']) !== ''
                    ) {
                        $product->productAttributes()->create([
                            'name'  => $attr['name'],
                            'value' => $attr['value'],
                        ]);
                    }
                }
            }

            return $product;
        });
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, Product $product)
    {
        return DB::transaction(function () use ($request, $product) {
            $request->merge([
                'purchase_price' => str_replace('.', '', $request->purchase_price),
                'selling_price'  => str_replace('.', '', $request->selling_price),
            ]);
            
            $validated = $request->validate([
                'name'           => 'required|string|max:255',
                'sku'            => 'required|unique:products,sku,' . $product->id,
                'category_id'    => 'required|exists:categories,id',
                'supplier_id'    => 'required|exists:suppliers,id',
                'purchase_price' => 'required|numeric',
                'stock'          => 'required|integer',
                'selling_price'  => 'required|numeric',
                'minimum_stock'  => 'required|integer',
                'description'    => 'nullable|string',
                'image'          => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')
                    ->store('products', 'public');
            }

            $product = $this->productRepository->updateProduct($product, $validated);

            // =========================
            // REPLACE ATTRIBUTES
            // =========================
            $product->productAttributes()->delete();

            $attributes = $request->input('attributes', []);

            if (is_array($attributes) && count($attributes) > 0) {

                foreach ($attributes as $attr) {

                    if (
                        isset($attr['name'], $attr['value']) &&
                        trim($attr['name']) !== '' &&
                        trim($attr['value']) !== ''
                    ) {
                        $product->productAttributes()->create([
                            'name'  => $attr['name'],
                            'value' => $attr['value'],
                        ]);
                    }
                }
            }
            $product->load('productAttributes');
            return $product;
        });
    }

    /**
     * Delete product
     */
    public function deleteProduct(Product $product)
    {
        return $this->productRepository->deleteProduct($product);
    }

    /**
     * Get all products
     */
    public function getAllProducts()
    {
        return $this->productRepository->getAllProducts();
    }
}
