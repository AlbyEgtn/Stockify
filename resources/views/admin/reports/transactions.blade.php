@extends('layouts.admin')

@section('title', 'Laporan Barang Masuk & Keluar')
@section('page-title', 'Laporan Barang Masuk & Keluar')

@section('content')

<div class="space-y-8">

    <!-- FILTER -->
    <div class="bg-white dark:bg-gray-800
                rounded-xl shadow-sm border
                border-gray-200 dark:border-gray-700
                p-4">

        <form method="GET" class="flex flex-wrap items-end gap-3">

            <div class="flex flex-col">
                <label class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                    Dari
                </label>
                <input type="date" name="start_date"
                    value="{{ request('start_date') }}"
                    class="px-3 py-1.5 text-sm rounded-md border
                        bg-gray-50 dark:bg-gray-700
                        border-gray-300 dark:border-gray-600
                        text-gray-700 dark:text-white
                        focus:ring-1 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                    Sampai
                </label>
                <input type="date" name="end_date"
                    value="{{ request('end_date') }}"
                    class="px-3 py-1.5 text-sm rounded-md border
                        bg-gray-50 dark:bg-gray-700
                        border-gray-300 dark:border-gray-600
                        text-gray-700 dark:text-white
                        focus:ring-1 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                    Produk
                </label>
                <select name="product_id"
                    class="px-3 py-1.5 text-sm rounded-md border
                        bg-gray-50 dark:bg-gray-700
                        border-gray-300 dark:border-gray-600
                        text-gray-700 dark:text-white
                        focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Semua</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700
                        text-white text-sm px-4 py-1.5
                        rounded-md transition">
                    Filter
                </button>

                <a href="{{ route('admin.reports.transactions') }}"
                class="bg-gray-200 dark:bg-gray-600
                        hover:bg-gray-300 dark:hover:bg-gray-500
                        text-gray-700 dark:text-white
                        text-sm px-4 py-1.5
                        rounded-md transition">
                    Reset
                </a>
            </div>

        </form>
    </div>



    <!-- SUMMARY -->
    <div class="grid md:grid-cols-2 gap-6">

        <div class="bg-gradient-to-r from-indigo-600 to-purple-600
                    text-white rounded-2xl p-6 shadow-lg">
            <p class="text-sm opacity-80">Total Barang Masuk</p>
            <h3 class="text-3xl font-bold mt-2">
                {{ number_format($totalIn) }}
            </h3>
        </div>

        <div class="bg-gradient-to-r from-rose-600 to-red-600
                    text-white rounded-2xl p-6 shadow-lg">
            <p class="text-sm opacity-80">Total Barang Keluar</p>
            <h3 class="text-3xl font-bold mt-2">
                {{ number_format($totalOut) }}
            </h3>
        </div>

    </div>


    <!-- TABLE -->
    <div class="bg-white dark:bg-gray-800
                rounded-2xl shadow border
                border-gray-200 dark:border-gray-700
                overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h4 class="font-semibold text-gray-700 dark:text-gray-200">
                Detail Transaksi
            </h4>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">

                <thead class="bg-gray-50 dark:bg-gray-700
                               text-gray-600 dark:text-gray-300
                               uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Stok Sebelum</th>
                        <th class="px-6 py-4">Stok Sesudah</th>
                        <th class="px-6 py-4">User</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $trx->created_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                                {{ $trx->product->name }}
                            </td>

                            <td class="px-6 py-4">
                                @if($trx->type == 'IN')
                                    <span class="px-3 py-1 text-xs rounded-full
                                                bg-indigo-100 text-indigo-700
                                                dark:bg-indigo-500/20 dark:text-indigo-300
                                                font-semibold">
                                        MASUK
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full
                                                bg-red-100 text-red-700
                                                dark:bg-red-500/20 dark:text-red-300
                                                font-semibold">
                                        KELUAR
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                                {{ $trx->quantity }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $trx->stock_before }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $trx->stock_after }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $trx->user->name ?? '-' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="text-center py-8 text-gray-400 dark:text-gray-500">
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
