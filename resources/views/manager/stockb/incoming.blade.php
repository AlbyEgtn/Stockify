@extends('layouts.manager')

@section('title','Barang Masuk')
@section('page-title','Barang Masuk')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">

        <div class="flex items-center gap-3">
            <div class="bg-green-100 text-green-600 p-3 rounded-xl">
                📥
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    Barang Masuk
                </h2>
                <p class="text-sm text-gray-500">
                    Tambah stok dari produk yang tersedia
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
                        <th class="py-3">Stok Saat Ini</th>
                        <th class="py-3">Status Terakhir</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                            <td class="py-3 font-medium text-gray-800 dark:text-white">
                                {{ $product->name }}
                            </td>

                            <td class="py-3 text-gray-500">
                                {{ $product->sku }}
                            </td>

                            <td class="py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $product->stock <= $product->minimum_stock
                                        ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300'
                                        : 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="py-3">
                                @php
                                    $lastTransaction = $product->latestIncoming;
                                @endphp

                                @if(!$lastTransaction)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded-full">
                                        Belum Ada
                                    </span>
                                @elseif($lastTransaction->status == 'pending')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-600 rounded-full">
                                        Pending
                                    </span>
                                @elseif($lastTransaction->status == 'approved')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">
                                        Approved
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">
                                        Rejected
                                    </span>
                                @endif

                            </td>

                            <td class="py-3 text-center">

                                <!-- BUTTON MODAL -->
                                <button onclick="openModal({{ $product->id }}, '{{ $product->name }}')"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-xs">
                                    Tambah Stok
                                </button>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">
                                Tidak ada produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- MODAL TAMBAH STOK -->
<div id="stockModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 w-full max-w-md">

        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
            Tambah Stok
        </h3>

        <form method="POST" action="{{ route('manager.stock.storeIncoming') }}">
            @csrf

            <input type="hidden" name="product_id" id="product_id">

            <!-- PRODUK -->
            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    Produk
                </label>
                <input type="text" id="product_name"
                    class="w-full px-4 py-2 rounded-xl border bg-gray-100 dark:bg-gray-700"
                    readonly>
            </div>

            <!-- SUPPLIER (BARU) -->
            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    Supplier
                </label>
                <select name="supplier_id"
                        class="w-full px-4 py-2 rounded-xl border focus:ring-2 focus:ring-green-400"
                        required>

                    <option value="">-- Pilih Supplier --</option>

                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">
                            {{ $supplier->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- JUMLAH -->
            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    Jumlah Masuk
                </label>
                <input type="number" name="quantity"
                    class="w-full px-4 py-2 rounded-xl border focus:ring-2 focus:ring-green-400"
                    min="1" required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal()"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600">
                    Batal
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>


<script>
function openModal(id, name) {
    document.getElementById('stockModal').classList.remove('hidden');
    document.getElementById('stockModal').classList.add('flex');
    document.getElementById('product_id').value = id;
    document.getElementById('product_name').value = name;
}

function closeModal() {
    document.getElementById('stockModal').classList.add('hidden');
    document.getElementById('stockModal').classList.remove('flex');
}
</script>

@endsection
