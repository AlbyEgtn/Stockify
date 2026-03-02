@extends('layouts.admin')

@section('title','Laporan Mutasi Stok')
@section('page-title','Laporan Mutasi Stok')

@section('content')

<div class="max-w-7xl mx-auto space-y-8 text-gray-800 dark:text-gray-200">

    {{-- ================= SUMMARY MUTASI ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow border dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Barang Masuk</p>
            <h2 class="text-2xl font-bold text-emerald-600 mt-2">
                {{ $totalIn }}
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow border dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Barang Keluar</p>
            <h2 class="text-2xl font-bold text-red-600 mt-2">
                {{ $totalOut }}
            </h2>
        </div>

    </div>

    {{-- ================= ANALISIS KONDISI STOK ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
            <p class="text-xs text-gray-500">Produk Kritis</p>
            <h3 class="text-2xl font-bold text-red-600 mt-2">
                {{ $criticalProducts->count() }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
            <p class="text-xs text-gray-500">Produk Warning</p>
            <h3 class="text-2xl font-bold text-yellow-500 mt-2">
                {{ $warningProducts->count() }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
            <p class="text-xs text-gray-500">Produk Aman</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-2">
                {{ $safeProducts->count() }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
            <p class="text-xs text-gray-500">Total Nilai Stok</p>
            <h3 class="text-xl font-bold text-indigo-600 mt-2">
                Rp {{ number_format($totalStockValue,0,',','.') }}
            </h3>
        </div>

    </div>

    {{-- ================= LIST PRODUK KRITIS ================= --}}
    @if($criticalProducts->count() > 0)
    <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-2xl border border-red-200 dark:border-red-800">
        <h4 class="font-bold text-red-600 mb-4">
            ⚠ Produk Stok Kritis
        </h4>

        <div class="grid md:grid-cols-3 gap-3 text-sm">
            @foreach($criticalProducts as $product)
                <div class="bg-white dark:bg-gray-900 p-3 rounded-lg shadow-sm">
                    <p class="font-semibold">{{ $product->name }}</p>
                    <p class="text-xs text-gray-500">
                        Stok: {{ $product->stock }} / Min: {{ $product->minimum_stock }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================= FILTER ================= --}}
    <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow border dark:border-gray-700">

        <form method="GET" class="grid md:grid-cols-5 gap-4 text-sm">

            <div>
                <label class="text-xs font-semibold">Dari Tanggal</label>
                <input type="date" name="start_date"
                    value="{{ request('start_date') }}"
                    class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800">
            </div>

            <div>
                <label class="text-xs font-semibold">Sampai Tanggal</label>
                <input type="date" name="end_date"
                    value="{{ request('end_date') }}"
                    class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800">
            </div>

            <div>
                <label class="text-xs font-semibold">Kategori</label>
                <select name="category_id"
                    class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800">
                    <option value="">Semua</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold">Tipe</label>
                <select name="type"
                    class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800">
                    <option value="">Semua</option>
                    <option value="IN" {{ request('type')=='IN'?'selected':'' }}>Masuk</option>
                    <option value="OUT" {{ request('type')=='OUT'?'selected':'' }}>Keluar</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                    Filter
                </button>

                <a href="{{ route('admin.reports.mutation') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">
                    Reset
                </a>
            </div>

        </form>

    </div>

    {{-- ================= TABLE MUTASI ================= --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border dark:border-gray-700 overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Qty</th>
                    <th>Stok Setelah</th>
                </tr>
            </thead>

            <tbody class="divide-y dark:divide-gray-800">

                @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">

                        <td class="px-5 py-4">
                            {{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}
                        </td>

                        <td>{{ $trx->product->name }}</td>

                        <td>{{ optional($trx->product->category)->name }}</td>

                        <td>
                            @if($trx->type === 'IN')
                                <span class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-600">
                                    Masuk
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">
                                    Keluar
                                </span>
                            @endif
                        </td>

                        <td class="font-semibold">
                            {{ $trx->quantity }}
                        </td>

                        <td>
                            {{ $trx->stock_after ?? '-' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400">
                            Tidak ada data mutasi.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div>
        {{ $transactions->links() }}
    </div>

</div>

@endsection