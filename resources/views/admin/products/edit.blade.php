@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')

<div class="flex justify-center px-4">
<div class="w-full max-w-3xl
            bg-white dark:bg-gray-900
            border border-gray-200 dark:border-gray-700
            rounded-2xl shadow-lg p-8">

<form action="{{ route('admin.products.update', $product->id) }}"
      method="POST"
      enctype="multipart/form-data"
      class="space-y-6">

    @csrf
    @method('PUT')

    @php
        $inputClass = "w-full px-3 py-2 text-sm rounded-lg
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-800
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-indigo-500
                       focus:border-indigo-500 transition";
    @endphp

    {{-- ================= BASIC INFO ================= --}}
    <div>
        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
            Nama Produk
        </label>
        <input type="text" name="name"
               value="{{ old('name', $product->name) }}"
               class="{{ $inputClass }}" required>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
            SKU
        </label>
        <input type="text" name="sku"
               value="{{ old('sku', $product->sku) }}"
               class="{{ $inputClass }}" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Kategori
            </label>
            <select name="category_id" class="{{ $inputClass }}" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected($product->category_id == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Supplier
            </label>
            <select name="supplier_id" class="{{ $inputClass }}" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}"
                        @selected($product->supplier_id == $supplier->id)>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- ================= PRICE ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Harga Beli
            </label>
            <input type="number" name="purchase_price"
                   value="{{ old('purchase_price', $product->purchase_price) }}"
                   class="{{ $inputClass }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Harga Jual
            </label>
            <input type="number" name="selling_price"
                   value="{{ old('selling_price', $product->selling_price) }}"
                   class="{{ $inputClass }}" required>
        </div>

    </div>

    {{-- ================= STOCK ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Jumlah Stok
            </label>
            <input type="number"
                   name="stock"
                   value="{{ old('stock', $product->stock) }}"
                   class="{{ $inputClass }}">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Minimum Stok
            </label>
            <input type="number"
                   name="minimum_stock"
                   value="{{ old('minimum_stock', $product->minimum_stock) }}"
                   class="{{ $inputClass }}" required>
        </div>

    </div>

    {{-- ================= ATRIBUT PRODUK ================= --}}
    <div
        x-data="productAttributesComponent()"
        x-init="init({{ $product->productAttributes->map(fn($a)=>['name'=>$a->name,'value'=>$a->value])->values() }})"
        class="mt-6 p-4 rounded-xl
               bg-gray-50 dark:bg-gray-800
               border border-gray-200 dark:border-gray-700"
    >

        <label class="block text-sm font-medium mb-3 text-gray-700 dark:text-gray-300">
            Atribut Produk
        </label>

        <template x-for="(attr, index) in attributes" :key="index">
            <div class="flex gap-2 mb-3">

                <input type="text"
                       :name="'attributes['+index+'][name]'"
                       x-model="attr.name"
                       placeholder="Nama"
                       class="{{ $inputClass }} w-1/2">

                <input type="text"
                       :name="'attributes['+index+'][value]'"
                       x-model="attr.value"
                       placeholder="Nilai"
                       class="{{ $inputClass }} w-1/2">

                <button type="button"
                        @click="remove(index)"
                        class="px-3 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    ✕
                </button>

            </div>
        </template>

        <button type="button"
                @click="add()"
                class="mt-3 px-4 py-2 text-sm font-medium
                       bg-indigo-600 text-white rounded-lg
                       hover:bg-indigo-700 transition">
            + Tambah Atribut
        </button>

    </div>

    {{-- ================= GAMBAR ================= --}}
    <div>
        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
            Gambar Produk
        </label>

        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}"
                 class="w-24 h-24 object-cover rounded-lg mb-3 border
                        border-gray-200 dark:border-gray-700">
        @endif

        <input type="file" name="image"
               class="w-full text-sm text-gray-700 dark:text-gray-300
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-lg file:border-0
                      file:text-sm file:font-medium
                      file:bg-indigo-600 file:text-white
                      hover:file:bg-indigo-700">
    </div>

    {{-- ================= DESKRIPSI ================= --}}
    <div>
        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
            Deskripsi
        </label>
        <textarea name="description" rows="4"
                  class="{{ $inputClass }}">{{ old('description', $product->description) }}</textarea>
    </div>

    {{-- ================= ACTION ================= --}}
    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">

        <a href="{{ route('admin.products.index') }}"
           class="px-4 py-2 text-sm font-medium
                  bg-gray-200 dark:bg-gray-700
                  text-gray-800 dark:text-gray-200
                  rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            Batal
        </a>

        <button type="submit"
                class="px-4 py-2 text-sm font-medium
                       bg-blue-600 text-white
                       rounded-lg hover:bg-blue-700 transition">
            Simpan Perubahan
        </button>

    </div>

</form>
</div>
</div>

{{-- Alpine Component --}}
<script>
function productAttributesComponent() {
    return {
        attributes: [],

        init(initialData) {
            if (initialData && initialData.length > 0) {
                this.attributes = initialData
            } else {
                this.attributes = [{ name:'', value:'' }]
            }
        },

        add() {
            this.attributes.push({ name:'', value:'' })
        },

        remove(index) {
            this.attributes.splice(index,1)

            if (this.attributes.length === 0) {
                this.attributes.push({ name:'', value:'' })
            }
        }
    }
}
</script>

@endsection