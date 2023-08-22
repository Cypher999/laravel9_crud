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
     Schema::create('barang',function (Blueprint $table){
        $table->string('id_barang',5)->primary();
        $table->string('nama_barang',50);
        $table->float('harga');
        $table->integer('stok');
     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('barang');
    }
};
