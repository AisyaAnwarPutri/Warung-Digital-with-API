<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatStoksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_stoks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('produk_id');
            $table->integer('stok_awal')->nullable()->comment('Jumlah stok awal');
            $table->integer('stok_update')->nullable()->comment('Selisih perubahan stok');
            $table->integer('stok_terbaru')->nullable()->comment('Jumlah stok terbaru');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_stoks');
    }
}
