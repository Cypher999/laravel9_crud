<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use Validator;
class ubah_passwordC extends Controller
{
  public function index(){
    $activeMenu="DataSaya";
    return view('user/ubah_password/index',compact('activeMenu'));
  }
  public function updatePassword(Request $req){
    $validator = Validator::make($req->all(),[
      'lama' => 'required',
      'password' => 'required',
      'konfirmasi' => 'required|same:password',
    ],[
      'lama.required'=>'Password Lama Tidak Boleh Kosong',
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
      $passwordLama=User::whereRaw('id_user=?',[$req->session()->get('id_user_laravel_crud')])->get();
      if(password_verify($req->get('lama'),$passwordLama[0]['password'])){
          $user=User::find($req->session()->get('id_user_laravel_crud'));
          $user->password=password_hash($req->input("password"),PASSWORD_DEFAULT);
          $simpan=$user->save();
          if($simpan){
            return Response::json(['message'=>'save successfull'],'200');
          }
          else{
            return Response::json(['message'=>'error'],'502');
          }
      }
      else{
            return Response::json(['message'=>['password lama tidak sama']],'502');
          }

    }
  }
}
