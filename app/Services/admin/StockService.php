<?php

namespace App\Services\admin;

use App\Repositories\admin\StockRepository;
use Illuminate\Support\Facades\DB;

class StockService
{
    protected $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function getIncomingPageData(): array
    {
        return [
            'products'  => $this->stockRepository->getAllProducts(),
            'suppliers' => $this->stockRepository->getAllSuppliers(),
        ];
    }

    public function storeIncoming(array $data)
    {
        DB::transaction(function () use ($data) {

            $product = $this->stockRepository->findProduct($data['product_id']);

            $stockBefore = $product->stock;
            $stockAfter  = $stockBefore + $data['quantity'];

            // Update stok
            $this->stockRepository->increaseProductStock(
                $data['product_id'],
                $data['quantity']
            );

            // Simpan histori
            $this->stockRepository->createStockTransaction([
                'product_id'   => $data['product_id'],
                'supplier_id'  => $data['supplier_id'],
                'user_id'      => auth()->id(),
                'type'         => 'in',
                'quantity'     => $data['quantity'],
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
                'note'         => $data['note'] ?? null,
            ]);
        });
    }
}
