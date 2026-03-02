<!DOCTYPE html>
<html
x-data="{
    dark: localStorage.getItem('dark') === 'true',
    collapsed: false
}"
x-init="$watch('dark', val => localStorage.setItem('dark', val))"
:class="{ 'dark': dark }">

<head>
    <meta charset="UTF-8">
    <title>Staff Panel - @yield('title')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

<div class="flex min-h-screen">

    @include('layouts.partials.staff-sidebar')

    <!-- MAIN CONTENT -->
    <div :class="collapsed ? 'ml-[80px]' : 'ml-[250px]'"
         class="flex-1 p-8 transition-all duration-300">

        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
            @yield('page-title')
        </h1>

        @yield('content')

    </div>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>