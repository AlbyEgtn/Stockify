<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        


    </head>
    <script src="//unpkg.com/alpinejs" defer></script>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            {{-- SIDEBAR (HANYA UNTUK USER LOGIN) --}}
            @auth
                @if(auth()->user()->role === 'Admin')
                    @include('layouts.admin')
                @endif
            @endauth

            {{-- KONTEN UTAMA --}}
            <main class="@auth ml-[240px] p-6 @else @endauth">

                {{-- PAGE HEADING (OPSIONAL, BISA DIHAPUS JUGA) --}}
                @if (isset($header))
                    <header class="mb-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            {{ $header }}
                        </h2>
                    </header>
                @endif

                {{ $slot }}
            </main>
        </div>

        <script>
            const sidebar = document.querySelector('[data-sidebar]');
            const btn = document.querySelector('[data-collapse-btn]');
            const texts = document.querySelectorAll('[data-menu-text]');
            const logo = document.querySelector('[data-logo-text]');

            btn.addEventListener('click', () => {
                sidebar.classList.toggle('w-[250px]');
                sidebar.classList.toggle('w-[80px]');

                texts.forEach(el => el.classList.toggle('hidden'));
                logo.classList.toggle('hidden');
            });
        </script>
    </body>


</html>
