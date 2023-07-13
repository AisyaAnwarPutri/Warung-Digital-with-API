<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('id_member');
            $table->integer('invoice');
            $table->integer('grand_total')->comment('Total semua harga produk di detail order');
            $table->string('status');
            $table->boolean('lunas')->default(0)->change()->comment('lunas{1}, belum lunas{0}');
            $table->timestamps();
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('id_order');
            $table->integer('id_produk');
            $table->integer('jumlah');
            $table->integer('harga')->comment('Harga per produk');
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
        Schema::dropIfExists('orders');
    }
}
