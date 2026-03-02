@extends('layouts.staff')

@section('title','Barang Keluar')
@section('page-title','Barang Keluar')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        <h1 class="text-2xl font-bold text-red-600 dark:text-red-400">
            Konfirmasi Barang Keluar
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Transaksi pengurangan stok menunggu persetujuan staff
        </p>
    </div>
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300
                    px-4 py-3 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300
                    px-4 py-3 rounded-lg shadow">
            {{ session('error') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">

        <table class="min-w-full text-sm">

            <thead class="bg-red-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Produk</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3 text-center">Stok Saat Ini</th>
                    <th class="px-4 py-3 text-left">Petugas</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

            @forelse($transactions as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                <!-- TANGGAL -->
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                    {{ $item->created_at->format('d-m-Y H:i') }}
                </td>

                <!-- PRODUK -->
                <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                    {{ $item->product->name ?? '-' }}
                </td>

                <!-- QTY -->
                <td class="px-4 py-3 text-center text-red-600 dark:text-red-400 font-semibold">
                    -{{ $item->quantity }}
                </td>

                <!-- STOK -->
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">
                    {{ $item->product->stock }}
                </td>

                <!-- PETUGAS -->
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                    {{ $item->user->name ?? '-' }}
                </td>

                <!-- CATATAN -->
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                    {{ $item->note ?? '-' }}
                </td>

                <!-- STATUS -->
                <td class="px-4 py-3 text-center">
                    @if($item->status === 'pending')
                        <span class="px-2 py-1 text-xs rounded-full
                                     bg-yellow-100 text-yellow-600
                                     dark:bg-yellow-900 dark:text-yellow-300">
                            Pending
                        </span>
                    @elseif($item->status === 'approved')
                        <span class="px-2 py-1 text-xs rounded-full
                                     bg-green-100 text-green-600
                                     dark:bg-green-900 dark:text-green-300">
                            Disetujui
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full
                                     bg-red-100 text-red-600
                                     dark:bg-red-900 dark:text-red-300">
                            Ditolak
                        </span>
                    @endif
                </td>

                <!-- AKSI -->
                <td class="px-4 py-3 text-center">

                @if($item->status === 'pending')

                <div x-data="{ open: false, actionType: '' }" class="flex justify-center gap-2">

                    <!-- APPROVE BUTTON -->
                    <button @click="open = true; actionType='approve'"
                        class="bg-green-600 hover:bg-green-700
                            text-white px-3 py-1.5 rounded-lg text-xs transition shadow">
                        Setujui
                    </button>

                    <!-- REJECT BUTTON -->
                    <button @click="open = true; actionType='reject'"
                        class="bg-red-600 hover:bg-red-700
                            text-white px-3 py-1.5 rounded-lg text-xs transition shadow">
                        Tolak
                    </button>

                    <!-- MODAL -->
                    <div x-show="open"
                        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
                        x-transition>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-96">

                            <h2 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">
                                Konfirmasi Aksi
                            </h2>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-5">
                                Apakah Anda yakin ingin
                                <span class="font-semibold"
                                    x-text="actionType === 'approve' ? 'menyetujui' : 'menolak'">
                                </span>
                                transaksi ini?
                            </p>

                            <div class="flex justify-end gap-3">

                                <button @click="open=false"
                                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600
                                        rounded-lg text-sm">
                                    Batal
                                </button>

                                <!-- FORM DINAMIS -->
                                <form :action="actionType === 'approve'
                                        ? '{{ route('staff.stock.confirm', $item->id) }}'
                                        : '{{ route('staff.stock.reject', $item->id) }}'"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 text-white rounded-lg text-sm"
                                        :class="actionType === 'approve'
                                                ? 'bg-green-600 hover:bg-green-700'
                                                : 'bg-red-600 hover:bg-red-700'">
                                        Ya, Lanjutkan
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                @else
                <span class="text-gray-400 dark:text-gray-500 text-xs">
                    Tidak ada aksi
                </span>
                @endif

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">
                    Tidak ada transaksi barang keluar.
                </td>
            </tr>
            @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="dark:text-gray-300">
        {{ $transactions->links() }}
    </div>

</div>

@endsection