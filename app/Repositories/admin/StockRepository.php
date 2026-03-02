<?php

namespace App\Repositories\admin;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockTransaction;

class StockRepository
{
    public function getAllProducts()
    {
        return Product::orderBy('name')->get();
    }

    public function getAllSuppliers()
    {
        return Supplier::orderBy('name')->get();
    }

    public function findProduct($id)
    {
        return Product::findOrFail($id);
    }

    public function increaseProductStock($productId, $quantity)
    {
        $product = Product::findOrFail($productId);
        $product->increment('stock', $quantity);
    }

    public function createStockTransaction(array $data)
    {
        return StockTransaction::create($data);
    }
}
