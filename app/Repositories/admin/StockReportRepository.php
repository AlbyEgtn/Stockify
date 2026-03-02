<?php

namespace App\Repositories\admin;

use App\Models\Product;

class StockReportRepository
{
    /**
     * Ambil produk dengan stok <= minimum_stock
     */
    public function getLowStockProducts()
    {
        return Product::whereColumn('stock', '<=', 'minimum_stock')
            ->with(['category', 'supplier'])
            ->orderBy('stock', 'asc')
            ->get();
    }

    /**
     * Ambil semua produk lengkap dengan relasi
     */
    public function getAllProducts()
    {
        return Product::with(['category', 'supplier'])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Hitung total nilai persediaan (berdasarkan harga beli)
     */
    public function getTotalStockValue()
    {
        return Product::selectRaw('SUM(stock * purchase_price) as total_value')
            ->value('total_value') ?? 0;
    }
}
