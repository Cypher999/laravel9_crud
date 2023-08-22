<?php

namespace App\Http\Controllers\no_login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Validator;
class loginC extends Controller
{
  public function index(Request $req){
    if($req->session()->get('id_user_laravel_crud')==null){
      return view('no_login/login/index');
    }
    else{
      echo "anda sudah login sebagai ".$req->session()->get('id_user_laravel_crud');
    }
  }
  public function logout(Request $req){
    $req->session()->forget('id_user_laravel_crud');
    return redirect('/');
  }
  //jika ingin nilai return error berbentuk json
  // public function doLogin(Request $req){
  //   $validator = Validator::make($req->all(), [
  //     'username' => 'required|min:50',
  //     'password' => 'required',
  //   ],[
  //     'username.required'=>'MASUKKAN USERNAME',
  //     'username.min'=>'USERNAME HARUS LEBIH DARI 50',
  //     'password.required'=>'MASUKKAN PASSWORD'
  //   ]);
  //   if ($validator->fails()) {
  //     $konversi_validator=json_encode($validator->messages('username'));
  //     $konversi_validator=json_decode($konversi_validator);
  //     return Response::json($konversi_validator,200);
  //   }
  //   $data=User::whereRaw('username=?',[$req->input('username')])->get();
  //   return Response::json($data,200);
  // }
  //
  public function doLogin(Request $req){
    $req->validate([
      'username' => 'required',
      'password' => 'required',
    ],[
      'username.required'=>'MASUKKAN USERNAME',
      'password.required'=>'MASUKKAN PASSWORD'
    ]);
    $data=User::whereRaw('username=?',[$req->input('username')])->get();
    if(count($data)<=0){
      return back()->with('customErrors',['Username Tidak Ditemukan']);
    }
    else{
      if(password_verify($req->input('password'),$data[0]['password'])){
        $req->session()->put('id_user_laravel_crud',$data[0]['id_user']);
        if($data[0]['type']=='A'){
          return redirect('admin/home');
        }
        else{
          return redirect('user/home');
        }
      }
      else{
        return back()->with('customErrors',['Password Salah']);
      }
    }
  }
  protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        return response()->json($e->validator->errors()->getMessages(), 422);
    }

  public function readAll(){
    // echo $params;
    $data=Barang::all();
    return Response::json($data,'200');
  }
  public function readOne($id){
    // echo $params;
    // $data=Barang::where('id_barang','=',$id)->get();
    // $data=Barang::whereRaw('id_barang = ?',[$id])->get();
    $data=Barang::selectRaw('id_barang,nama_barang,harga,stok')->whereRaw('id_barang =?',[$id])->get();
    // untuk menggunakan where, cukup rubah whereRaw
    return Response::json($data,'200');
  }
  public function create($nama_barang,$harga,$stok){
    $barang=new Barang;
    $barang->id_barang=Str::random(5);
    $barang->nama_barang=$nama_barang;
    $barang->harga=(float)$harga;
    $barang->stok=(int)$stok;
    $simpan=$barang->save();
    if($simpan){
      return Response::json(['message'=>'save successfull'],'200');
    }
    else{
      return Response::json(['message'=>'error'],'502');
    }
  }
  public function update($id_barang,$nama_barang,$harga,$stok){
    $barang=Barang::find($id_barang);
    $barang->nama_barang=$nama_barang;
    $barang->harga=(float)$harga;
    $barang->stok=(int)$stok;
    $simpan=$barang->save();
    if($simpan){
      return Response::json(['message'=>'update successfull'],'200');
    }
    else{
      return Response::json(['message'=>'error'],'502');
    }
  }
  public function delete($id_barang){
    $barang=Barang::find($id_barang);
    $simpan=$barang->delete();
    if($simpan){
      return Response::json(['message'=>'delete successfull'],'200');
    }
    else{
      return Response::json(['message'=>'error'],'502');
    }
  }
}
