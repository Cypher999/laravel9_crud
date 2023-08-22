<?php

namespace App\Http\Controllers\no_login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\User;
class loginC extends Controller
{
  public function index(){
    return view('no_login/login/index');
  }
}
