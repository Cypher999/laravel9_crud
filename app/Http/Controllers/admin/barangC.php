<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use Validator;
class barangC extends Controller
{
  public function index(){
    $activeMenu="Barang";
    return view('admin/barang/index',compact('activeMenu'));
  }
  public function readPage($halaman){
    $limit=10;
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
  public function create(Request $req){
    $validator = Validator::make($req->all(),[
      'nama_barang' => 'required',
      'harga' => 'required|numeric',
      'stok' => 'required|numeric',
      'file' => 'required|image|mimes:jpeg,jpg,png|max:10000',
    ],[
      'nama_barang.required'=>'Nama Barang Tidak Boleh Kosong',
      'harga.required'=>'Harga Barang Tidak Boleh Kosong',
      'harga.numeric'=>'Harga harus berbentuk angka',
      'stok.required'=>'Stok Tidak Boleh Kosong',
      'stok.numeric'=>'Stok harus berbentuk angka',
      'file.required'=>'gambar tidak boleh kosong',
      'file.image'=>'file harus bertipe image',
      'file.mimes'=>'file harus berformat jpg,jpeg dan png',
      'file.max'=>'maksimal file 10 MB'
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $id_barang=Str::random(5);
      $barang=new Barang;
      $barang->id_barang=$id_barang;
      $barang->nama_barang=$req->input("nama_barang");
      $barang->harga=(float)$req->input("harga");
      $barang->stok=(int)$req->input("stok");
      $simpan=$barang->save();
      if($simpan){
        $req->file->move(public_path('images/products'),$id_barang.".jpg");
        return Response::json(['message'=>'save successfull'],'200');
      }
      else{
        return Response::json(['message'=>'error'],'502');
    }
    }
    $data=User::whereRaw('username=?',[$req->input('username')])->get();


  }
  public function update($id_barang,Request $req){
    $validator = Validator::make($req->all(),[
      'nama_barang' => 'required',
      'harga' => 'required|numeric',
      'stok' => 'required|numeric',
      'file' => 'image|mimes:jpeg,jpg,png|max:10000',
    ],[
      'nama_barang.required'=>'Nama Barang Tidak Boleh Kosong',
      'harga.required'=>'Harga Barang Tidak Boleh Kosong',
      'harga.numeric'=>'Harga harus berbentuk angka',
      'stok.required'=>'Stok Tidak Boleh Kosong',
      'stok.numeric'=>'Stok harus berbentuk angka',
      'file.image'=>'file harus bertipe image',
      'file.mimes'=>'file harus berformat jpg,jpeg dan png',
      'file.max'=>'maksimal file 10 MB'
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $barang=Barang::find($id_barang);
      $barang->id_barang=$id_barang;
      $barang->nama_barang=$req->input("nama_barang");
      $barang->harga=(float)$req->input("harga");
      $barang->stok=(int)$req->input("stok");
      $simpan=$barang->save();
      if($simpan){
        if($req->file('file')!=null){
          unlink(public_path('images/products/'.$id_barang.".jpg"));
          $req->file->move(public_path('images/products'),$id_barang.".jpg");
        }

        return Response::json(['message'=>'save successfull'],'200');
      }
      else{
        return Response::json(['message'=>'error'],'502');
    }
    }


  }
  public function delete($id_barang,Request $req){
    $barang=Barang::find($id_barang);
    $simpan=$barang->delete();
    if($simpan){
      unlink(public_path('images/products/'.$id_barang.".jpg"));

      return Response::json(['message'=>'DATA BERHASIL DIHAPUS'],'200');
    }
    else{
      return Response::json(['message'=>'error'],'502');
    }
  }
}
