<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use Validator;
class userC extends Controller
{
  public function index(){
    $activeMenu="User";
    return view('admin/user/index',compact('activeMenu'));
  }
  public function readPage($halaman){
    $limit=10;
    $offset=$limit*($halaman-1);
    $jumlahData=User::selectRaw('count(*) as jml')->get();
    $data=User::selectRaw('*')->limit($limit)->offset($offset)->get();
    $maxPage=ceil($jumlahData[0]['jml']/$limit);
    return Response::json([
      'data'=>$data,
      'data-count'=>$jumlahData[0]['jml'],
      'max-page'=>$maxPage
    ],200);
  }
  public function readOne($id_user){
    $data=User::selectRaw('*')->whereRaw('id_user=?',[$id_user])->get();
    if(count($data)==0){
      return Response::json(['message'=>'DATA NOT FOUND'],404);
    }
    else{
      $data[0]['gambar']=url('public/images/products/'.$data[0]["id_user"].".jpg?e=".Str::random(5));
      return Response::json([
      'data'=>$data
    ],200);
    }

  }
  public function create(Request $req){
    $validator = Validator::make($req->all(),[
      'username' => 'required',
      'password' => 'required|',
      'konfirmasi' => 'required|same:password',
      'type' => 'required',
    ],[
      'username.required'=>'Username Tidak Boleh Kosong',
      'password.required'=>'Password Tidak Boleh Kosong',
      'konfirmasi.required'=>'Password konfirmasi tidak boleh kosong',
      'konfirmasi.same'=>'Password konfirmasi tidak sama',
      'type.required'=>'Masukkan Tipe User'
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $id_user=Str::random(5);
      $user=new User;
      $user->id_user=$id_user;
      $user->username=$req->input("username");
      $user->password=password_hash($req->input("password"),PASSWORD_DEFAULT);
      $user->type=$req->input("type");
      $simpan=$user->save();
      if($simpan){
        return Response::json(['message'=>'DATA SUDAH DISIMPAN'],'200');
      }
      else{
        return Response::json(['message'=>'error'],'502');
      }
    }
  }
  public function update($id_user,Request $req){
    $validator = Validator::make($req->all(),[
      'username' => 'required',
      'password' => 'required|',
      'konfirmasi' => 'required|same:password',
      'type' => 'required',
    ],[
      'username.required'=>'Username Tidak Boleh Kosong',
      'password.required'=>'Password Tidak Boleh Kosong',
      'konfirmasi.required'=>'Password konfirmasi tidak boleh kosong',
      'konfirmasi.same'=>'Password konfirmasi tidak sama',
      'type.required'=>'Masukkan Tipe User'
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $user=User::find($id_user);
      $user->id_user=$id_user;
      $user->username=$req->input("username");
      $user->password=(float)$req->input("password");
      $user->type=(int)$req->input("type");
      $simpan=$user->save();
      if($simpan){
        return Response::json(['message'=>'save successfull'],'200');
      }
      else{
        return Response::json(['message'=>'error'],'502');
      }
    }
  }
  public function updateData($id_user,Request $req){
    $validator = Validator::make($req->all(),[
      'username' => 'required',
      'type'=>'required'
    ],[
      'username.required'=>'Username Tidak Boleh Kosong',
      'type.required'=>'Tipe Tidak Boleh Kosong',
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $cekUser=User::selectRaw("COUNT(*) AS jml")->whereRaw("username=?",[$req->input('username')])->get();
      $dataLama=User::whereRaw("id_user=?",[$id_user])->get();
      if(($cekUser[0]['jml']>0)&&($dataLama[0]['username']!=$req->input('username'))){
        return Response::json(['message'=>['NAMA SUDAH ADA']],'502');
      }
      else{
        $user=User::find($id_user);
        $user->username=$req->input('username');
        $user->type=$req->input('type');
        $simpan=$user->save();
        if($simpan){
          return Response::json(['message'=>'save successfull'],'200');
        }
        else{
          return Response::json(['message'=>'error'],'502');
        }

      }
    }
  }
  public function updatePassword($id_user,Request $req){
    $validator = Validator::make($req->all(),[
      'password' => 'required|',
      'konfirmasi' => 'required|same:password',
    ],[
      'password.required'=>'Password Tidak Boleh Kosong',
      'konfirmasi.required'=>'Password konfirmasi tidak boleh kosong',
      'konfirmasi.same'=>'Password konfirmasi tidak sama',
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $user=User::find($id_user);
      $user->password=password_hash($req->input("password"),PASSWORD_DEFAULT);
      $simpan=$user->save();
      if($simpan){
        return Response::json(['message'=>'save successfull'],'200');
      }
      else{
        return Response::json(['message'=>'error'],'502');
      }
    }
  }
  public function delete($id_user,Request $req){
    $user=User::find($id_user);
    $simpan=$user->delete();
    if($simpan){
      return Response::json(['message'=>'DATA BERHASIL DIHAPUS'],'200');
    }
    else{
      return Response::json(['message'=>'error'],'502');
    }
  }
}
