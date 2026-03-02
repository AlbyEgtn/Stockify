<?php

namespace App\Services\Manager;

use App\Repositories\Manager\ManagerStokRepository;
use Illuminate\Support\Facades\DB;
use App\Models\StockTransaction;
use Exception;
use App\Models\Product;



class ManagerStokService
{
    protected ManagerStokRepository $stokRepository;

    public function __construct(ManagerStokRepository $stokRepository)
    {
        $this->stokRepository = $stokRepository;
    }


    public function handleIncoming($request): void
    {
        DB::transaction(function () use ($request) {

            $product = $this->stokRepository->findById($request->product_id);

            if (!$product) {
                throw new Exception('Produk tidak ditemukan.');
            }

            $quantity = (int) $request->quantity;

            $stockBefore = $product->stock;

            $this->stokRepository->createTransaction(
                $product->id,
                (int) $request->supplier_id,
                'IN',
                $quantity,
                $stockBefore
            );
        });
    }
    public function handleOutgoing($request): void
    {
        DB::transaction(function () use ($request) {

            $product = $this->stokRepository->findById($request->product_id);

            if (!$product) {
                throw new Exception('Produk tidak ditemukan.');
            }

            $quantity = (int) $request->quantity;

            if ($product->stock < $quantity) {
                throw new Exception('Stok tidak mencukupi.');
            }

            $stockBefore = $product->stock;


            $this->stokRepository->createTransaction(
                $product->id,
                null,
                'OUT',
                $quantity,
                $stockBefore
            );
        });
    }


    public function handleOpname($request)
    {
        DB::transaction(function () use ($request) {

            $product = Product::lockForUpdate()
                ->findOrFail($request->product_id);

            $systemStock = $product->stock;
            $realStock   = $request->real_stock;
            $difference  = $realStock - $systemStock;

            if ($difference == 0) {
                throw new \Exception('Tidak ada perubahan stok.');
            }

            // Update stok produk
            $product->update([
                'stock' => $realStock,
                'opname_status' => 'none'
            ]);

            StockTransaction::create([
                'product_id'   => $product->id,
                'supplier_id'  => null,
                'user_id'      => auth()->id(),
                'type'         => 'OPNAME',
                'status'       => 'approved',
                'quantity'     => abs($difference),
                'difference'   => $difference,
                'stock_before' => $systemStock,
                'stock_after'  => $realStock,
                'note'         => '[OPNAME] ' . ($request->note ?? '-'),
            ]);

            logActivity(
                'stock_opname',
                'Opname: ' . $product->name .
                ' | Before: ' . $systemStock .
                ' | After: ' . $realStock .
                ' | Diff: ' . $difference
            );
        });
    }

    public function getTransactionHistory($filters = [])
    {
        $query = StockTransaction::with(['product','user'])
                    ->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['product'])) {
            $query->where('product_id', $filters['product']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'].' 00:00:00',
                $filters['end_date'].' 23:59:59'
            ]);
        }

        return $transactions = $query->paginate(10); $transactions->appends(request()->query());

    }
}
