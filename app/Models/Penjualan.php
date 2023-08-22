<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table='penjualan';
    protected $primaryKey='id_penjualan';
    public $incrementing=false;
    public $timestamps=false;
    protected $keyType='string';
    protected $fillable=['id_penjualan','id_barang','id_user','jumlah','tgl_penjualan'];
    public function Barang()
    {
      return $this->belongsTo(Barang::class);
    }
    public function User()
    {
      return $this->belongsTo(User::class);
    }
}
