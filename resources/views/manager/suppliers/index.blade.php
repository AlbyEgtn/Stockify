@extends('layouts.manager')

@section('title','Supplier')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    <!-- ALERT -->
    @if(session('success'))
        <div class="p-4 rounded-2xl 
                    bg-green-100 text-green-800
                    dark:bg-green-900 dark:text-green-300 
                    font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- WRAPPER -->
    <div class="bg-white dark:bg-gray-900 
                rounded-3xl shadow-lg 
                border border-gray-200 dark:border-gray-700">

        <!-- HEADER -->
        <div class="flex justify-between items-center 
                    p-8 border-b border-gray-200 dark:border-gray-700">

            <div>
                <h2 class="text-2xl font-bold tracking-wide 
                           text-gray-900 dark:text-white">
                    Daftar Supplier
                </h2>
                <p class="text-sm mt-1 
                          text-gray-600 dark:text-gray-400">
                    Kelola data supplier untuk kebutuhan pengadaan barang.
                </p>
            </div>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-50 dark:bg-gray-800 
                               text-gray-600 dark:text-gray-300 
                               uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-8 py-4 text-left">Nama Supplier</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Alamat</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                            <td class="px-8 py-5">
                                <p class="font-semibold text-gray-900 dark:text-white text-base">
                                    {{ $supplier->name }}
                                </p>
                            </td>

                            <td class="px-6 py-5 text-gray-700 dark:text-gray-300">
                                {{ $supplier->phone ?? '-' }}
                            </td>

                            <td class="px-6 py-5 text-gray-700 dark:text-gray-300">
                                {{ $supplier->email ?? '-' }}
                            </td>

                            <td class="px-6 py-5 text-gray-600 dark:text-gray-400 text-sm">
                                {{ $supplier->address ?? '-' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-6 py-12 text-center 
                                       text-gray-500 dark:text-gray-400">
                                Belum ada supplier terdaftar.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            {{ $suppliers->links() }}
        </div>

    </div>

</div>

@endsection
