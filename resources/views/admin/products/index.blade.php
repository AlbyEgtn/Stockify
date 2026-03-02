@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')

@section('content')

<div class="space-y-6">
    
    {{-- ===================== --}}
    {{-- STATISTIK --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Produk</p>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ $totalProducts }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Stok Rendah</p>
            <h3 class="text-2xl font-bold text-red-500">
                {{ $lowStockProducts }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Kategori</p>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ $totalCategories }}
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500">Supplier</p>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ $totalSuppliers }}
            </h3>
        </div>

    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">

        <!-- Tombol Tambah -->
        <a href="{{ route('admin.products.create') }}"
        class="px-4 py-2 text-sm font-medium
                bg-blue-600 text-white rounded-lg
                hover:bg-blue-700 transition inline-block text-center">
            + Tambah Produk
        </a>

        <!-- Search -->
        <form method="GET"
            action="{{ route('admin.products.index') }}"
            class="flex items-center gap-2 w-full sm:w-auto">

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari produk..."
                class="w-full sm:w-64 px-3 py-2 text-sm
                        border border-gray-300 dark:border-gray-700
                        rounded-lg
                        dark:bg-gray-800 dark:text-gray-200">

            <button type="submit"
                    class="px-3 py-2 text-sm
                        bg-gray-200 dark:bg-gray-700
                        rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                Cari
            </button>

        </form>

    </div>


    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

        <table class="w-full text-sm">
            
            {{-- HEADER --}}
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                <tr>
                    <th class="px-5 py-3 text-center font-semibold tracking-wide">
                        Gambar
                    </th>
                    <th class="px-5 py-3 text-left font-semibold tracking-wide">
                        Nama
                    </th>

                    <th class="px-5 py-3 text-left font-semibold tracking-wide">
                        Kategori
                    </th>

                    <th class="px-5 py-3 text-left font-semibold tracking-wide">
                        Supplier
                    </th>

                    <th class="px-5 py-3 text-left font-semibold tracking-wide">
                        Harga
                    </th>

                    <th class="px-5 py-3 text-center font-semibold tracking-wide">
                        Stok
                    </th>

                    <th class="px-5 py-3 text-center font-semibold tracking-wide">
                        Min
                    </th>

                    <th class="px-5 py-3 text-left font-semibold tracking-wide">
                        Atribut
                    </th>

                    <th class="px-5 py-3 text-left font-semibold tracking-wide">
                        Status
                    </th>

                    <th class="px-5 py-3 text-center font-semibold tracking-wide min-w-[150px]">
                        Aksi
                    </th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">

                @forelse($products as $product)
                    <tr class="hover:bg-indigo-50 dark:hover:bg-gray-800 transition">
                        
                        <td class="px-5 py-3 text-center">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="w-12 h-12 object-cover rounded-xl shadow">
                            @else
                                <div class="w-12 h-12 object-cover rounded-2xl ring-2 ring-indigo-200 dark:ring-indigo-700 shadow-sm">
                                    -
                                </div>
                            @endif
                        </td>

                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">
                            {{ $product->name }}
                        </td>

                        <td class="px-5 py-3 dark:text-gray-400">
                            {{ $product->category->name ?? '-' }}
                        </td>

                        <td class="px-5 py-3 dark:text-gray-400">
                            {{ $product->supplier->name ?? '-' }}
                        </td>

                        <td class="px-5 py-3 font-semibold text-emerald-600">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-3 text-center">
                            @if($product->stock <= $product->minimum_stock)
                                <span class="px-3 py-1 text-xs rounded-full
                                            bg-red-100 text-red-600
                                            dark:bg-red-900/30 dark:text-red-400">
                                    {{ $product->stock }}
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full
                                            bg-emerald-100 text-emerald-600
                                            dark:bg-emerald-900/30 dark:text-emerald-400">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-3 text-center">
                            <span class="px-3 py-1 text-xs rounded-full
                                        bg-gray-200 text-gray-700
                                        dark:bg-gray-700 dark:text-gray-200">
                                {{ $product->minimum_stock }}
                            </span>
                        </td>

                        <td class="px-5 py-3">
                            @if($product->productAttributes->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->productAttributes as $attribute)
                                        <span class="px-2 py-1 text-xs rounded-full
                                                    bg-indigo-100 text-indigo-600
                                                    dark:bg-indigo-900/30 dark:text-indigo-400">
                                            {{ $attribute->name }}: {{ $attribute->value }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-xs italic">
                                    -
                                </span>
                            @endif
                        </td>
                        
                        <td class="px-5 py-3 text-center"
                            x-data="{ 
                                status: '{{ $product->status }}',
                                toggle() {
                                    fetch('{{ route('admin.products.toggle-status', $product) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        this.status = data.status
                                    })
                                }
                            }">

                            <button @click="toggle"
                                :class="status === 'active' 
                                    ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' 
                                    : 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400'"
                                class="px-4 py-1.5 text-xs font-semibold rounded-full transition-all duration-200 hover:scale-105">

                                <span x-text="status === 'active' ? 'ACTIVE' : 'DRAFT'"></span>

                            </button>

                        </td>

                        <td class="px-5 py-3">
                            <div class="flex flex-wrap justify-center gap-2">

                                <a href="{{ route('admin.products.edit',$product) }}"
                                class="px-3 py-1 text-xs rounded-lg
                                        bg-blue-100 text-blue-600
                                        hover:bg-blue-200 transition whitespace-nowrap">
                                    Edit
                                </a>

                                <form method="POST"
                                    action="{{ route('admin.products.destroy',$product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus produk?')"
                                            class="px-3 py-1 text-xs rounded-lg
                                                bg-red-100 text-red-600
                                                hover:bg-red-200 transition whitespace-nowrap">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9"
                            class="px-5 py-6 text-center text-gray-500 dark:text-gray-400">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>
    <div class="mt-6">
        {{ $products->links() }}
    </div>


</div>

@endsection
