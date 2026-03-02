@extends('layouts.manager')

@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 text-sm rounded-xl
                bg-green-100 text-green-800
                dark:bg-green-900 dark:text-green-300">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800
            rounded-2xl shadow-lg overflow-hidden
            transition-colors duration-300">

    <!-- Header -->
    <div class="flex justify-between items-center p-6 border-b
                dark:border-gray-700">

        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Daftar Produk
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola seluruh produk gudang
            </p>
        </div>

        <a href="{{ route('manager.products.create') }}"
           class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700
                  text-white rounded-xl font-medium
                  shadow-md hover:shadow-lg transition">
            + Tambah Produk
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">

            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Supplier</th>
                    <th class="px-6 py-4">Harga Beli</th>
                    <th class="px-6 py-4">Harga Jual</th>
                    <th class="px-6 py-4">Stok</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y dark:divide-gray-700">

                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <!-- Produk Info -->
                        <td class="px-6 py-4 flex items-center gap-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     class="w-12 h-12 object-cover rounded-xl shadow">
                            @else
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600
                                            rounded-xl flex items-center justify-center
                                            text-xs text-gray-500 dark:text-gray-300">
                                    No Image
                                </div>
                            @endif

                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">
                                    {{ $product->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    SKU: {{ $product->sku }}
                                </p>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $product->category->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $product->supplier->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                            Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>

                        <!-- Stock Badge -->
                        <td class="px-6 py-4 dark:text-gray-400">
                            @if($product->stock <= $product->minimum_stock)
                                <span class="px-3 py-1 text-xs font-semibold
                                             bg-red-100 text-red-700
                                             dark:bg-red-900 dark:text-red-300
                                             rounded-full">
                                    {{ $product->stock }} (Low)
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold
                                             bg-green-100 text-green-700
                                             dark:bg-green-900 dark:text-green-300
                                             rounded-full">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>

                        <!-- Action Buttons -->
                        <td class="px-6 py-4 text-center space-x-2">
                            <button onclick="openDetailModal({{ $product->id }})"
                                class="px-3 py-1 text-xs
                                    bg-indigo-600 hover:bg-indigo-700
                                    text-white rounded-lg transition">
                                Detail
                            </button>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-10 text-center
                                   text-gray-500 dark:text-gray-400">
                            Belum ada produk.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t dark:border-gray-700">
        {{ $products->links() }}
    </div>

</div>
<!-- DETAIL MODAL -->
<div id="detailModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-900 
                w-full max-w-3xl 
                rounded-3xl shadow-2xl 
                p-8 relative 
                border border-gray-200 dark:border-gray-700">

        <!-- Close -->
        <button onclick="closeDetailModal()"
                class="absolute top-5 right-5 
                       text-gray-500 hover:text-gray-900 
                       dark:text-gray-400 dark:hover:text-white 
                       text-lg font-bold">
            ✕
        </button>

        <div id="detailContent"></div>

    </div>
</div>

<script>

const products = @json($products->items());

function openDetailModal(id) {

    const product = products.find(p => p.id === id);
    if (!product) return;

    let attributesHtml = '';

    if (product.product_attributes && product.product_attributes.length > 0) {
        product.product_attributes.forEach(attr => {
            attributesHtml += `
                <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-700 
                              dark:bg-indigo-900 dark:text-indigo-300 
                              rounded-full">
                    ${attr.name}: ${attr.value}
                </span>
            `;
        });
    }

    const html = `
    <div class="space-y-8 text-gray-800 dark:text-gray-100">

        <div class="flex gap-8">

            <div>
                ${product.image 
                    ? `<img src="/storage/${product.image}" 
                        class="w-36 h-36 object-cover rounded-2xl shadow-md">`
                    : `<div class="w-36 h-36 bg-gray-200 dark:bg-gray-700 
                                rounded-2xl flex items-center justify-center
                                text-gray-500 dark:text-gray-300 font-medium">
                            No Image
                    </div>`
                }
            </div>

            <div class="flex-1">

                <h2 class="text-3xl font-bold tracking-wide 
                        text-gray-900 dark:text-white">
                    ${product.name}
                </h2>

                <p class="text-sm mt-1 font-medium 
                        text-gray-600 dark:text-gray-400">
                    SKU: ${product.sku}
                </p>

                <div class="mt-6 space-y-2 text-sm leading-relaxed">

                    <p><span class="font-semibold text-gray-700 dark:text-gray-300">Harga Beli:</span> 
                    <span class="font-medium">Rp ${formatNumber(product.purchase_price)}</span></p>

                    <p><span class="font-semibold text-gray-700 dark:text-gray-300">Harga Jual:</span> 
                    <span class="font-medium">Rp ${formatNumber(product.selling_price)}</span></p>

                    <p><span class="font-semibold text-gray-700 dark:text-gray-300">Stok:</span> 
                    <span class="font-bold">${product.stock}</span></p>

                    <p><span class="font-semibold text-gray-700 dark:text-gray-300">Minimum Stok:</span> 
                    ${product.minimum_stock}</p>

                    <p><span class="font-semibold text-gray-700 dark:text-gray-300">Kategori:</span> 
                    ${product.category?.name ?? '-'}</p>

                    <p><span class="font-semibold text-gray-700 dark:text-gray-300">Supplier:</span> 
                    ${product.supplier?.name ?? '-'}</p>

                </div>

            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold mb-3 
                    text-gray-900 dark:text-white">
                Atribut Produk
            </h3>

            <div class="flex flex-wrap gap-2">
                ${attributesHtml || '<span class="text-gray-500 dark:text-gray-400">Tidak ada atribut</span>'}
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold mb-3 
                    text-gray-900 dark:text-white">
                Deskripsi
            </h3>

            <p class="text-sm leading-relaxed 
                    text-gray-700 dark:text-gray-300">
                ${product.description ?? '-'}
            </p>
        </div>

    </div>
    `;

    document.getElementById('detailContent').innerHTML = html;

    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

</script>

@endsection
