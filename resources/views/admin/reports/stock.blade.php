@extends('layouts.admin')

@section('title','Laporan Stok')
@section('page-title','Laporan Stok Barang')

@section('content')

<div class="max-w-7xl mx-auto space-y-10">




    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Total Produk -->
        <div class="bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700
                    rounded-2xl p-4 shadow-sm">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Total Produk
                    </p>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mt-1">
                        {{ $allProducts->count() }}
                    </h2>
                </div>

                <div class="bg-indigo-100 text-indigo-600 
                            dark:bg-indigo-900 dark:text-indigo-300
                            p-2 rounded-lg text-sm">
                    
                </div>
            </div>
        </div>

        <!-- Stok Rendah -->
        <div class="bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700
                    rounded-2xl p-4 shadow-sm">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Produk Stok Rendah
                    </p>
                    <h2 class="text-xl font-semibold text-red-600 mt-1">
                        {{ $lowStockProducts->count() }}
                    </h2>
                </div>

                <div class="bg-red-100 text-red-600 
                            dark:bg-red-900 dark:text-red-300
                            p-2 rounded-lg text-sm">
                    
                </div>
            </div>
        </div>

        <!-- Total Nilai -->
        <div class="bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700
                    rounded-2xl p-4 shadow-sm">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Total Nilai Persediaan
                    </p>
                    <h2 class="text-lg font-semibold text-emerald-600 mt-1">
                        Rp {{ number_format($totalStockValue ?? 0, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-emerald-100 text-emerald-600 
                            dark:bg-emerald-900 dark:text-emerald-300
                            p-2 rounded-lg text-sm">
                    
                </div>
            </div>
        </div>

    </div>
    <div class="bg-white dark:bg-gray-900 
                border border-gray-200 dark:border-gray-700
                p-5 rounded-2xl shadow-sm">

        <form method="GET" action="{{ route('admin.reports.stock') }}" 
            class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end text-sm">

            <!-- Dari Tanggal -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                    Dari Tanggal
                </label>
                <input type="date" name="start_date"
                    value="{{ request('start_date') }}"
                    class="w-full mt-1 px-3 py-2 rounded-lg border 
                        focus:ring-2 focus:ring-indigo-500 
                        text-sm dark:bg-gray-800 dark:text-gray-400">
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                    Sampai Tanggal
                </label>
                <input type="date" name="end_date"
                    value="{{ request('end_date') }}"
                    class="w-full mt-1 px-3 py-2 rounded-lg border 
                        focus:ring-2 focus:ring-indigo-500 
                        text-sm dark:bg-gray-800 dark:text-gray-400">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                    Kategori
                </label>
                <select name="category_id"
                    class="w-full mt-1 px-3 py-2 rounded-lg border 
                        focus:ring-2 focus:ring-indigo-500 
                        text-sm dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                    Status
                </label>
                <select name="status"
                    class="w-full mt-1 px-3 py-2 rounded-lg border 
                        focus:ring-2 focus:ring-indigo-500 
                        text-sm dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua</option>
                    <option value="critical" {{ request('status')=='critical'?'selected':'' }}>Kritis</option>
                    <option value="warning" {{ request('status')=='warning'?'selected':'' }}>Warning</option>
                    <option value="safe" {{ request('status')=='safe'?'selected':'' }}>Aman</option>
                </select>
            </div>

            <!-- Button -->
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-sm font-semibold
                        bg-indigo-600 hover:bg-indigo-700
                        text-white rounded-lg transition">
                    Filter
                </button>

                <a href="{{ route('admin.reports.stock') }}"
                class="px-4 py-2 text-sm font-semibold
                        bg-gray-200 hover:bg-gray-300
                        dark:bg-gray-700 dark:hover:bg-gray-600
                        rounded-lg transition dark:text-gray-400">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <div class="bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-lg">

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
            Laporan Detail Stok Produk
        </h3>

        <div class="overflow-x-auto max-h-[500px] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-xl">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 dark:bg-gray-800 
                    text-gray-600 dark:text-gray-300 
                    uppercase text-xs tracking-wide
                    sticky top-0 z-10">

                    <tr>
                        <th class="px-6 py-4 text-left">Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Min. Stok</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Nilai Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>


                <tbody class="divide-y dark:divide-gray-800">

                @forelse($allProducts as $product)

                    @php
                        $percentage = $product->minimum_stock > 0
                            ? ($product->stock / ($product->minimum_stock * 2)) * 100
                            : 100;

                        $percentage = min($percentage, 100);
                    @endphp

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                        <!-- Nama -->
                        <td class="px-6 py-5 font-semibold text-gray-900 dark:text-white">
                            {{ $product->name }}
                        </td>

                        <!-- Kategori -->
                        <td class="text-gray-600 dark:text-gray-400">
                            {{ optional($product->category)->name ?? '-' }}
                        </td>

                        <!-- Supplier -->
                        <td class="text-gray-600 dark:text-gray-400">
                            {{ optional($product->supplier)->name ?? '-' }}
                        </td>
                                                <td class="font-semibold text-gray-700 dark:text-gray-300">
                            {{ $product->minimum_stock }}
                        </td>
                        <!-- Stok -->
                        <td class="font-bold text-gray-800 dark:text-gray-200">
                            {{ $product->stock }}
                        </td>


                        <!-- Status -->
                        <td>
                            @if($product->stock <= $product->minimum_stock)
                                <span class="px-3 py-1 text-xs font-semibold 
                                             bg-red-100 text-red-600
                                             dark:bg-red-900 dark:text-red-300
                                             rounded-full">
                                    Kritis
                                </span>
                            @elseif($product->stock <= $product->minimum_stock * 2)
                                <span class="px-3 py-1 text-xs font-semibold 
                                             bg-yellow-100 text-yellow-600
                                             dark:bg-yellow-900 dark:text-yellow-300
                                             rounded-full">
                                    Warning
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold 
                                             bg-green-100 text-green-600
                                             dark:bg-green-900 dark:text-green-300
                                             rounded-full">
                                    Aman
                                </span>
                            @endif
                        </td>

                        <!-- Nilai Stok -->
                        <td class="font-semibold text-indigo-600">
                            Rp {{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <button onclick="openModal({{ $product->id }})"
                                class="px-4 py-2 text-xs font-semibold
                                    bg-indigo-600 hover:bg-indigo-700
                                    text-white rounded-lg transition">
                                Detail
                            </button>
                        </td>

                    </tr>

                    <!-- Progress Bar -->
                    <tr>
                        <td colspan="6" class="px-6 pb-4">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full 
                                    {{ $product->stock <= $product->minimum_stock ? 'bg-red-500'
                                      : ($product->stock <= $product->minimum_stock * 2 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data produk.
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>
                <!-- MODAL DETAIL -->
                <div id="productModal"
                    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

                    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 w-full max-w-lg relative">

                        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
                            Detail Produk
                        </h3>

                        <div id="modalContent"
                            class="space-y-3 text-sm text-gray-700 dark:text-gray-200">
                        </div>

                        <div class="mt-6 text-right">
                            <button onclick="closeModal()"
                                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700
                                        rounded-lg text-sm">
                                Tutup
                            </button>
                        </div>

                    </div>
                </div>

        </div>

    </div>

</div>
<script>
const products = @json($allProducts);

function openModal(id) {

    const product = products.find(p => p.id == id);

    if (!product) return;

    const html = `
        <div class="grid grid-cols-2 gap-3">

            <div><strong>Nama:</strong></div>
            <div>${product.name}</div>

            <div><strong>SKU:</strong></div>
            <div>${product.sku}</div>

            <div><strong>Kategori:</strong></div>
            <div>${product.category ? product.category.name : '-'}</div>

            <div><strong>Supplier:</strong></div>
            <div>${product.supplier ? product.supplier.name : '-'}</div>

            <div><strong>Stok:</strong></div>
            <div>${product.stock}</div>

            <div><strong>Minimum Stok:</strong></div>
            <div>${product.minimum_stock}</div>

            <div><strong>Harga Beli:</strong></div>
            <div>Rp ${Number(product.purchase_price).toLocaleString('id-ID')}</div>

            <div><strong>Harga Jual:</strong></div>
            <div>Rp ${Number(product.selling_price).toLocaleString('id-ID')}</div>

            <div><strong>Nilai Persediaan:</strong></div>
            <div class="font-semibold text-indigo-600">
                Rp ${(product.stock * product.purchase_price).toLocaleString('id-ID')}
            </div>

        </div>
    `;

    document.getElementById('modalContent').innerHTML = html;

    const modal = document.getElementById('productModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('productModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection
