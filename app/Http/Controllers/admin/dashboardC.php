<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Support\Facades\Response;
class dashboardC extends Controller
{
  public function index(){
    $activeMenu="Home";
    return view('admin/dashboard/index',compact('activeMenu'));
  }
  public function readAllData(){
    $barang=Barang::selectRaw('count(*) as jml')->get();
    $penjualan=Penjualan::selectRaw('count(*) as jml')->get();
    $user=User::selectRaw('count(*) as jml')->get();
    $data=[
      "jumlahBarang"=>$barang[0]['jml'],
      "jumlahPenjualan"=>$penjualan[0]['jml'],
      "jumlahUser"=>$user[0]['jml']
    ];
    return Response::json($data,200);
  }
}
