@extends('layouts.manager')

@section('title','Barang Keluar')
@section('page-title','Barang Keluar')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="flex items-center gap-3">
            <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                📤
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    Barang Keluar
                </h2>
                <p class="text-sm text-gray-500">
                    Kurangi stok dan pantau status persetujuan
                </p>
            </div>
        </div>
    </div>

    <!-- TABEL PRODUK -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Daftar Produk
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                        <th class="py-3">Nama</th>
                        <th class="py-3">SKU</th>
                        <th class="py-3">Stok</th>
                        <th class="py-3">Status Terakhir</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)

                        @php
                            $last = $product->latestOutgoing;
                        @endphp

                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                            <!-- NAMA -->
                            <td class="py-3 font-medium text-gray-800 dark:text-white">
                                {{ $product->name }}
                            </td>

                            <!-- SKU -->
                            <td class="py-3 text-gray-500">
                                {{ $product->sku }}
                            </td>

                            <!-- STOK -->
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $product->stock <= $product->minimum_stock
                                        ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300'
                                        : 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>

                            <!-- STATUS -->
                            <td class="py-3">
                                @if(!$last)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded-full">
                                        Belum Ada
                                    </span>

                                @elseif($last->status === 'pending')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-600 rounded-full">
                                        Pending
                                    </span>

                                @elseif($last->status === 'approved')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">
                                        Disetujui
                                    </span>

                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="py-3 text-center">

                                <button onclick="openModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }})"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs">
                                    Kurangi Stok
                                </button>

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">
                                Tidak ada produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- MODAL KURANGI STOK -->
<div id="stockModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 w-full max-w-md">

        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
            Kurangi Stok
        </h3>

        <form method="POST" action="{{ route('manager.stock.storeOutgoing') }}">
            @csrf

            <input type="hidden" name="product_id" id="product_id">

            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    Produk
                </label>
                <input type="text" id="product_name"
                    class="w-full px-4 py-2 rounded-xl border bg-gray-100 dark:bg-gray-700"
                    readonly>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    Stok Saat Ini
                </label>
                <input type="number" id="current_stock"
                    class="w-full px-4 py-2 rounded-xl border bg-gray-100 dark:bg-gray-700"
                    readonly>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    Jumlah Keluar
                </label>
                <input type="number" name="quantity"
                    class="w-full px-4 py-2 rounded-xl border focus:ring-2 focus:ring-red-400"
                    min="1" required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal()"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600">
                    Batal
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function openModal(id, name, stock) {
    document.getElementById('stockModal').classList.remove('hidden');
    document.getElementById('stockModal').classList.add('flex');
    document.getElementById('product_id').value = id;
    document.getElementById('product_name').value = name;
    document.getElementById('current_stock').value = stock;
}

function closeModal() {
    document.getElementById('stockModal').classList.add('hidden');
    document.getElementById('stockModal').classList.remove('flex');
}
</script>

@endsection
