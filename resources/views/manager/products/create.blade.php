@extends('layouts.manager')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')

<div class="flex justify-center">
<div class="w-full max-w-4xl bg-white/5 backdrop-blur 
            border border-white/10 rounded-3xl shadow-xl p-8">

    <form action="{{ route('manager.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        {{-- Nama Produk --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Nama Produk
            </label>
            <input type="text" name="name"
                   class="w-full rounded-xl bg-white/10 border border-white/20
                          text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                   required>
        </div>

        {{-- SKU --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                SKU
            </label>
            <input type="text" name="sku"
                   class="w-full rounded-xl bg-white/10 border border-white/20
                          text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                   required>
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Kategori
            </label>
            <select name="category_id"
                    class="w-full rounded-xl bg-white/10 border border-white/20
                           text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                    required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Supplier --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Supplier
            </label>
            <select name="supplier_id"
                    class="w-full rounded-xl bg-white/10 border border-white/20
                           text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                    required>
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Stok --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Jumlah Stok
            </label>
            <input type="number" name="stock"
                   class="w-full rounded-xl bg-white/10 border border-white/20
                          text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                   required>
        </div>

        {{-- Harga --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-300 mb-1">
                    Harga Beli
                </label>
                <input type="number" name="purchase_price"
                       class="w-full rounded-xl bg-white/10 border border-white/20
                              text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div>
                <label class="block text-sm text-gray-300 mb-1">
                    Harga Jual
                </label>
                <input type="number" name="selling_price"
                       class="w-full rounded-xl bg-white/10 border border-white/20
                              text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>
        </div>

        {{-- Minimum Stok --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Minimum Stok
            </label>
            <input type="number" name="minimum_stock"
                   class="w-full rounded-xl bg-white/10 border border-white/20
                          text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                   required>
        </div>

        {{-- Gambar --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Gambar Produk
            </label>
            <input type="file" name="image"
                   class="block w-full text-sm text-gray-300">
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Deskripsi
            </label>
            <textarea name="description" rows="4"
                      class="w-full rounded-xl bg-white/10 border border-white/20
                             text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        {{-- ATRIBUT PRODUK --}}
        <div x-data="attributesHandler()" class="pt-4">
            <label class="block text-sm text-gray-300 mb-2">
                Atribut Produk
            </label>

            <template x-for="(attr, index) in attributes" :key="index">
                <div class="flex gap-2 mb-2">

                    <input type="text"
                           :name="'attributes[' + index + '][name]'"
                           x-model="attr.name"
                           placeholder="Nama (Warna, Ukuran)"
                           class="w-1/2 rounded-xl bg-white/10 border border-white/20
                                  text-white px-3 py-2">

                    <input type="text"
                           :name="'attributes[' + index + '][value]'"
                           x-model="attr.value"
                           placeholder="Nilai (Merah, XL)"
                           class="w-1/2 rounded-xl bg-white/10 border border-white/20
                                  text-white px-3 py-2">

                    <button type="button"
                            @click="removeField(index)"
                            class="px-3 bg-red-600 text-white rounded-xl">
                        ✕
                    </button>

                </div>
            </template>

            <button type="button"
                    @click="addField"
                    class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-xl">
                + Tambah Atribut
            </button>
        </div>

        {{-- Action --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-white/10">
            <a href="{{ route('manager.products.index') }}"
               class="px-5 py-2 rounded-xl bg-gray-700 text-gray-300">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r
                           from-indigo-600 to-purple-600 text-white">
                Simpan Produk
            </button>
        </div>

    </form>

</div>
</div>

{{-- Alpine Handler --}}
<script>
function attributesHandler() {
    return {
        attributes: [
            { name: '', value: '' }
        ],
        addField() {
            this.attributes.push({ name: '', value: '' });
        },
        removeField(index) {
            this.attributes.splice(index, 1);
        }
    }
}
</script>

@endsection
