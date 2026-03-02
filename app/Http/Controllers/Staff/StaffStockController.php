<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StaffStockController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Barang Masuk (Pending)
    |--------------------------------------------------------------------------
    */
    public function incoming()
    {
        $transactions = StockTransaction::with(['product','supplier','user'])
            ->where('type', 'IN')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('staff.stock.incoming', compact('transactions'));
    }

    /*
    |--------------------------------------------------------------------------
    | Barang Keluar (Pending)
    |--------------------------------------------------------------------------
    */
    public function outgoing()
    {
        $transactions = StockTransaction::with(['product','user'])
            ->where('type', 'OUT')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('staff.stock.outgoing', compact('transactions'));
    }

    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Transaksi
    |--------------------------------------------------------------------------
    */
    public function confirm($id)
    {
        try {

            DB::transaction(function () use ($id) {

                $transaction = StockTransaction::with('product')
                    ->lockForUpdate()
                    ->findOrFail($id);

                // ❌ Jika sudah diproses
                if ($transaction->status !== 'pending') {
                    throw new \Exception('Transaksi sudah diproses.');
                }

                $product = $transaction->product;

                // ===============================
                // BARANG MASUK
                // ===============================
                if ($transaction->type === 'IN') {

                    $newStock = $product->stock + $transaction->quantity;

                } 
                // ===============================
                // BARANG KELUAR
                // ===============================
                else {

                    if ($product->stock < $transaction->quantity) {
                        throw new \Exception('Stok tidak mencukupi untuk disetujui.');
                    }

                    $newStock = $product->stock - $transaction->quantity;
                }

                // Update stok produk
                $product->update([
                    'stock' => $newStock
                ]);

                // Update transaksi
                $transaction->update([
                    'stock_after' => $newStock,
                    'status'      => 'approved'
                ]);

                // Log aktivitas
                logActivity(
                    'approve_transaction',
                    'Menyetujui transaksi ' . $transaction->type .
                    ' untuk produk: ' . $product->name .
                    ' (Qty: ' . $transaction->quantity . ')'
                );
            });

            return back()->with('success', 'Transaksi berhasil dikonfirmasi.');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Tolak Transaksi
    |--------------------------------------------------------------------------
    */
    public function reject($id)
    {
        try {

            $transaction = StockTransaction::with('product')
                ->findOrFail($id);

            if ($transaction->status !== 'pending') {
                return back()->with('error', 'Transaksi sudah diproses.');
            }

            $transaction->update([
                'status' => 'rejected'
            ]);

            logActivity(
                'reject_transaction',
                'Menolak transaksi ' . $transaction->type .
                ' untuk produk: ' . $transaction->product->name .
                ' (Qty: ' . $transaction->quantity . ')'
            );

            return back()->with('success', 'Transaksi berhasil ditolak.');

        } catch (\Exception $e) {

            return back()->with('error', 'Terjadi kesalahan saat menolak transaksi.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Riwayat Semua Transaksi
    |--------------------------------------------------------------------------
    */
    public function history(Request $request)
    {
        $query = StockTransaction::with(['product','supplier','user'])
            ->latest();

        // 🔍 Search nama produk
        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->paginate(10)
            ->appends($request->query());

        return view('staff.stock.history', compact('transactions'));
    }
}