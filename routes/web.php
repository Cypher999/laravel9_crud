<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\no_login\loginC::class,'index']);
Route::get('/signup', [App\Http\Controllers\no_login\signupC::class,'index']);
Route::post('/signup', [App\Http\Controllers\no_login\signupC::class,'doSignup']);
Route::post('login',[App\Http\Controllers\no_login\loginC::class,'doLogin']);
Route::get('logout',[App\Http\Controllers\no_login\loginC::class,'logout']);
Route::prefix('admin')->middleware(App\Http\Middleware\AdminFilter::class)->group(function(){
  Route::prefix('home')->group(function(){
    Route::get('/',[App\Http\Controllers\admin\dashboardC::class,'index']);
    Route::get('/readAllData',[App\Http\Controllers\admin\dashboardC::class,'readAllData']);
  });
  Route::prefix('barang')->group(function(){
    Route::get('/',[App\Http\Controllers\admin\barangC::class,'index']);
    Route::post('/',[App\Http\Controllers\admin\barangC::class,'create']);
    Route::get('/readPage/{halaman}',[App\Http\Controllers\admin\barangC::class,'readPage']);
    Route::get('/{id_barang}',[App\Http\Controllers\admin\barangC::class,'readOne']);
    Route::put('/{id_barang}',[App\Http\Controllers\admin\barangC::class,'update']);
    Route::delete('/{id_barang}',[App\Http\Controllers\admin\barangC::class,'delete']);
  });
  Route::prefix('user')->group(function(){
    Route::get('/',[App\Http\Controllers\admin\userC::class,'index']);
    Route::post('/',[App\Http\Controllers\admin\userC::class,'create']);
    Route::get('/readPage/{halaman}',[App\Http\Controllers\admin\userC::class,'readPage']);
    Route::get('/{id_user}',[App\Http\Controllers\admin\userC::class,'readOne']);
    Route::put('/updateData/{id_user}',[App\Http\Controllers\admin\userC::class,'updateData']);
    Route::put('/updatePassword/{id_user}',[App\Http\Controllers\admin\userC::class,'updatePassword']);
    Route::delete('/{id_user}',[App\Http\Controllers\admin\userC::class,'delete']);
  });
  Route::prefix('penjualan')->group(function(){
    Route::get('/',[App\Http\Controllers\admin\penjualanC::class,'index']);
    Route::get('/readPage/{halaman}',[App\Http\Controllers\admin\penjualanC::class,'readPage']);
    Route::delete('/{id_penjualan}',[App\Http\Controllers\admin\penjualanC::class,'delete']);
  });
});
Route::prefix('user')->middleware(App\Http\Middleware\UserFilter::class)->group(function(){
  Route::prefix('home')->group(function(){
    Route::get('/readPage/{halaman}',[App\Http\Controllers\user\dashboardC::class,'readPage']);
    Route::get('/',[App\Http\Controllers\user\dashboardC::class,'index']);
    Route::post('/pesan_barang',[App\Http\Controllers\user\dashboardC::class,'pesan_barang']);
    Route::get('/{id_barang}',[App\Http\Controllers\user\dashboardC::class,'readOne']);
  });
  Route::prefix('riwayat_pembelian')->group(function(){
    Route::get('/',[App\Http\Controllers\user\riwayatC::class,'index']);
    Route::get('/readPage/{halaman}',[App\Http\Controllers\user\riwayatC::class,'readPage']);
    Route::delete('/{id_penjualan}',[App\Http\Controllers\user\riwayatC::class,'delete']);

  });
  Route::prefix('ubah_nama')->group(function(){
    Route::get('/',[App\Http\Controllers\user\ubah_namaC::class,'index']);
    Route::post('/',[App\Http\Controllers\user\ubah_namaC::class,'updateNama']);
  });
  Route::prefix('ubah_password')->group(function(){
    Route::get('/',[App\Http\Controllers\user\ubah_passwordC::class,'index']);
    Route::post('/',[App\Http\Controllers\user\ubah_passwordC::class,'updatePassword']);
  });
});
Route::prefix('testing')->group(function(){
  Route::get('/readAll',[App\Http\Controllers\no_login\loginC::class,'readAll']);
  Route::get('/readOne/{id}',[App\Http\Controllers\no_login\loginC::class,'readOne']);
  Route::get('/create/{nama_barang}/{harga}/{stok}',[App\Http\Controllers\no_login\loginC::class,'create']);
  Route::get('/update/{id_barang}/{nama_barang}/{harga}/{stok}',[App\Http\Controllers\no_login\loginC::class,'update']);
  Route::get('/delete/{id_barang}',[App\Http\Controllers\no_login\loginC::class,'delete']);

});