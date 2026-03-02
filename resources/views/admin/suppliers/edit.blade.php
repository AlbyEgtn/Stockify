@extends('layouts.admin')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@section('content')

<div class="flex justify-center">
    <div class="w-full max-w-2xl bg-white dark:bg-gray-800 shadow rounded-xl p-6">

        <form action="{{ route('admin.suppliers.update', $supplier->id) }}"
              method="POST"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Supplier --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Nama Supplier
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $supplier->name) }}"
                       class="w-full rounded-lg border-gray-300
                              dark:bg-gray-900 dark:text-white"
                       required>

                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Contact Person --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Contact Person
                </label>

                <input type="text"
                       name="contact_person"
                       value="{{ old('contact_person', $supplier->contact_person) }}"
                       class="w-full rounded-lg border-gray-300
                              dark:bg-gray-900 dark:text-white">

                @error('contact_person')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email & Telepon --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $supplier->email) }}"
                           class="w-full rounded-lg border-gray-300
                                  dark:bg-gray-900 dark:text-white">

                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                        Telepon
                    </label>

                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $supplier->phone) }}"
                           class="w-full rounded-lg border-gray-300
                                  dark:bg-gray-900 dark:text-white">

                    @error('phone')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Alamat --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Alamat
                </label>

                <textarea name="address"
                          rows="3"
                          class="w-full rounded-lg border-gray-300
                                 dark:bg-gray-900 dark:text-white">{{ old('address', $supplier->address) }}</textarea>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Catatan
                </label>

                <textarea name="notes"
                          rows="3"
                          class="w-full rounded-lg border-gray-300
                                 dark:bg-gray-900 dark:text-white">{{ old('notes', $supplier->notes) }}</textarea>
            </div>

            {{-- Action --}}
            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">

                <a href="{{ route('admin.suppliers.index') }}"
                   class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Update Supplier
                </button>

            </div>

        </form>

    </div>
</div>

@endsection
