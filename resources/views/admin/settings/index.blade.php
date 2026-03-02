@extends('layouts.admin')

@section('title','Pengaturan Sistem')
@section('page-title','Pengaturan Sistem')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8">

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.settings.update') }}"
              enctype="multipart/form-data"
              class="space-y-8">

            @csrf
            @method('PUT')

            {{-- Nama Sistem --}}
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Nama Sistem
                </label>

                <input type="text"
                       name="system_name"
                       value="{{ old('system_name', $system_name ?? 'Stockify') }}"
                       class="w-full px-4 py-3 rounded-xl border
                              focus:ring-2 focus:ring-indigo-500 focus:outline-none
                              dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                @error('system_name')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Logo Sistem --}}
            <div>
                <label class="block mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Logo Sistem
                </label>

                <div class="flex items-center gap-6">

                    {{-- Preview Logo --}}
                    @php
                        $logo = \App\Models\Setting::getValue('logo');
                    @endphp

                    <div class="w-24 h-24 rounded-2xl border
                                flex items-center justify-center
                                bg-gray-50 dark:bg-gray-800
                                overflow-hidden shadow-inner">

                        <img src="{{ $logo 
                                    ? asset('storage/'.$logo) 
                                    : asset('images/default-logo.png') }}"
                            class="object-contain w-full h-full">

                    </div>

                    <div class="flex-1">
                        <input type="file"
                               name="logo"
                               class="w-full px-4 py-3 rounded-xl border
                                      focus:ring-2 focus:ring-indigo-500
                                      dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Format: PNG, JPG, JPEG, SVG (Max 2MB)
                        </p>

                        @error('logo')
                            <p class="mt-2 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Checkbox Hapus Logo --}}
                        @if(!empty($system_logo))
                            <label class="flex items-center gap-2 text-sm text-red-600 mt-3">
                                <input type="checkbox" name="remove_logo" value="1">
                                Hapus Logo
                            </label>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Tombol --}}
            <div class="pt-4 border-t dark:border-gray-700 flex justify-end">
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700
                               text-white font-medium shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>

@endsection