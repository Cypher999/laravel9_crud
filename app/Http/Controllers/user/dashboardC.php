<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Barang;
use App\Models\Penjualan;
use Validator;
class dashboardC extends Controller
{
  function index(){
    $activeMenu="Home";
    return view('user/dashboard/index',compact('activeMenu'));
  }
  public function readPage($halaman){
    $limit=5;
    $offset=$limit*($halaman-1);
    $jumlahData=Barang::selectRaw('count(*) as jml')->get();
    $data=Barang::selectRaw('*')->limit($limit)->offset($offset)->get();
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
  public function readOne($id_barang){
    $data=Barang::selectRaw('*')->whereRaw('id_barang=?',[$id_barang])->get();
    if(count($data)==0){
      return Response::json(['message'=>'DATA NOT FOUND'],404);
    }
    else{
      $data[0]['gambar']=url('public/images/products/'.$data[0]["id_barang"].".jpg?e=".Str::random(5));
      return Response::json([
      'data'=>$data
    ],200);
    }

  }
  public function pesan_barang(Request $req){
    $validator = Validator::make($req->all(),[
      'jumlah' => 'required|numeric|min:1'
    ],[
      'jumlah.required'=>'jumlah Tidak Boleh Kosong',
      'jumlah.numeric'=>'jumlah harus berbentuk angka',
      'jumlah.min'=>'jumlah minimal adalah 1'
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $cekStok=Barang::whereRaw("id_barang=?",[$req->input('id_barang')])->get();
      if($cekStok[0]['stok']>=$req->input("jumlah")){
        $id_penjualan=Str::random(5);
        $penjualan=new Penjualan;
        $penjualan->id_penjualan=$id_penjualan;
        $penjualan->id_barang=$req->input('id_barang');
        $penjualan->id_user=$req->session()->get('id_user_laravel_crud');
        $penjualan->jumlah=(int)$req->input("jumlah");
        $penjualan->tgl_penjualan=date('Y-m-d H:i:s');
        $simpan=$penjualan->save();
        if($simpan){
          $barang=Barang::find($req->input('id_barang'));
          $barang->stok=$cekStok[0]['stok']-$req->input("jumlah");
          $simpan=$barang->save();
          return Response::json(['message'=>'save successfull'],'200');
        }
        else{
          return Response::json(['message'=>'error'],'502');
        }
      }
      else{
        return Response::json(['message'=>['STOK TIDAK CUKUP']],'502');
      }
    }
  }
}
