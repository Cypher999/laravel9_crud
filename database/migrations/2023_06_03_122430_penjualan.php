<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('penjualan',function (Blueprint $table){
        $table->string('id_penjualan',5)->primary();
        $table->string('id_user',5);
        $table->string('id_barang');
        $table->integer('jumlah');
        $table->datetime('tgl_penjualan');
        $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('penjualan');
    }
};
