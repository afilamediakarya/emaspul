<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unitBidang extends Model
{
    use HasFactory;
    protected $table = 'unit_bidang_verifikasi';
    protected $with = ['perangkat_desa'];
    public function bidangVerifikator(){
        return $this->hasMany(unitBidang::class,'id','id_bidang');
    }

    public function perangkat_desa(){
        return $this->hasOne(perangkat_desa::class,'id','id_perangkat');
    }
}
