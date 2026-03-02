@extends('layouts.admin')

@section('title','Riwayat Stok')
@section('page-title','Riwayat Transaksi Stok')

@section('content')

<div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
            Riwayat Perubahan Stok
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
            Data penambahan dan pengurangan stok barang.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700
                           text-gray-700 dark:text-gray-200 font-semibold">
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

            <tbody class="text-gray-700 dark:text-gray-300">

            @forelse($transactions as $trx)

                <tr class="border-b border-gray-100 dark:border-gray-800
                           hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                    <td class="py-3">
                        {{ $trx->created_at->format('d M Y H:i') }}
                    </td>

                    <td class="font-semibold">
                        {{ $trx->product->name }}
                    </td>

                    <td>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $trx->type === 'IN'
                                ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $trx->type === 'IN' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>

                    <td class="font-semibold">
                        {{ $trx->quantity }}
                    </td>

                    <td>{{ $trx->stock_before }}</td>
                    <td>{{ $trx->stock_after }}</td>

                    <td>
                        @php
                            $status = $trx->status ?? 'pending';

                            $badge = match($status) {
                                'approved' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                default    => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                            };
                        @endphp

                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>

                    <td class="text-gray-600 dark:text-gray-400">
                        {{ $trx->user->name }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8"
                        class="py-6 text-center text-gray-500 dark:text-gray-400">
                        Belum ada transaksi stok.
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
