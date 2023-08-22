<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table='user';
    protected $primaryKey='id_user';
    public $incrementing=false;
    public $timestamps=false;
    protected $keyType='string';
    protected $fillable=['id_user','username','password','type'];
    public function Penjualans()
    {
      return $this->hasMany(Penjualan::class);
    }
}
