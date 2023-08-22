<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
class userSeeder extends Seeder
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
          'id_user'=>'ADM01',
          'username'=>'ADMIN',
          'password'=>'$2y$10$6fCunObhHOpLntp40rs0aur8ukeMQ.t6Vza06IEHO1GZNf.Lm21Dq',
          'type'=>'A'
        ],
        [
          'id_user'=>'USR01',
          'username'=>'USER 1',
          'password'=>'$2y$10$6fCunObhHOpLntp40rs0aur8ukeMQ.t6Vza06IEHO1GZNf.Lm21Dq',
          'type'=>'U'
        ],
        [
          'id_user'=>'USR02',
          'username'=>'USER 2',
          'password'=>'$2y$10$6fCunObhHOpLntp40rs0aur8ukeMQ.t6Vza06IEHO1GZNf.Lm21Dq',
          'type'=>'U'
        ]
        ];
        DB::table('user')->insert($data);
    }
}
