<?php

namespace App\Repositories\Manager;

use App\Models\Product;
use App\Models\StockTransaction;

class ManagerStokRepository
{


    public function findById(int $id): ?Product
    {
        return Product::lockForUpdate()->find($id);
    }

    public function increaseStock(Product $product, int $quantity): void
    {
        $product->increment('stock', $quantity);
    }

    public function decreaseStock(Product $product, int $quantity): void
    {
        $product->decrement('stock', $quantity);
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function createTransaction(
        int $productId,
        ?int $supplierId,
        string $type,
        int $quantity,
        int $stockBefore
    ): StockTransaction {

        return StockTransaction::create([
            'product_id'   => $productId,
            'supplier_id'  => $supplierId,
            'type'         => $type,
            'quantity'     => $quantity,
            'stock_before' => $stockBefore,
            'stock_after'  => $stockBefore, // belum berubah
            'status'       => 'pending',
            'user_id'      => auth()->id(),
        ]);
    }


    public function getAllTransactions()
    {
        return StockTransaction::with(['product','user'])
            ->latest()
            ->paginate(10);
    }
}
