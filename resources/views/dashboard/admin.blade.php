@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- WELCOME -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600
                text-white p-6 rounded-2xl shadow-lg">

        <h3 class="text-2xl font-bold">
            Selamat Datang, {{ auth()->user()->name }}
        </h3>

        <p class="text-sm opacity-90 mt-1">
            Ringkasan performa sistem Stockify hari ini.
        </p>
    </div>


    <!-- STATISTICS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                {{ $totalProducts }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Stok Rendah</p>
            <h3 class="text-3xl font-bold text-red-500 mt-2">
                {{ $lowStockProducts }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Kategori</p>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                {{ $totalCategories }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Supplier</p>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                {{ $totalSuppliers }}
            </h3>
        </div>

    </div>


    <!-- GRAFIK -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Grafik Stok Produk
        </h3>

        <div class="relative w-full h-72">
            <canvas id="stockChart"></canvas>
        </div>

    </div>


    <!-- PRODUK TERBARU -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Produk Terbaru
            </h3>

            <a href="{{ route('admin.products.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @forelse($recentProducts as $product)

                <div class="border border-gray-200 dark:border-gray-700
                            rounded-xl p-4 bg-gray-50 dark:bg-gray-900">

                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}"
                             class="w-full h-40 object-cover rounded-lg mb-3">
                    @else
                        <div class="w-full h-40 flex items-center justify-center
                                    bg-gray-200 dark:bg-gray-700
                                    rounded-lg mb-3 text-gray-400">
                            No Image
                        </div>
                    @endif

                    <h4 class="font-semibold text-gray-800 dark:text-white">
                        {{ $product->name }}
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}
                    </p>

                    <div class="mt-2">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $product->stock <= $product->minimum_stock
                                ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300'
                                : 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' }}">
                            Stok: {{ $product->stock }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-1 mt-3">
                        @foreach($product->productAttributes as $attr)
                            <span class="px-2 py-1 text-xs rounded-full
                                         bg-indigo-100 text-indigo-600
                                         dark:bg-indigo-900 dark:text-indigo-300">
                                {{ $attr->name }}: {{ $attr->value }}
                            </span>
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-400 mt-3">
                        {{ $product->created_at->format('d M Y') }}
                    </p>

                </div>

            @empty
                <p class="text-gray-500">Belum ada produk</p>
            @endforelse

        </div>

    </div>

</div>


<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('stockChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartProducts->pluck('name')),
            datasets: [{
                label: 'Jumlah Stok',
                data: @json($chartProducts->pluck('stock')),
                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                borderRadius: 6
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true
        }
    });

});
</script>

@endsection
