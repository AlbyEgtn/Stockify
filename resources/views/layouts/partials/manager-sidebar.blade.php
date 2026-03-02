{{-- ================================= --}}
{{--        SIDEBAR MANAGER            --}}
{{-- ================================= --}}

@php
    $produkActive = request()->routeIs('manager.products.*');

    $stokActive = request()->routeIs('manager.stock.*');

    $supplierActive = request()->routeIs('manager.suppliers.*');
@endphp

<aside
    x-data="{
        collapsed: false,
        openStok: {{ $stokActive ? 'true' : 'false' }}
    }"
    :class="collapsed ? 'w-[80px]' : 'w-[250px]'"
    class="fixed inset-y-0 left-0
           bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900
           text-gray-200 shadow-2xl flex flex-col
           transition-all duration-300">

    {{-- HEADER --}}
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700">
        <div class="flex items-center gap-3">

            @if(!empty($global_system_logo))
                <img src="{{ asset('storage/'.$global_system_logo) }}"
                    class="h-8 w-8 object-contain rounded">
            @endif

            <span x-show="!collapsed"
                class="text-xl font-semibold tracking-wide">
                {{ $global_system_name }}
            </span>

        </div>
    </div>

    {{-- USER --}}
    <div class="px-4 py-4 border-b border-gray-700 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full
                    bg-gradient-to-br from-blue-500 to-indigo-500
                    flex items-center justify-center
                    text-white font-bold shadow">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div x-show="!collapsed">
            <div class="text-sm font-semibold text-white truncate">
                {{ auth()->user()->name }}
            </div>
            <div class="text-xs text-gray-400">
                Manager Gudang
            </div>
        </div>
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 px-3 py-6 text-sm space-y-2">

        {{-- DASHBOARD --}}
        <a href="{{ route('manager.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ request()->routeIs('manager.dashboard')
                ? 'bg-blue-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>🏠</span>
            <span x-show="!collapsed">Dashboard</span>
        </a>


        {{-- PRODUK --}}
        <a href="{{ route('manager.products.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ $produkActive
                ? 'bg-blue-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>📦</span>
            <span x-show="!collapsed">Produk</span>
        </a>


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

                <a href="{{ route('manager.stock.incoming') }}"
                   class="block py-1.5
                   {{ request()->routeIs('manager.stock.incoming')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Barang Masuk
                </a>

                <a href="{{ route('manager.stock.outgoing') }}"
                   class="block py-1.5
                   {{ request()->routeIs('manager.stock.outgoing')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Barang Keluar
                </a>

                <a href="{{ route('manager.stock.opname') }}"
                   class="block py-1.5
                   {{ request()->routeIs('manager.stock.opname.*')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Stock Opname
                </a>

                <a href="{{ route('manager.stock.history') }}"
                   class="block py-1.5
                   {{ request()->routeIs('manager.stock.history')
                        ? 'text-white font-medium'
                        : 'text-gray-400 hover:text-white' }}">
                    Riwayat Stok
                </a>
            </div>
        </div>


        {{-- SUPPLIER --}}
        <a href="{{ route('manager.suppliers.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ $supplierActive
                ? 'bg-blue-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>🚚</span>
            <span x-show="!collapsed">Supplier</span>
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