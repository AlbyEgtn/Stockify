@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-600">
            <h2 class="text-lg font-semibold text-white">
                Edit Data User
            </h2>
            <p class="text-sm text-indigo-100 mt-1">
                Perbarui informasi akun pengguna sistem.
            </p>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.users.update', $user->id) }}"
              method="POST"
              class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- INFORMASI DASAR --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2 rounded-xl border border-gray-300
                                  focus:ring-2 focus:ring-indigo-500 focus:outline-none
                                  dark:bg-gray-900 dark:text-white dark:border-gray-700"
                           required>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2 rounded-xl border border-gray-300
                                  focus:ring-2 focus:ring-indigo-500 focus:outline-none
                                  dark:bg-gray-900 dark:text-white dark:border-gray-700"
                           required>
                </div>

            </div>

            {{-- ROLE --}}
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                    Role Pengguna
                </label>
                <select name="role"
                        class="w-full px-4 py-2 rounded-xl border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none
                               dark:bg-gray-900 dark:text-white dark:border-gray-700"
                        required>
                    <option value="Admin" {{ $user->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Staff Gudang" {{ $user->role === 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
                    <option value="Manager Gudang" {{ $user->role === 'Manager Gudang' ? 'selected' : '' }}>Manager Gudang</option>
                </select>
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                    Password Baru
                </label>
                <input type="password"
                       name="password"
                       placeholder="Kosongkan jika tidak ingin mengubah password"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300
                              focus:ring-2 focus:ring-indigo-500 focus:outline-none
                              dark:bg-gray-900 dark:text-white dark:border-gray-700">
            </div>

            {{-- STATUS AKTIF --}}
            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-900
                        p-4 rounded-xl border dark:border-gray-700">

                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                        Status Akun
                    </p>
                    <p class="text-xs text-gray-500">
                        Aktifkan atau nonaktifkan akses pengguna ke sistem.
                    </p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="sr-only peer"
                           {{ $user->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2
                                peer-focus:ring-indigo-500 rounded-full
                                peer dark:bg-gray-700
                                peer-checked:bg-indigo-600
                                after:content-['']
                                after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:border after:rounded-full
                                after:h-5 after:w-5 after:transition-all
                                peer-checked:after:translate-x-full">
                    </div>
                </label>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">

                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2 rounded-xl bg-gray-200
                          hover:bg-gray-300 transition
                          dark:bg-gray-700 dark:hover:bg-gray-600">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-xl
                               bg-indigo-600 text-white
                               hover:bg-indigo-700 transition shadow">
                    Update User
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
