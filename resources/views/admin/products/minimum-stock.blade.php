@extends('layouts.admin')

@section('title','Pengaturan Minimum Stock')
@section('page-title','Pengaturan Minimum Stock')

@section('content')

<div class="space-y-6">

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
            Atur Minimum Stock Produk
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Admin dapat menentukan batas stok minimum untuk setiap produk.
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
        <form method="GET" class="flex flex-wrap items-center gap-3">

            {{-- SEARCH --}}
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama produk..."
                class="px-4 py-2 border rounded-lg text-sm
                        dark:bg-gray-700 dark:text-white">

            {{-- FILTER STATUS --}}
            <select name="status"
                    class="px-4 py-2 border rounded-lg text-sm
                        dark:bg-gray-700 dark:text-white">

                <option value="">Semua Status</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>
                    Stok Rendah
                </option>
                <option value="safe" {{ request('status') == 'safe' ? 'selected' : '' }}>
                    Aman
                </option>

            </select>

            {{-- BUTTON --}}
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white
                        rounded-lg text-sm hover:bg-indigo-700 transition">
                Filter
            </button>

            {{-- RESET --}}
            <a href="{{ route('admin.products.minimumStock') }}"
            class="px-4 py-2 bg-gray-300 dark:bg-gray-700
                    rounded-lg text-sm">
                Reset
            </a>

        </form>
    </div>
    
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

        <table class="w-full text-sm">

            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="px-5 py-3 text-left">Nama Produk</th>
                    <th class="px-5 py-3 text-center">Stok Saat Ini</th>
                    <th class="px-5 py-3 text-center">Minimum Stock</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">

            @forelse($products as $product)

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">
                        {{ $product->name }}
                    </td>

                    <td class="px-5 py-3 text-center dark:text-gray-300">
                        {{ $product->stock }}
                    </td>

                    <td class="px-5 py-3 text-center">

                        <form action="{{ route('admin.products.updateMinimumStock', $product) }}"
                              method="POST"
                              class="flex justify-center gap-2">

                            @csrf

                            <input type="number"
                                   name="minimum_stock"
                                   value="{{ $product->minimum_stock }}"
                                   min="0"
                                   class="w-20 px-2 py-1 border rounded text-center
                                          dark:bg-gray-700 dark:text-gray-300">

                            <button type="submit"
                                    class="px-3 py-1 text-xs rounded-lg
                                           bg-blue-600 text-white
                                           hover:bg-blue-700 transition">
                                Simpan
                            </button>

                        </form>

                    </td>

                    <td class="px-5 py-3 text-center">
                        @if($product->stock <= $product->minimum_stock)
                            <span class="px-2 py-1 text-xs rounded-full
                                         bg-red-100 text-red-600
                                         dark:bg-red-900/30 dark:text-red-400">
                                Stok Rendah
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full
                                         bg-green-100 text-green-600
                                         dark:bg-green-900/30 dark:text-green-400">
                                Aman
                            </span>
                        @endif
                    </td>

                    <td class="px-5 py-3 text-center">
                        <button type="button"
                            onclick="openProductModal({{ $product->id }})"
                            class="px-3 py-1 text-xs rounded-lg
                                bg-gray-200 text-gray-700
                                hover:bg-gray-300 transition">
                            Detail
                        </button>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5"
                        class="px-5 py-6 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada produk
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div>
        {{ $products->links() }}
    </div>

</div>
<!-- MODAL DETAIL PRODUK -->
<div id="productModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-900
                rounded-2xl shadow-xl
                w-full max-w-lg p-6 relative">

        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
            Detail Produk
        </h3>

        <div id="productModalContent"
             class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
        </div>

        <div class="mt-6 text-right">
            <button onclick="closeProductModal()"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-700
                       rounded-lg text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>
<script>
const products = @json($products->items());

function openProductModal(id) {

    const product = products.find(p => p.id === id);

    const imageUrl = product.image
        ? `/storage/${product.image}`
        : null;

    const html = `
        <div class="space-y-4">

            <!-- GAMBAR -->
            <div class="flex justify-center">
                ${
                    imageUrl
                    ? `<img src="${imageUrl}"
                           class="w-32 h-32 object-cover rounded-2xl shadow-lg border
                                  border-gray-200 dark:border-gray-700">`
                    : `<div class="w-32 h-32 flex items-center justify-center
                                bg-gray-200 dark:bg-gray-700
                                rounded-2xl text-gray-400 text-sm">
                            Tidak ada gambar
                       </div>`
                }
            </div>

            <!-- DETAIL -->
            <div class="grid grid-cols-2 gap-3 text-sm">

                <div><strong>Nama:</strong></div>
                <div>${product.name}</div>

                <div><strong>Stok:</strong></div>
                <div>${product.stock}</div>

                <div><strong>Minimum Stock:</strong></div>
                <div>${product.minimum_stock}</div>

                <div><strong>Harga Beli:</strong></div>
                <div>Rp ${Number(product.purchase_price).toLocaleString('id-ID')}</div>

                <div><strong>Harga Jual:</strong></div>
                <div>Rp ${Number(product.selling_price ?? 0).toLocaleString('id-ID')}</div>

                <div><strong>Nilai Persediaan:</strong></div>
                <div class="font-semibold text-indigo-600">
                    Rp ${(product.stock * product.purchase_price).toLocaleString('id-ID')}
                </div>

            </div>
        </div>
    `;

    document.getElementById('productModalContent').innerHTML = html;

    const modal = document.getElementById('productModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
