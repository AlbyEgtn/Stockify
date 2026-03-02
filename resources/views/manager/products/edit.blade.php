<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">
            Edit Produk
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-6">
            <div class="bg-white/5 backdrop-blur border border-white/10 
                        rounded-3xl shadow-xl p-6">

                <form action="{{ route('manager.products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="text" name="name"
                           value="{{ $product->name }}"
                           class="w-full rounded-xl mb-4">

                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-xl">
                        Update
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
