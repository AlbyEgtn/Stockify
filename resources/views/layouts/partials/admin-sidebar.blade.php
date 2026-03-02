
@php

    $produkActive =
        request()->routeIs('admin.products.index') ||
        request()->routeIs('admin.products.create') ||
        request()->routeIs('admin.products.edit') ||
        request()->routeIs('admin.categories.*') ||
        request()->routeIs('admin.suppliers.*');

    $importExportActive =
        request()->routeIs('admin.products.import*');

    $stokActive =
        request()->routeIs('admin.stocks.*') ||
        request()->routeIs('admin.products.minimumStock');

    $laporanActive = request()->routeIs('admin.reports.*');

    $userActive = request()->routeIs('admin.users.*');
    $pengaturanActive = request()->routeIs('admin.settings.*');
@endphp
<aside
    x-data="{
        openProduk: {{ $produkActive ? 'true' : 'false' }},
        openStok: {{ $stokActive ? 'true' : 'false' }},
        openLaporan: {{ $laporanActive ? 'true' : 'false' }}
    }"
    :class="collapsed ? 'w-[80px]' : 'w-[250px]'"
    class="fixed inset-y-0 left-0
           bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900
           text-gray-200 shadow-2xl flex flex-col
           transition-all duration-300">

    {{-- HEADER --}}
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700">
        @php
            $systemName = \App\Models\Setting::getValue('system_name', 'Stockify');
            $systemLogo = \App\Models\Setting::getValue('logo');
        @endphp

        <div class="flex items-center gap-3">

            @if($systemLogo)
                <img src="{{ asset('storage/'.$systemLogo) }}"
                    class="h-8 w-8 object-contain rounded">
            @endif

            <span x-show="!collapsed"
                class="text-xl font-semibold tracking-wide">
                {{ $systemName }}
            </span>

        </div>
    </div>

    {{-- USER --}}
    <div class="px-4 py-4 border-b border-gray-700 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full
                    bg-gradient-to-br from-indigo-500 to-purple-500
                    flex items-center justify-center
                    text-white font-bold shadow">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div x-show="!collapsed">
            <div class="text-sm font-semibold text-white truncate">
                {{ auth()->user()->name }}
            </div>
            <div class="text-xs text-gray-400">
                {{ auth()->user()->role }}
            </div>
        </div>
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 px-3 py-6 text-sm space-y-2">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ request()->routeIs('admin.dashboard')
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>🏠</span>
            <span x-show="!collapsed">Dashboard</span>
        </a>

        <div>
            <button @click="openProduk = !openProduk"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                {{ ($produkActive || $importExportActive) ? 'bg-white/10' : 'hover:bg-white/10' }}">

                <div class="flex items-center gap-3">
                    <span>📦</span>
                    <span x-show="!collapsed">Produk</span>
                </div>

                <span x-show="!collapsed"
                    :class="openProduk ? 'rotate-180' : ''"
                    class="text-xs transition">⌄</span>
            </button>

            <div x-show="openProduk && !collapsed"
                x-transition
                class="ml-8 mt-2 space-y-1">

                {{-- DATA PRODUK --}}
                <a href="{{ route('admin.products.index') }}"
                class="block py-1.5
                {{ $produkActive
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Data Produk
                </a>

                {{-- KATEGORI --}}
                <a href="{{ route('admin.categories.index') }}"
                class="block py-1.5
                {{ request()->routeIs('admin.categories.*')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Kategori
                </a>

                {{-- SUPPLIER --}}
                <a href="{{ route('admin.suppliers.index') }}"
                class="block py-1.5
                {{ request()->routeIs('admin.suppliers.*')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Supplier
                </a>

                {{-- IMPORT EXPORT --}}
                <a href="{{ route('admin.products.import') }}"
                class="block py-1.5
                {{ $importExportActive
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Import / Export
                </a>

            </div>
        </div>

        {{-- STOK --}}
        <div>
            <button @click="openStok = !openStok"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                {{ $stokActive ? 'bg-white/10' : 'hover:bg-white/10' }}">

                <div class="flex items-center gap-3">
                    <span>📊</span>
                    <span x-show="!collapsed">Stok</span>
                </div>

                <span x-show="!collapsed"
                      :class="openStok ? 'rotate-180' : ''"
                      class="text-xs transition">⌄</span>
            </button>

            <div x-show="openStok && !collapsed"
                 x-transition
                 class="ml-8 mt-2 space-y-1">

                <a href="{{ route('admin.products.minimumStock') }}"
                   class="block py-1.5
                   {{ request()->routeIs('admin.products.minimumStock')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Minimum Stock
                </a>

                <a href="{{ route('admin.stocks.index') }}"
                   class="block py-1.5
                   {{ request()->routeIs('admin.stocks.*')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Riwayat Stok
                </a>
            </div>
        </div>


        {{-- LAPORAN --}}
        <div>
            <button @click="openLaporan = !openLaporan"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                {{ $laporanActive ? 'bg-white/10' : 'hover:bg-white/10' }}">

                <div class="flex items-center gap-3">
                    <span>📑</span>
                    <span x-show="!collapsed">Laporan</span>
                </div>

                <span x-show="!collapsed"
                    :class="openLaporan ? 'rotate-180' : ''"
                    class="text-xs transition">⌄</span>
            </button>

            <div x-show="openLaporan && !collapsed"
                x-transition
                class="ml-8 mt-2 space-y-1 text-sm">

                {{-- 🔄 GANTI NAMA DI SINI --}}
                <a href="{{ route('admin.reports.mutation') }}"
                class="block py-1.5
                {{ request()->routeIs('admin.reports.mutation')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                   Laporan Stok
                </a>

                <a href="{{ route('admin.reports.transactions') }}"
                class="block py-1.5
                {{ request()->routeIs('admin.reports.transactions')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Barang Masuk & Keluar
                </a>

                <a href="{{ route('admin.reports.activities') }}"
                class="block py-1.5
                {{ request()->routeIs('admin.reports.activities')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Aktivitas User
                </a>

            </div>
        </div>


        {{-- USERS --}}
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ $userActive
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">
            <span>👤</span>
            <span x-show="!collapsed">Manajemen Akun</span>
        </a>
        {{-- PENGATURAN --}}
        <a href="{{ route('admin.settings.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ $pengaturanActive
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>⚙️</span>
            <span x-show="!collapsed">Pengaturan</span>
        </a>

    </nav>

    {{-- FOOTER --}}
    <div class="p-4 border-t border-gray-700 space-y-2">

        <button @click="dark = !dark"
            class="w-full px-3 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 text-white transition">
            <span x-show="!collapsed"
                  x-text="dark ? 'Light Mode' : 'Dark Mode'"></span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full px-3 py-2 rounded-lg
                           text-red-400 hover:bg-red-600 hover:text-white transition">
                <span x-show="!collapsed">Logout</span>
            </button>
        </form>

    </div>

</aside>