@extends('layouts.admin')

@section('title', 'Laporan Barang Masuk & Keluar')
@section('page-title', 'Laporan Barang Masuk & Keluar')

@section('content')

<div class="max-w-7xl mx-auto space-y-10 text-gray-800 dark:text-gray-100">

    {{-- ================= SUMMARY ================= --}}
    <div class="grid md:grid-cols-2 gap-6">

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                Total Barang Masuk
            </p>
            <h3 class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-2">
                {{ number_format($totalIn) }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                Total Barang Keluar
            </p>
            <h3 class="text-4xl font-extrabold text-rose-600 dark:text-rose-400 mt-2">
                {{ number_format($totalOut) }}
            </h3>
        </div>

    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-6">

        <h4 class="font-semibold text-lg mb-5">
            Filter Data
        </h4>

        <form method="GET" class="grid md:grid-cols-5 gap-4">

            {{-- Dari --}}
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Dari Tanggal
                </label>
                <input type="date" name="start_date"
                    value="{{ request('start_date') }}"
                    class="w-full mt-1 px-3 py-2 rounded-lg border
                           bg-gray-50 dark:bg-gray-700
                           border-gray-300 dark:border-gray-600
                           text-gray-800 dark:text-white
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            {{-- Sampai --}}
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Sampai Tanggal
                </label>
                <input type="date" name="end_date"
                    value="{{ request('end_date') }}"
                    class="w-full mt-1 px-3 py-2 rounded-lg border
                           bg-gray-50 dark:bg-gray-700
                           border-gray-300 dark:border-gray-600
                           text-gray-800 dark:text-white
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Kategori
                </label>
                <select name="category_id"
                    class="w-full mt-1 px-3 py-2 rounded-lg border
                           bg-gray-50 dark:bg-gray-700
                           border-gray-300 dark:border-gray-600
                           text-gray-800 dark:text-white
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipe --}}
            <div>
                <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Tipe Transaksi
                </label>
                <select name="type"
                    class="w-full mt-1 px-3 py-2 rounded-lg border
                           bg-gray-50 dark:bg-gray-700
                           border-gray-300 dark:border-gray-600
                           text-gray-800 dark:text-white
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                    <option value="">Semua</option>
                    <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>Masuk</option>
                    <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>

            {{-- Button --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    Filter
                </button>

                <a href="{{ route('admin.reports.transactions') }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg transition">
                    Reset
                </a>
            </div>

        </form>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h4 class="font-semibold text-lg">
                Detail Transaksi
            </h4>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-100 dark:bg-gray-900
                               text-gray-700 dark:text-gray-200
                               uppercase text-xs font-bold tracking-wider">

                    <tr>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Produk</th>
                        <th class="px-6 py-4 text-left">Kategori</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-center">Qty</th>
                        <th class="px-6 py-4 text-center">Stok Sebelum</th>
                        <th class="px-6 py-4 text-center">Stok Sesudah</th>
                        <th class="px-6 py-4 text-left">User</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                @forelse($transactions as $trx)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                        <td class="px-6 py-4 font-medium">
                            {{ $trx->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 font-semibold">
                            {{ $trx->product->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ optional($trx->product->category)->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($trx->type == 'IN')
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                            bg-emerald-500/20 text-emerald-300">
                                    MASUK
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                            bg-rose-500/20 text-rose-300">
                                    KELUAR
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center font-bold">
                            {{ $trx->quantity }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ $trx->stock_before ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $trx->stock_after ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $trx->user->name ?? '-' }}
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-gray-500 dark:text-gray-400">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>
        </div>

        @if(method_exists($transactions, 'links'))
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->links() }}
        </div>
        @endif

    </div>

</div>

@endsection