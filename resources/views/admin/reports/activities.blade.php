@extends('layouts.admin')

@section('title','Aktivitas Pengguna')
@section('page-title','Laporan Aktivitas Pengguna')

@section('content')

<div class="space-y-6">

    <!-- SEARCH & FILTER -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

        <form method="GET" class="flex gap-2 w-full sm:w-auto">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari aktivitas..."
                   class="w-full sm:w-64 px-3 py-2 text-sm border rounded-lg 
                          dark:bg-gray-900 dark:text-white">

            <button type="submit"
                    class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Cari
            </button>
        </form>

        <div class="text-sm text-gray-500">
            Total: {{ $activities->total() }} aktivitas
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                    <tr>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Pengguna</th>
                        <th class="px-5 py-3 text-left">Aktivitas</th>
                        <th class="px-5 py-3 text-left">Deskripsi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">

                    @forelse($activities as $activity)

                        <tr class="hover:bg-indigo-50 dark:hover:bg-gray-800 transition">

                            <!-- Tanggal -->
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">
                                {{ $activity->created_at->format('d M Y H:i') }}
                            </td>

                            <!-- User -->
                            <td class="px-5 py-3 font-semibold text-gray-900 dark:text-white">
                                {{ $activity->user->name ?? '-' }}
                            </td>

                            <!-- Activity Type -->
                            <td class="px-5 py-3">
                                @php
                                    $type = strtolower($activity->activity_type);
                                @endphp

                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ str_contains($type, 'delete') ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'
                                      : (str_contains($type, 'update') ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400'
                                      : (str_contains($type, 'create') ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400'
                                      : 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400')) }}">
                                    {{ ucfirst(str_replace('_',' ', $activity->activity_type)) }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-300">
                                {{ $activity->description }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="px-5 py-6 text-center text-gray-500 dark:text-gray-400">
                                Belum ada aktivitas tercatat.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $activities->links() }}
    </div>

</div>

@endsection
