@extends('layouts.manager')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    <!-- WELCOME WRAPPER -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 
                text-white p-8 rounded-3xl shadow-lg">
        <h2 class="text-3xl font-bold">
            Selamat Datang, {{ auth()->user()->name }}
        </h2>
        <p class="mt-2 opacity-90 text-sm">
            Monitoring aktivitas dan kondisi stok gudang secara real-time.
        </p>
    </div>

    <!-- STATISTICS WRAPPER -->
    <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-md">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Ringkasan Stok
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- TOTAL PRODUK -->
            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-2xl">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Total Produk
                </p>
                <h3 class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">
                    {{ $totalProducts }}
                </h3>
            </div>

            <!-- STOK RENDAH -->
            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-2xl">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Stok Rendah
                </p>
                <h3 class="text-3xl font-bold mt-2 text-red-500">
                    {{ $lowStockProducts }}
                </h3>
            </div>

            <!-- MASUK HARI INI -->
            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-2xl">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Produk Masuk Hari Ini
                </p>
                <h3 class="text-3xl font-bold mt-2 text-green-600">
                    {{ $productsInToday }}
                </h3>
            </div>

            <!-- KELUAR HARI INI -->
            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-2xl">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Produk Keluar Hari Ini
                </p>
                <h3 class="text-3xl font-bold mt-2 text-red-600">
                    {{ $productsOutToday }}
                </h3>
            </div>

        </div>
    </div>

    <!-- AKTIVITAS WRAPPER -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- PRODUK MASUK TERBARU -->
        <div class="bg-white dark:bg-gray-900 
                    rounded-3xl shadow-lg 
                    border border-gray-200 dark:border-gray-700">

            <!-- HEADER -->
            <div class="flex items-center justify-between 
                        px-6 py-4 
                        bg-green-50 dark:bg-green-900/30 
                        rounded-t-3xl">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center
                                bg-green-100 dark:bg-green-800
                                text-green-600 dark:text-green-300
                                rounded-xl text-lg">
                        📥
                    </div>

                    <h3 class="text-lg font-semibold 
                            text-gray-900 dark:text-white">
                        Produk Masuk Terbaru
                    </h3>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="p-6 space-y-4">

                @forelse($latestIncoming as $trx)
                    <div class="flex justify-between items-center
                                p-4 rounded-xl
                                hover:bg-gray-50 dark:hover:bg-gray-800
                                transition">

                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $trx->product->name }}
                            </p>
                            <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">
                                {{ $trx->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm font-bold
                                    bg-green-100 text-green-700
                                    dark:bg-green-900 dark:text-green-300">
                            +{{ $trx->quantity }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Tidak ada transaksi masuk.
                    </p>
                @endforelse

            </div>
        </div>

        <!-- PRODUK KELUAR TERBARU -->
        <div class="bg-white dark:bg-gray-900 
                    rounded-3xl shadow-lg 
                    border border-gray-200 dark:border-gray-700">

            <!-- HEADER -->
            <div class="flex items-center justify-between 
                        px-6 py-4 
                        bg-red-50 dark:bg-red-900/30 
                        rounded-t-3xl">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center
                                bg-red-100 dark:bg-red-800
                                text-red-600 dark:text-red-300
                                rounded-xl text-lg">
                        📤
                    </div>

                    <h3 class="text-lg font-semibold 
                            text-gray-900 dark:text-white">
                        Produk Keluar Terbaru
                    </h3>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="p-6 space-y-4">

                @forelse($latestOutgoing as $trx)
                    <div class="flex justify-between items-center
                                p-4 rounded-xl
                                hover:bg-gray-50 dark:hover:bg-gray-800
                                transition">

                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $trx->product->name }}
                            </p>
                            <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">
                                {{ $trx->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm font-bold
                                    bg-red-100 text-red-700
                                    dark:bg-red-900 dark:text-red-300">
                            -{{ $trx->quantity }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Tidak ada transaksi keluar.
                    </p>
                @endforelse

            </div>
        </div>

    </div>
    
    <!-- STOK MINIMUM WRAPPER -->
    <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-md">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Produk Stok Minimum
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700
                               text-gray-600 dark:text-gray-400">
                        <th class="py-3 text-left">Produk</th>
                        <th>Stok</th>
                        <th>Minimum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockList as $product)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="py-3 font-medium text-gray-900 dark:text-white">
                                {{ $product->name }}
                            </td>
                            <td class="text-red-500 font-semibold">
                                {{ $product->stock }}
                            </td>
                            <td>
                                {{ $product->minimum_stock }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                Semua stok dalam kondisi aman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
