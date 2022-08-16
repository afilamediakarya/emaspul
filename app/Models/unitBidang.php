<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unitBidang extends Model
{
    use HasFactory;
    protected $table = 'unit_bidang_verifikasi';
    protected $with = ['perangkat'];

    public function bidangVerifikator(){
        return $this->hasMany(unitBidang::class,'id','id_bidang');
    }

    public function perangkat(){ 
        return $this->hasOne(unitKerja::class,'id','id_perangkat');
    }
}
