<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockTransaction;

class TransactionReportController extends Controller
{
    public function index(Request $request)
{
    $query = StockTransaction::with(['product','user']);

    if ($request->type) {
        $query->where('type', $request->type);
    }

    if ($request->start_date && $request->end_date) {
        $query->whereBetween('created_at', [
            $request->start_date,
            $request->end_date
        ]);
    }

    $transactions = $query->latest()->get();

    return view('admin.reports.transactions', compact('transactions'));
}

}
