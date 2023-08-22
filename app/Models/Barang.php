<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table='barang';
    protected $primaryKey='id_barang';
    public $incrementing=false;
    public $timestamps=false;
    protected $keyType='string';
    protected $fillable=[
      'id_barang','nama_barang','harga','stok'
    ];
    public function Penjualans()
    {
      return $this->hasMany(Penjualan::class);
    }
}
