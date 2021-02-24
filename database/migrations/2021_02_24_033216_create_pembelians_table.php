<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembeliansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_mitra')->unsigned();
            $table->integer('user_pembeli')->unsigned();
            $table->string('no_invoice')->nullable();
            $table->double('subtotal', 12, 2)->default(0);
            $table->double('diskon', 12, 2)->default(0);
            $table->double('total', 12, 2)->default(0);
            $table->date('tanggal_transaksi');
            $table->string('status')->default('cart');//cart, checkout
            $table->foreign('user_mitra')->references('id')->on('users');
            $table->foreign('user_pembeli')->references('id')->on('users');
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
        Schema::dropIfExists('pembelian');
    }
}
