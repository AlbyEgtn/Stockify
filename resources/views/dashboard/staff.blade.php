@extends('layouts.staff')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Staff')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
            {{ $totalProducts }}
        </h3>
    </div>

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500 dark:text-gray-400">Stok Minimum</p>
        <h3 class="text-3xl font-bold text-yellow-500 mt-2">
            {{ $lowStock }}
        </h3>
    </div>

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500 dark:text-gray-400">Barang Masuk Hari Ini</p>
        <h3 class="text-3xl font-bold text-emerald-500 mt-2">
            {{ $incomingToday }}
        </h3>
    </div>

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500 dark:text-gray-400">Barang Keluar Hari Ini</p>
        <h3 class="text-3xl font-bold text-red-500 mt-2">
            {{ $outgoingToday }}
        </h3>
    </div>

</div>
<div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- BARANG MASUK -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Barang Masuk Perlu Diperiksa
            </h3>

            <a href="{{ route('staff.stock.incoming') }}"
            class="text-sm text-emerald-600 hover:underline">
                Lihat Semua →
            </a>
        </div>

        @forelse($incomingTasks as $task)

            <div class="flex justify-between items-center
                        border-b border-gray-200 dark:border-gray-700
                        py-3">

                <div>
                    <p class="font-medium text-gray-800 dark:text-white">
                        {{ $task->product->name }}
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Supplier: {{ $task->supplier->name ?? '-' }}
                    </p>

                    <p class="text-xs text-gray-400">
                        {{ $task->created_at->format('d M Y H:i') }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Qty: {{ $task->quantity }}
                    </p>
                </div>

                <span class="px-3 py-1 text-xs rounded-full
                    {{ $task->status == 'approved'
                        ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300'
                        : ($task->status == 'rejected'
                            ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300'
                            : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300') }}">
                    {{ ucfirst($task->status) }}
                </span>

            </div>

        @empty
            <p class="text-gray-500 dark:text-gray-400">
                Tidak ada tugas barang masuk.
            </p>
        @endforelse

    </div>

    <!-- BARANG KELUAR -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Barang Keluar Perlu Disiapkan
        </h3>

        @forelse($outgoingTasks as $task)

            <div class="flex justify-between items-center
                        border-b border-gray-200 dark:border-gray-700
                        py-3">

                <div>
                    <p class="font-medium text-gray-800 dark:text-white">
                        {{ $task->product->name }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Qty: {{ $task->quantity }}
                    </p>
                </div>

                <span class="px-3 py-1 text-xs rounded-full
                    {{ $task->status == 'approved'
                        ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300'
                        : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300' }}">
                    {{ ucfirst($task->status) }}
                </span>

            </div>

        @empty
            <p class="text-gray-500">Tidak ada tugas barang keluar.</p>
        @endforelse

    </div>

</div>

@endsection