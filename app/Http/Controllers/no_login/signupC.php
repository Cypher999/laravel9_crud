<?php

namespace App\Http\Controllers\no_login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Validator;
class signupC extends Controller
{
  public function index(Request $req){
    if($req->session()->get('id_user_laravel_crud')==null){
      return view('no_login/signup/index');
    }
    else{
      echo "anda sudah login sebagai ".$req->session()->get('id_user_laravel_crud');
    }
  }
  public function doSignup(Request $req){
    $req->validate([
      'username' => 'required',
      'password' => 'required',
      'konfirmasi' => 'required|same:password',
    ],[
      'username.required'=>'MASUKKAN USERNAME',
      'password.required'=>'MASUKKAN PASSWORD',
      'konfirmasi.required'=>'MASUKKAN PASSWORD KONFIRMASI',
      'konfirmasi.same'=>'PASSWORD KONFIRMASI TIDAK SAMA',
    ]);
    $data=User::selectRaw("COUNT(*) AS jml")->whereRaw("username=?",[$req->input('username')])->get();
    if($data[0]['jml']>0){
      return back()->with('customErrors',['Username Sudah Ada']);
    }
    else{
      $id_user=Str::random(5);
      $user=new User();
      $user->id_user=$id_user;
      $user->username=$req->input('username');
      $user->password=password_hash($req->input('password'),PASSWORD_DEFAULT);
      $user->type="U";
      $user->save();
      $req->session()->put('id_user_laravel_crud',$id_user);
      return redirect('user/home');
    }
  }
}
