<!DOCTYPE html>
<html lang="en"
      x-data="{
          dark: localStorage.getItem('dark') === 'true',
          collapsed: false
      }"
      x-init="$watch('dark', val => localStorage.setItem('dark', val))"
      :class="{ 'dark': dark }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel - @yield('title')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="flex min-h-screen">

@include('layouts.partials.manager-sidebar')

    <!-- MAIN CONTENT -->
    <main
        :class="collapsed ? 'ml-20' : 'ml-64'"
        class="flex-1 px-6 py-6 transition-all duration-300 overflow-y-auto min-h-screen">

        <div class="w-full max-w-6xl mx-auto">

            <h1 class="text-xl md:text-2xl font-bold mb-5
                    text-gray-800 dark:text-white">
                @yield('page-title')
            </h1>

            @yield('content')

        </div>

    </main>

</div>

</body>
</html>
