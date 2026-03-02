@php
    $user = auth()->user();
@endphp

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        {{-- Avatar --}}
        <div class="w-12 h-12 rounded-full bg-blue-600
                    flex items-center justify-center
                    text-white font-bold text-lg">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        {{-- Info --}}
        <div>
            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{ $user->name }}
            </div>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ ucfirst($user->role) }} • {{ $user->email }}
            </div>
        </div>
    </div>

    {{-- Badge Role --}}
    <span class="px-3 py-1 text-xs rounded-full
        @if($user->role === 'admin')
        @elseif($user->role === 'manager')
        @else bg-blue-100 text-blue-600
        @endif
    ">
        {{ strtoupper($user->role) }}
    </span>
</div>
