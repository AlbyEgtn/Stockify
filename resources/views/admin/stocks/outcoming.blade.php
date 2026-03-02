<x-app-layout>

    <x-slot name="header">
        Transaksi Barang Masuk
    </x-slot>

    <div class="bg-white dark:bg-gray-800 rounded shadow p-6 max-w-xl">

        <form>
            <div class="mb-4">
                <label class="block text-sm mb-1">Produk</label>
                <select class="w-full border rounded px-3 py-2">
                    <option>Laptop Asus</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Jumlah</label>
                <input type="number" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Catatan</label>
                <textarea class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <a href="#" class="px-4 py-2 border rounded">Batal</a>
                <button class="px-4 py-2 bg-green-600 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>

    </div>

</x-app-layout>
