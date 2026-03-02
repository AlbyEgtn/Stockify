@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')

<div class="max-w-5xl mx-auto px-4">

<div class="bg-white dark:bg-gray-900
            border border-gray-200 dark:border-gray-700
            rounded-2xl shadow-xl overflow-hidden">

    {{-- HEADER --}}
    <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-purple-600">
        <h2 class="text-xl font-semibold text-white">
            Tambah Produk Baru
        </h2>
        <p class="text-sm text-indigo-100 mt-1">
            Masukkan informasi lengkap produk untuk manajemen stok.
        </p>
    </div>

    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="p-8 space-y-10"
          x-data="{ preview: null }">
        @csrf

        @php
            $inputClass = "w-full px-4 py-2 text-sm rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-800
                           text-gray-800 dark:text-gray-100
                           placeholder-gray-400 dark:placeholder-gray-500
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500 transition";
            $labelClass = "block text-sm font-semibold mb-2
                           text-gray-700 dark:text-gray-200";
            $sectionTitle = "text-xs font-bold uppercase tracking-wider
                             text-gray-500 dark:text-gray-400 mb-4";
        @endphp

        {{-- ================= INFORMASI DASAR ================= --}}
        <div>
            <h3 class="{{ $sectionTitle }}">Informasi Dasar</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="{{ $labelClass }}">Nama Produk</label>
                    <input type="text" name="name" class="{{ $inputClass }}" required>
                </div>

                <div>
                    <label class="{{ $labelClass }}">SKU</label>
                    <input type="text" name="sku" class="{{ $inputClass }}" required>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Kategori</label>
                    <select name="category_id" class="{{ $inputClass }}" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Supplier</label>
                    <select name="supplier_id" class="{{ $inputClass }}" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        {{-- ================= STOK & HARGA ================= --}}
        <div>
            <h3 class="{{ $sectionTitle }}">Stok & Harga</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <label class="{{ $labelClass }}">Jumlah Stok</label>
                    <input type="number" name="stock" class="{{ $inputClass }}" required>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Harga Beli</label>
                    <input type="text"
                        name="purchase_price"
                        x-data
                        x-on:input="$el.value = formatRupiah($el.value)"
                        class="{{ $inputClass }}"
                        required>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Harga Jual</label>
                    <input type="text"
                        name="selling_price"
                        x-data
                        x-on:input="$el.value = formatRupiah($el.value)"
                        class="{{ $inputClass }}"
                        required>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Minimum Stok</label>
                    <input type="number" name="minimum_stock" class="{{ $inputClass }}" required>
                </div>

            </div>
        </div>

        {{-- ================= ATRIBUT ================= --}}
        <div x-data="{
                attributes: [{ name: '', value: '' }],
                add() { this.attributes.push({ name: '', value: '' }) },
                remove(index) { this.attributes.splice(index, 1) }
            }">

            <h3 class="{{ $sectionTitle }}">Atribut Produk</h3>

            <template x-for="(attribute, index) in attributes" :key="index">
                <div class="flex gap-3 mb-3">

                    <input type="text"
                           :name="'attributes[' + index + '][name]'"
                           x-model="attribute.name"
                           placeholder="Nama (Warna, Ukuran)"
                           class="{{ $inputClass }}">

                    <input type="text"
                           :name="'attributes[' + index + '][value]'"
                           x-model="attribute.value"
                           placeholder="Nilai (Merah, XL)"
                           class="{{ $inputClass }}">

                    <button type="button"
                            @click="remove(index)"
                            class="px-3 rounded-xl bg-red-500 text-white hover:bg-red-600 transition">
                        ✕
                    </button>

                </div>
            </template>

            <button type="button"
                    @click="add()"
                    class="mt-2 px-4 py-2 text-sm font-medium
                           bg-indigo-600 text-white rounded-xl
                           hover:bg-indigo-700 transition">
                + Tambah Atribut
            </button>
        </div>

        {{-- ================= GAMBAR ================= --}}
        <div>
            <h3 class="{{ $sectionTitle }}">Media Produk</h3>

            <div class="flex items-center gap-6">

                <div class="w-28 h-28 rounded-2xl border
                            border-gray-300 dark:border-gray-600
                            flex items-center justify-center
                            overflow-hidden
                            bg-gray-50 dark:bg-gray-800">

                    <template x-if="preview">
                        <img :src="preview" class="object-cover w-full h-full">
                    </template>

                    <template x-if="!preview">
                        <span class="text-xs text-gray-400 dark:text-gray-500">
                            Preview
                        </span>
                    </template>
                </div>

                <input type="file"
                       name="image"
                       @change="preview = URL.createObjectURL($event.target.files[0])"
                       class="text-sm text-gray-700 dark:text-gray-200">
            </div>
        </div>

        {{-- DESKRIPSI --}}
        <div>
            <label class="{{ $labelClass }}">
                Deskripsi
                <span class="text-xs font-normal text-gray-400 ml-1">(Optional)</span>
            </label>
            <textarea name="description"
                      rows="4"
                      class="{{ $inputClass }}"></textarea>
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">

            <a href="{{ route('admin.products.index') }}"
               class="px-5 py-2 rounded-xl
                      bg-gray-200 text-gray-800
                      hover:bg-gray-300 transition
                      dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                Batal
            </a>

            <button type="submit"
                    class="px-6 py-2 rounded-xl
                           bg-indigo-600 text-white
                           hover:bg-indigo-700 transition shadow">
                Simpan Produk
            </button>

        </div>

    </form>

</div>
</div>
<script>
function formatRupiah(value) {
    let number_string = value.replace(/[^,\d]/g, '').toString();
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}
</script>
@endsection
