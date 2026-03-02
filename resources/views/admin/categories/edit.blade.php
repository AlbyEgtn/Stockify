@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori Produk')

@section('content')

<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow p-6">

        <form action="{{ route('admin.categories.update', $category->id) }}"
              method="POST"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Kategori --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Nama Kategori
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $category->name) }}"
                       class="w-full border rounded-lg px-3 py-2
                              dark:bg-gray-900 dark:text-white
                              focus:ring-2 focus:ring-blue-500"
                       required>

                @error('name')
                    <p class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Deskripsi
                </label>

                <textarea name="description"
                          rows="4"
                          class="w-full border rounded-lg px-3 py-2
                                 dark:bg-gray-900 dark:text-white
                                 focus:ring-2 focus:ring-blue-500">{{ old('description', $category->description) }}</textarea>

                @error('description')
                    <p class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Action --}}
            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">

                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 rounded-lg border
                          dark:border-gray-600
                          text-gray-700 dark:text-gray-300">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg
                               hover:bg-blue-700 transition">
                    Update
                </button>

            </div>

        </form>

    </div>
</div>

@endsection
