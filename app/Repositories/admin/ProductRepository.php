<?php

namespace App\Repositories\admin;

use App\Models\Product;

class ProductRepository
{
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data)
    {
        $product->update($data);

        return $product->refresh();
    }

    public function deleteProduct(Product $product)
    {
        return $product->delete();
    }

    public function getAllProducts()
    {
        return Product::with(['category', 'supplier', 'productAttributes'])->latest()->get();
    }
}
