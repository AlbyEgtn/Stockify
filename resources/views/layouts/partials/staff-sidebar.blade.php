{{-- ================================= --}}
{{--          SIDEBAR STAFF FINAL      --}}
{{-- ================================= --}}

@php
    $dashboardActive = request()->routeIs('staff.dashboard');
    $incomingActive = request()->routeIs('staff.stock.incoming');
    $outgoingActive = request()->routeIs('staff.stock.outgoing');
    $historyActive  = request()->routeIs('staff.stock.history');
@endphp

<aside
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


    {{-- USER INFO --}}
    <div class="px-4 py-4 border-b border-gray-700
                bg-gradient-to-r from-emerald-600/20 to-green-600/20
                flex items-center gap-3">

        <div class="w-10 h-10 rounded-full
                    bg-gradient-to-br from-emerald-500 to-green-500
                    flex items-center justify-center
                    text-white font-bold shadow">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div x-show="!collapsed">
            <div class="text-sm font-semibold text-white truncate">
                {{ auth()->user()->name }}
            </div>

            <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full
                         bg-emerald-500 text-white">
                Staff Gudang
            </span>
        </div>
    </div>


    {{-- NAVIGATION --}}
    <nav class="flex-1 px-3 py-6 text-sm space-y-2">

        {{-- DASHBOARD --}}
        <a href="{{ route('staff.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ $dashboardActive
                ? 'bg-emerald-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>🏠</span>
            <span x-show="!collapsed">Dashboard</span>
        </a>


        {{-- BARANG MASUK --}}
        <a href="{{ route('staff.stock.incoming') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ $incomingActive
                ? 'bg-emerald-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>📥</span>
            <span x-show="!collapsed">Barang Masuk</span>
        </a>


        {{-- BARANG KELUAR --}}
        <a href="{{ route('staff.stock.outgoing') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ $outgoingActive
                ? 'bg-emerald-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>📤</span>
            <span x-show="!collapsed">Barang Keluar</span>
        </a>


        {{-- HISTORY --}}
        <a href="{{ route('staff.stock.history') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ $historyActive
                ? 'bg-emerald-600 text-white shadow'
                : 'hover:bg-white/10 hover:text-white' }}">

            <span>🕒</span>
            <span x-show="!collapsed">History Stok</span>
        </a>

    </nav>


    {{-- FOOTER --}}
    <div class="p-4 border-t border-gray-700 space-y-2">

        {{-- DARK MODE --}}
        <button @click="dark = !dark"
            class="w-full px-3 py-2 rounded-lg
                   bg-gray-700 hover:bg-gray-600
                   text-white transition">

            <span x-show="!collapsed"
                  x-text="dark ? 'Light Mode' : 'Dark Mode'"></span>

            <span x-show="collapsed">🌙</span>
        </button>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center justify-center gap-2
                           px-3 py-2 rounded-lg
                           text-red-400 hover:bg-red-600 hover:text-white transition">

                <span>⎋</span>
                <span x-show="!collapsed">Logout</span>
            </button>
        </form>

    </div>

</aside>