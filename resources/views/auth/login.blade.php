<x-guest-layout>

<div class="w-full max-w-md
            bg-white dark:bg-gray-800
            rounded-3xl
            shadow-2xl
            p-8 space-y-6">

    <!-- LOGO -->
    <div class="text-center">
        <img src="{{ asset('images/Stockify.svg') }}"
            alt="Logo" class="w-20 h-20 mx-auto mb-6 drop-shadow-lg">

        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Selamat Datang
        </h2>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            Silakan login untuk melanjutkan
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-lg"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-lg"
                type="password"
                name="password"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember -->
        <div class="flex items-center gap-2 text-sm">
            <input type="checkbox"
                name="remember"
                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-gray-600 dark:text-gray-400">
                Remember me
            </span>
        </div>

        <!-- Button -->
        <button type="submit"
            class="w-full py-2.5 rounded-lg
                   bg-indigo-600 hover:bg-indigo-700
                   text-white font-semibold
                   transition shadow-lg">
            Log In
        </button>

    </form>

</div>

</x-guest-layout>