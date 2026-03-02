<?php

namespace App\Repositories\Manager;

use App\Models\Product;

class ProductRepository
{
    public function paginateWithRelations($perPage = 10)
    {
        return Product::with(['category', 'supplier'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return Product::with(['category', 'supplier', 'productAttributes'])
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
