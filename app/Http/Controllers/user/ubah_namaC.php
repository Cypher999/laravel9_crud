<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use Validator;
class ubah_namaC extends Controller
{
  public function index(Request $req){
    $activeMenu="DataSaya";
    $data=User::selectRaw('id_user,username')->whereRaw('id_user=?',[$req->session()->get('id_user_laravel_crud')])->get();
    return view('user/ubah_nama/index',compact('data','activeMenu'));
  }
  public function updateNama(Request $req){
    $validator = Validator::make($req->all(),[
      'username' => 'required'
    ],[
      'username.required'=>'Username Tidak Boleh Kosong'
    ]);
    if ($validator->fails()) {
      $konversi_validator=json_encode($validator->messages());
      $konversi_validator=json_decode($konversi_validator);
      return Response::json($konversi_validator,502);
    }
    else{
      $cekUser=User::selectRaw("COUNT(*) AS jml")->whereRaw("username=?",[$req->input('username')])->get();
      $dataLama=User::whereRaw("id_user=?",[$req->session()->get('id_user_laravel_crud')])->get();
      if(($cekUser[0]['jml']>0)&&($dataLama[0]['username']!=$req->input('username'))){
        return Response::json(['message'=>['NAMA SUDAH ADA']],'502');
      }
      else{
        $user=User::find($req->session()->get('id_user_laravel_crud'));
        $user->username=$req->input('username');
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
}
