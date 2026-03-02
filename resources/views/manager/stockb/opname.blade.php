@extends('layouts.manager')

@section('title','Stock Opname')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<div class="flex justify-center">
    <div class="w-full max-w-3xl space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-md">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                Stock Opname Gudang
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Sesuaikan stok sistem berdasarkan hasil perhitungan fisik.
            </p>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-lg">

            <form method="POST"
                  action="{{ route('manager.stock.opname') }}"
                  onsubmit="return confirmSubmit()"
                  class="space-y-6">

                @csrf

                {{-- PRODUK --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Produk
                    </label>

                    <select name="product_id"
                            id="productSelect"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border
                                focus:ring-2 focus:ring-indigo-500
                                bg-white dark:bg-gray-800
                                border-gray-300 dark:border-gray-700
                                text-gray-800 dark:text-gray-100">

                        <option value="">-- Pilih Produk --</option>

                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                    data-stock="{{ $product->stock }}">
                                {{ $product->name }} (Stok: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- GRID STOCK --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Stok Sistem
                        </label>
                        <input type="number"
                               id="systemStock"
                               readonly
                               class="w-full px-4 py-2.5 rounded-xl
                                      bg-gray-100 dark:bg-gray-800
                                      text-gray-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Stok Fisik
                        </label>
                        <input type="number"
                               name="real_stock"
                               id="realStock"
                               min="0"
                               required
                               class="w-full px-4 py-2.5 rounded-xl
                                      border focus:ring-2 focus:ring-indigo-500
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Selisih
                        </label>
                        <input type="text"
                               id="difference"
                               readonly
                               class="w-full px-4 py-2.5 rounded-xl
                                      bg-gray-100 dark:bg-gray-800
                                      font-semibold text-gray-900 dark:text-white">
                    </div>

                </div>

                <div id="previewBox"
                     class="hidden p-3 rounded-xl text-sm font-medium">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Keterangan
                    </label>
                    <textarea name="note"
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-xl
                                     border focus:ring-2 focus:ring-indigo-500
                                     dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                              placeholder="Contoh: Selisih karena barang rusak"></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            id="submitBtn"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700
                                   text-white font-semibold rounded-xl transition">
                        Simpan Opname
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const systemStock = document.getElementById('systemStock');
    const realStock   = document.getElementById('realStock');
    const difference  = document.getElementById('difference');
    const previewBox  = document.getElementById('previewBox');
    const submitBtn   = document.getElementById('submitBtn');

    // INIT TOM SELECT (HANYA SEKALI)
    const select = new TomSelect("#productSelect", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        onChange: function(value) {

            const option = this.options[value];
            const stock  = option ? option.stock : 0;

            systemStock.value = stock || 0;
            calculate();
        }
    });

    // Simpan data-stock ke TomSelect option
    select.on('initialize', function() {
        const original = document.getElementById('productSelect');

        [...original.options].forEach(opt => {
            if(opt.value){
                select.options[opt.value].stock = opt.dataset.stock;
            }
        });
    });

    function calculate() {

        const system = parseInt(systemStock.value) || 0;
        const real   = parseInt(realStock.value) || 0;
        const diff   = real - system;

        difference.value = diff > 0 ? '+' + diff : diff;

        previewBox.className = "p-3 rounded-xl text-sm font-medium";

        if(diff > 0){
            previewBox.innerHTML =
                `Stok akan bertambah sebanyak <strong>${diff}</strong> unit.`;
            previewBox.classList.add(
                'bg-green-100','text-green-700',
                'dark:bg-green-900/40','dark:text-green-300'
            );
            previewBox.classList.remove('hidden');
            submitBtn.disabled = false;
        }
        else if(diff < 0){
            previewBox.innerHTML =
                `Stok akan berkurang sebanyak <strong>${Math.abs(diff)}</strong> unit.`;
            previewBox.classList.add(
                'bg-red-100','text-red-700',
                'dark:bg-red-900/40','dark:text-red-300'
            );
            previewBox.classList.remove('hidden');
            submitBtn.disabled = false;
        }
        else {
            previewBox.innerHTML = "Tidak ada perubahan stok.";
            previewBox.classList.add(
                'bg-gray-100','text-gray-700',
                'dark:bg-gray-800','dark:text-gray-300'
            );
            previewBox.classList.remove('hidden');
            submitBtn.disabled = true;
        }
    }

    realStock.addEventListener('input', calculate);

});

function confirmSubmit(){
    return confirm("Yakin ingin menyimpan penyesuaian stok ini?");
}
</script>

@endsection