<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
class barangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data=[
        [
          'id_barang'=>'BRG01',
          'nama_barang'=>'BARANG 1',
          'harga'=>150000,
          'stok'=>15
        ],
        [
          'id_barang'=>'BRG02',
          'nama_barang'=>'BARANG 2',
          'harga'=>200000,
          'stok'=>20
        ],
        [
          'id_barang'=>'BRG03',
          'nama_barang'=>'BARANG 3',
          'harga'=>250000,
          'stok'=>25
        ]
        ];
        DB::table('barang')->insert($data);
    }
}
