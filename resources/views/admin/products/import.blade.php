@extends('layouts.admin')

@section('title','Import & Export Produk')
@section('page-title','Import & Export Produk')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <div class="bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700
                    rounded-2xl shadow-xl overflow-hidden flex flex-col">

            <div class="px-8 py-6 bg-gradient-to-r from-green-600 to-emerald-600">
                <h2 class="text-xl font-semibold text-white">
                    Export Data Produk
                </h2>
                <p class="text-sm text-green-100 mt-1">
                    Download data produk dalam format siap import.
                </p>
            </div>

            <div class="p-8 flex flex-col justify-between flex-1">

                <div class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    File export akan berisi seluruh data produk lengkap
                    dengan kategori, supplier, stok, dan minimum stok.
                </div>

                <div class="mt-8">
                    <a href="{{ route('admin.products.export') }}"
                       class="w-full inline-block text-center px-6 py-3 rounded-xl
                              bg-green-600 text-white font-medium
                              hover:bg-green-700 transition shadow-lg">
                        Download Export
                    </a>
                </div>

            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700
                    rounded-2xl shadow-xl overflow-hidden flex flex-col">

            <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-purple-600">
                <h2 class="text-xl font-semibold text-white">
                    Import Data Produk
                </h2>
                <p class="text-sm text-indigo-100 mt-1">
                    Upload file CSV atau Excel sesuai template resmi.
                </p>
            </div>

            <div class="p-8 flex-1">

                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 rounded-xl bg-red-100 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.products.import') }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="space-y-6">

                    @csrf

                    <div>
                        <label class="block text-sm font-semibold mb-2 
                                       text-gray-700 dark:text-gray-200">
                            File Import
                        </label>

                        <input type="file" 
                               name="file"
                               required
                               class="w-full px-4 py-3 text-sm rounded-xl
                                      border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-800
                                      text-gray-800 dark:text-gray-100
                                      focus:ring-2 focus:ring-indigo-500
                                      focus:border-indigo-500 transition">
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full px-6 py-3 rounded-xl
                                       bg-indigo-600 text-white font-medium
                                       hover:bg-indigo-700 transition shadow-lg">
                            Import Sekarang
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection