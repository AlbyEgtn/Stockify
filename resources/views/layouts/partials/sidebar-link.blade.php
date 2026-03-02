<li>
    <a href="{{ $url ?? route(str_replace('.*','',$route)) }}"
       class="group flex items-center gap-3 px-4 py-2 rounded-lg
              transition-all duration-200 ease-out
              hover:translate-x-1 hover:shadow-md
              {{ request()->routeIs($route)
                    ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
                    : 'text-gray-300 hover:bg-gray-700/70 hover:text-white' }}">
        <span data-menu-text class="font-medium">
            {{ $label }}
        </span>
    </a>
</li>
