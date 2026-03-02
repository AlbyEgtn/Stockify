@extends('layouts.admin')

@section('title', 'Manajemen Akun')
@section('page-title', 'Manajemen Akun')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <p class="text-sm text-gray-500">
            Mengatur hak akses pengguna sistem
        </p>
    </div>

    <a href="{{ route('admin.users.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition">
        + Tambah User
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 text-left">User</th>
                    <th class="px-6 py-4 text-left">Role</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Terdaftar</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y dark:divide-gray-700">

                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        {{-- USER INFO --}}
                        <td class="px-6 py-4 flex items-center gap-4">

                            <div class="w-10 h-10 flex items-center justify-center
                                        rounded-full bg-indigo-100 text-indigo-600 font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="font-medium text-gray-800 dark:text-gray-100">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $user->email }}
                                </div>
                            </div>

                        </td>

                        {{-- ROLE --}}
                        <td class="px-6 py-4">

                            @php
                                $roleColor = match($user->role) {
                                    'Admin' => 'bg-red-100 text-red-600',
                                    'Staff Gudang' => 'bg-blue-100 text-blue-600',
                                    'Manager Gudang' => 'bg-green-100 text-green-600',
                                    default => 'bg-gray-100 text-gray-600'
                                };
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $roleColor }}">
                                {{ ucfirst($user->role) }}
                            </span>

                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">

                            <form action="{{ route('admin.users.toggle-status', $user->id) }}"
                                  method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    class="px-3 py-1 text-xs font-semibold rounded-full transition
                                    {{ $user->is_active
                                        ? 'bg-green-100 text-green-600 hover:bg-green-200'
                                        : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                    
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}

                                </button>
                            </form>

                        </td>

                        {{-- TANGGAL --}}
                        <td class="px-6 py-4 text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="px-3 py-1 text-xs bg-yellow-100 text-yellow-600
                                          rounded-lg hover:bg-yellow-200 transition">
                                    Edit
                                </a>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="px-3 py-1 text-xs bg-red-100 text-red-600
                                                       rounded-lg hover:bg-red-200 transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            Belum ada user terdaftar
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection
