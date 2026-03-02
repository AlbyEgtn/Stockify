<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\admin\StockService;
use Illuminate\Http\Request;
use App\Models\StockTransaction;

class StockController extends Controller
{
    public function index(Request $request)
{
    $query = StockTransaction::with(['product','user'])
                ->latest();

    // Filter status (optional)
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // Filter tipe (optional)
    if ($request->type) {
        $query->where('type', $request->type);
    }

    // Filter produk (optional)
    if ($request->product) {
        $query->where('product_id', $request->product);
    }

    $transactions = $query->paginate(10);
    $transactions->appends($request->query());

    return view('admin.stocks.index', compact('transactions'));
}
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function incoming()
    {
        $data = $this->stockService->getIncomingPageData();

        return view('admin.stocks.incoming', $data);
    }

    public function storeIncoming(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity'    => 'required|integer|min:1',
            'note'        => 'nullable|string'
        ]);

        $this->stockService->storeIncoming($request->all());

        return redirect()
            ->route('admin.stock.incoming')
            ->with('success', 'Stok berhasil ditambahkan.');
    }
}
