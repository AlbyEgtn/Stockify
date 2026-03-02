@extends('layouts.staff')

@section('title','Riwayat Transaksi')
@section('page-title','Riwayat Transaksi')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- SEARCH & FILTER -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <!-- SEARCH -->
            <div>
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama produk..."
                    class="w-full px-4 py-2 rounded-lg border
                            dark:bg-gray-700 dark:border-gray-600
                            dark:text-white text-sm">
            </div>

            <!-- TYPE -->
            <div>
                <select name="type"
                        class="w-full px-4 py-2 rounded-lg border
                            dark:bg-gray-700 dark:border-gray-600
                            dark:text-white text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="IN" {{ request('type')=='IN'?'selected':'' }}>
                        Barang Masuk
                    </option>
                    <option value="OUT" {{ request('type')=='OUT'?'selected':'' }}>
                        Barang Keluar
                    </option>
                </select>
            </div>

            <!-- STATUS -->
            <div>
                <select name="status"
                        class="w-full px-4 py-2 rounded-lg border
                            dark:bg-gray-700 dark:border-gray-600
                            dark:text-white text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>
                        Pending
                    </option>
                    <option value="approved" {{ request('status')=='approved'?'selected':'' }}>
                        Approved
                    </option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>
                        Rejected
                    </option>
                </select>
            </div>

            <!-- DATE -->
            <div>
                <input type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="w-full px-4 py-2 rounded-lg border
                            dark:bg-gray-700 dark:border-gray-600
                            dark:text-white text-sm">
            </div>

            <!-- BUTTON -->
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-emerald-600 hover:bg-emerald-700
                        text-white px-4 py-2 rounded-lg text-sm transition">
                    Filter
                </button>

                <a href="{{ route('staff.stock.history') }}"
                class="flex-1 bg-gray-400 hover:bg-gray-500
                        text-white px-4 py-2 rounded-lg text-sm text-center transition">
                    Reset
                </a>
            </div>

        </form>

</div>
    <!-- TABEL -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Jenis</th>
                    <th class="px-4 py-3 text-left">Produk</th>
                    <th class="px-4 py-3 text-left">Supplier</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3 text-center">Stok Sebelum</th>
                    <th class="px-4 py-3 text-center">Stok Sesudah</th>
                    <th class="px-4 py-3 text-left">Petugas</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

            @forelse($transactions as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                    {{ $item->created_at->format('d-m-Y H:i') }}
                </td>

                <!-- TYPE -->
                <td class="px-4 py-3">
                    @if($item->type === 'IN')
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 rounded-full">
                            Barang Masuk
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 rounded-full">
                            Barang Keluar
                        </span>
                    @endif
                </td>

                <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                    {{ $item->product->name ?? '-' }}
                </td>

                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                    {{ $item->supplier->name ?? '-' }}
                </td>

                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">
                    {{ $item->quantity }}
                </td>

                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">
                    {{ $item->stock_before }}
                </td>

                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">
                    {{ $item->stock_after }}
                </td>

                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                    {{ $item->user->name ?? '-' }}
                </td>

                <!-- STATUS -->
                <td class="px-4 py-3 text-center">

                    @if($item->status === 'pending')
                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 rounded-full">
                            Pending
                        </span>

                    @elseif($item->status === 'approved')
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 rounded-full">
                            Approved
                        </span>

                    @else
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 rounded-full">
                            Rejected
                        </span>
                    @endif

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-6 text-gray-500 dark:text-gray-400">
                    Tidak ada transaksi.
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