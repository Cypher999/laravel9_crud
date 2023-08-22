<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use Validator;
class penjualanC extends Controller
{
  public function index(){
    $activeMenu="Penjualan";
    return view('admin/penjualan/index',compact('activeMenu'));
  }
  public function readPage($halaman){
    $limit=10;
    $offset=$limit*($halaman-1);
    $jumlahData=Penjualan::selectRaw('count(*) as jml')->get();
    $data=Penjualan::selectRaw('penjualan.*,barang.nama_barang,user.username')
    ->join('barang','barang.id_barang','=','penjualan.id_barang')
    ->join('user','penjualan.id_user','=','user.id_user')
    ->limit($limit)
    ->offset($offset)
    ->get();
    for($i=0;$i<count($data);$i++){
      $data[$i]['gambar']=url('public/images/products/'.$data[$i]["id_barang"].".jpg?e=".Str::random(5));
    }
    $maxPage=ceil($jumlahData[0]['jml']/$limit);
    return Response::json([
      'data'=>$data,
      'data-count'=>$jumlahData[0]['jml'],
      'max-page'=>$maxPage
    ],200);
  }
  public function delete($id_penjualan,Request $req){
    $penjualan=Penjualan::find($id_penjualan);
    $simpan=$penjualan->delete();
    if($simpan){
      return Response::json(['message'=>'DATA BERHASIL DIHAPUS'],'200');
    }
    else{
      return Response::json(['message'=>'error'],'502');
    }
  }
}
