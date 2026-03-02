@extends('layouts.admin')

@section('title', 'Kategori Produk')
@section('page-title', 'Kategori Produk')

@section('content')

<div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            Daftar Kategori
        </h3>

        <a href="{{ route('admin.categories.create') }}"
           class="px-4 py-2 text-sm font-medium
                  bg-blue-600 text-white rounded-lg
                  hover:bg-blue-700 transition">
            + Tambah Kategori
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm text-left">

            <thead
                class="bg-gray-50 dark:bg-gray-800
                       text-gray-700 dark:text-gray-200
                       uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-4 py-3 w-12">No</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3 text-center min-w-[130px]">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="text-gray-700 dark:text-gray-300">

                @forelse($categories as $index => $category)
                    <tr
                        class="border-b border-gray-200 dark:border-gray-700
                               hover:bg-blue-50/40 dark:hover:bg-gray-800 transition">

                        <td class="px-4 py-3 text-center font-medium">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                            {{ $category->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $category->description ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap justify-center gap-2">

                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                class="px-3 py-1 text-xs rounded-lg
                                        bg-blue-100 text-blue-600
                                        hover:bg-blue-200 transition whitespace-nowrap">
                                    Edit
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin hapus kategori?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
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
                        <td colspan="4"
                            class="px-4 py-8 text-center
                                   text-gray-500 dark:text-gray-400">
                            Data kategori belum tersedia
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection
