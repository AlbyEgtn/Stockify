<?php

namespace App\Services\admin;

use App\Repositories\admin\StockReportRepository;

class StockReportService
{
    protected $stockReportRepository;

    public function __construct(StockReportRepository $stockReportRepository)
    {
        $this->stockReportRepository = $stockReportRepository;
    }

    public function getLowStockProducts()
    {
        return $this->stockReportRepository->getLowStockProducts();
    }

    public function getAllProducts()
    {
        return $this->stockReportRepository->getAllProducts();
    }

    public function getTotalStockValue()
    {
        return $this->stockReportRepository->getTotalStockValue();
    }
}
