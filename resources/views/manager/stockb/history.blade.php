@extends('layouts.manager')

@section('title','Riwayat Transaksi')

@section('content')

<div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
            Riwayat Perubahan Stok
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
            Data penambahan dan pengurangan stok barang secara real-time.
        </p>
    </div>
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- Filter Status -->
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Status</label>
            <select name="status"
                class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                <option value="">Semua</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option>
                <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option>
            </select>
        </div>

        <!-- Filter Tipe -->
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Tipe</label>
            <select name="type"
                class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                <option value="">Semua</option>
                <option value="IN" {{ request('type')=='IN'?'selected':'' }}>Penambahan</option>
                <option value="OUT" {{ request('type')=='OUT'?'selected':'' }}>Pengurangan</option>
            </select>
        </div>

        <!-- Filter Produk -->
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Produk</label>
            <select name="product"
                class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                <option value="">Semua</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        {{ request('product')==$product->id?'selected':'' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Tanggal -->
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Dari</label>
            <input type="date" name="start_date"
                value="{{ request('start_date') }}"
                class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Sampai</label>
            <input type="date" name="end_date" 
                value="{{ request('end_date') }}"
                class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
        </div>

        <!-- Tombol -->
        <div class="md:col-span-5 flex gap-3 mt-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Filter
            </button>

            <a href="{{ route('manager.stock.history') }}"
            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Reset
            </a>
        </div>

    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700
                           text-gray-700 dark:text-gray-200 font-semibold tracking-wide">
                    <th class="py-3 text-left">Tanggal</th>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Qty</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Status</th>
                    <th>User</th>
                </tr>
            </thead>

            <tbody class="text-gray-700 dark:text-gray-300 font-medium">

            @forelse($transactions as $trx)

                <tr class="border-b border-gray-100 dark:border-gray-800
                           hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                    <td class="py-3">
                        {{ $trx->created_at->format('d M Y H:i') }}
                    </td>

                    <td class="font-semibold text-gray-800 dark:text-white">
                        {{ $trx->product->name }}
                    </td>

                    <td>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $trx->type === 'IN'
                                ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $trx->type === 'IN' ? 'Penambahan' : 'Pengurangan' }}
                        </span>
                    </td>

                    <td class="font-semibold">
                        {{ $trx->quantity }}
                    </td>

                    <td>{{ $trx->stock_before }}</td>
                    <td>{{ $trx->stock_after }}</td>
                    <td>
                    <td>
                        @php
                            $statusText = match($trx->status) {
                                'pending' => 'Pending',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                default => 'Unknown'
                            };

                            $statusClass = match($trx->status) {
                                'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                'approved' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp

                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>

                </td>

                    <td class="text-gray-600 dark:text-gray-400">
                        {{ $trx->user->name }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7"
                        class="py-6 text-center text-gray-500 dark:text-gray-400">
                        Belum ada transaksi stok
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>

</div>

@endsection
