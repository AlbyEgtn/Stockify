<?php

namespace App\Repositories\Staff;

use App\Models\Product;
use App\Models\StockTransaction;

class StaffDashboardRepository
{
    public function getTotalProducts()
    {
        return Product::count();
    }

    public function getLowStockProducts($limit = 5)
    {
        return Product::where('stock', '<=', $limit)->count();
    }


    public function getTodayIncoming()
    {
        return StockTransaction::where('type', 'in')
            ->whereDate('created_at', now())
            ->count();
    }

    public function getTodayOutgoing()
    {
        return StockTransaction::where('type', 'out')
            ->whereDate('created_at', now())
            ->count();
    }
        public function getPendingIncoming()
        {
            return StockTransaction::with('product')
                ->where('type', 'IN')
                ->whereDate('created_at', now())
                ->latest()
                ->get();
        }

        public function getPendingOutgoing()
        {
            return StockTransaction::with('product')
                ->where('type', 'OUT')
                ->whereDate('created_at', now())
                ->latest()
                ->get();
        }
}
