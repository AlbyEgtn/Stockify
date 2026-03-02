<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Riwayat Transaksi Stok Barang
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Riwayat Transaksi</h3>
            <table class="min-w-full text-sm dark:text-gray-200">
                <thead>
                    <tr class="border-b dark:border-gray-700 text-left">
                        <th class="py-2">Nama Produk</th>
                        <th class="py-2">Tipe Transaksi</th>
                        <th class="py-2">Jumlah</th>
                        <th class="py-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-2">{{ $transaction->product->name }}</td>
                            <td class="py-2">{{ ucfirst($transaction->type) }}</td>
                            <td class="py-2">{{ $transaction->quantity }}</td>
                            <td class="py-2">{{ $transaction->transaction_date->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
