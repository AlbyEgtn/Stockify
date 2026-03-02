<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Produk terkait
            $table->enum('type', ['in', 'out']); // Tipe transaksi: masuk (in) atau keluar (out)
            $table->integer('quantity'); // Jumlah barang
            $table->timestamp('transaction_date')->useCurrent(); // Tanggal transaksi
            $table->timestamps();
        });
    }   

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
