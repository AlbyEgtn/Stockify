<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {

            if (!Schema::hasColumn('stock_transactions', 'difference')) {
                $table->integer('difference')
                    ->nullable()
                    ->after('quantity');
            }
        });

        // Update enum hanya jika belum ada OPNAME
        DB::statement("
            ALTER TABLE stock_transactions 
            MODIFY type ENUM('IN','OUT','OPNAME')
        ");
    }

    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {

            if (Schema::hasColumn('stock_transactions', 'difference')) {
                $table->dropColumn('difference');
            }
        });

        DB::statement("
            ALTER TABLE stock_transactions 
            MODIFY type ENUM('IN','OUT')
        ");
    }
};
