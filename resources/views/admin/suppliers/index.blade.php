@extends('layouts.admin')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">

    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            Daftar Supplier
        </h3>

        <a href="{{ route('admin.suppliers.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            + Tambah Supplier
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="bg-gray-100 dark:bg-gray-700 dark:text-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Kontak</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Telepon</th>
                    <th class="px-4 py-3 text-center min-w-[130px]">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="dark:text-gray-100">

                @forelse($suppliers as $supplier)
                    <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <td class="p-3 font-medium">
                            {{ $supplier->name }}
                        </td>

                        <td class="p-3">
                            {{ $supplier->contact_person ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $supplier->email ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $supplier->phone ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap justify-center gap-2">

                                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                class="px-3 py-1 text-xs rounded-lg
                                        bg-blue-100 text-blue-600
                                        hover:bg-blue-200 transition whitespace-nowrap">
                                    Edit
                                </a>

                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin hapus Supplier?')">
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
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            Belum ada supplier
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

</div>

<div class="mt-6">
    {{ $suppliers->links() }}
</div>

@endsection
