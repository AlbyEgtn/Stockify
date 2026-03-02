<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\Manager\ManagerStokService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;

class ManagerStokController extends Controller
{
    protected ManagerStokService $stokService;

    public function __construct(ManagerStokService $stokService)
    {
        $this->stokService = $stokService;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE INCOMING (Pending Approval)
    |--------------------------------------------------------------------------
    */

    public function storeIncoming(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        $this->stokService->handleIncoming($request);

        return back()->with('success', 'Barang masuk berhasil dicatat dan menunggu approval.');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE OUTGOING (Pending Approval)
    |--------------------------------------------------------------------------
    */

    public function storeOutgoing(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $this->stokService->handleOutgoing($request);

        return back()->with('success', 'Barang keluar berhasil dicatat dan menunggu approval.');
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN BARANG MASUK
    |--------------------------------------------------------------------------
    */

    public function incoming()
    {
        $products = Product::with('latestIncoming')
            ->latest()
            ->get();

        $suppliers = \App\Models\Supplier::all();

        return view('manager.stockb.incoming', compact('products','suppliers'));
    }



    public function outgoing()
    {
        $products = Product::with('latestTransaction')
            ->latest()
            ->get();

        return view('manager.stockb.outgoing', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY TRANSAKSI
    |--------------------------------------------------------------------------
    */

    public function transactionHistory(Request $request)
    {
        $filters = $request->only([
            'status',
            'type',
            'product',
            'start_date',
            'end_date'
        ]);

        $transactions = $this->stokService->getTransactionHistory($filters);

        $products = Product::all();

        return view('manager.stockb.history', compact('transactions','products'));
    }


    public function opnameForm()
    {
        $products = Product::all();
        return view('manager.stockb.opname', compact('products'));
    }

    public function storeOpname(Request $request)
    {
        try {

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'real_stock' => 'required|integer|min:0',
                'note'       => 'nullable|string'
            ]);

            $this->stokService->handleOpname($request);

            return redirect()
                ->route('manager.stock.history')
                ->with('success', 'Stock opname berhasil disimpan.');

        } catch (\Exception $e) {

            return back()
                ->with('error', $e->getMessage());
        }
    }
}
