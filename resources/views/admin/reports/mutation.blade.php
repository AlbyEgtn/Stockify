@extends('layouts.admin')

@section('title','Laporan Stok')
@section('page-title','Laporan Stok Gudang')

@section('content')

<div class="max-w-7xl mx-auto space-y-8 text-gray-800 dark:text-gray-200">

{{-- ================= SUMMARY ================= --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">

    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
        <p class="text-xs text-gray-500">Produk Kritis</p>
        <h3 class="text-2xl font-bold text-red-600 mt-2">
            {{ $criticalProducts->count() }}
        </h3>
    </div>

    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
        <p class="text-xs text-gray-500">Produk Warning</p>
        <h3 class="text-2xl font-bold text-yellow-500 mt-2">
            {{ $warningProducts->count() }}
        </h3>
    </div>

    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
        <p class="text-xs text-gray-500">Produk Aman</p>
        <h3 class="text-2xl font-bold text-emerald-600 mt-2">
            {{ $safeProducts->count() }}
        </h3>
    </div>

    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow border">
        <p class="text-xs text-gray-500">Total Nilai Stok</p>
        <h3 class="text-xl font-bold text-indigo-600 mt-2">
            Rp {{ number_format($totalStockValue,0,',','.') }}
        </h3>
    </div>

</div>


{{-- ================= FILTER ================= --}}
<div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow border">

<form method="GET" class="grid md:grid-cols-4 gap-4 text-sm">

    <div>
        <label class="text-xs font-semibold">Kategori</label>

        <select name="category_id"
            class="w-full mt-1 px-3 py-2 rounded-lg border dark:bg-gray-800">

            <option value="">Semua</option>

            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach

        </select>
    </div>

    <div class="flex items-end gap-2">

        <button type="submit"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
            Filter
        </button>

        <a href="{{ route('admin.reports.mutation') }}"
           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">
           Reset
        </a>

    </div>

</form>

</div>


{{-- ================= TABLE STOK ================= --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl shadow border overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase">

<tr>
    <th class="px-5 py-3 text-left">Produk</th>
    <th>Kategori</th>
    <th>Stok</th>
    <th>Minimum</th>
    <th>Status</th>
    <th>Nilai Stok</th>
</tr>

</thead>

<tbody class="divide-y dark:divide-gray-800">

@forelse($products as $product)

@php
    if($product->stock <= $product->minimum_stock){
        $status = 'Kritis';
        $color  = 'bg-red-100 text-red-600';
    }
    elseif($product->stock <= ($product->minimum_stock * 2)){
        $status = 'Warning';
        $color  = 'bg-yellow-100 text-yellow-600';
    }
    else{
        $status = 'Aman';
        $color  = 'bg-emerald-100 text-emerald-600';
    }

    $stockValue = $product->stock * $product->purchase_price;
@endphp

<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">

    <td class="px-5 py-4 font-semibold">
        {{ $product->name }}
    </td>

    <td>
        {{ $product->category->name ?? '-' }}
    </td>

    <td class="font-bold">
        {{ $product->stock }}
    </td>

    <td>
        {{ $product->minimum_stock }}
    </td>

    <td>
        <span class="px-2 py-1 text-xs rounded-full {{ $color }}">
            {{ $status }}   
        </span>
    </td>

    <td class="font-semibold text-indigo-600">
        Rp {{ number_format($stockValue,0,',','.') }}
    </td>

</tr>

@empty

<tr>
    <td colspan="6" class="text-center py-8 text-gray-400">
        Tidak ada data produk
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

@endsection