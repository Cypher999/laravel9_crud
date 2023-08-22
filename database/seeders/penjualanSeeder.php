<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
class penjualanSeeder extends Seeder
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
          'id_penjualan'=>'PNJ01',
          'id_barang'=>'BRG01',
          'id_user'=>'USR01',
          'jumlah'=>1,
          'tgl_penjualan'=>'2022-02-02'
        ],
        [
          'id_penjualan'=>'PNJ02',
          'id_barang'=>'BRG01',
          'id_user'=>'USR02',
          'jumlah'=>2,
          'tgl_penjualan'=>'2022-02-02'
        ],
        [
          'id_penjualan'=>'PNJ03',
          'id_barang'=>'BRG02',
          'id_user'=>'USR01',
          'jumlah'=>2,
          'tgl_penjualan'=>'2022-02-02'
        ],
        [
          'id_penjualan'=>'PNJ04',
          'id_barang'=>'BRG02',
          'id_user'=>'USR02',
          'jumlah'=>3,
          'tgl_penjualan'=>'2022-02-02'
        ],
        [
          'id_penjualan'=>'PNJ05',
          'id_barang'=>'BRG03',
          'id_user'=>'USR01',
          'jumlah'=>4,
          'tgl_penjualan'=>'2022-02-02'
        ],
        [
          'id_penjualan'=>'PNJ06',
          'id_barang'=>'BRG03',
          'id_user'=>'USR02',
          'jumlah'=>6,
          'tgl_penjualan'=>'2022-02-02'
        ]
      ];
      DB::table('penjualan')->insert($data);
    }
}
