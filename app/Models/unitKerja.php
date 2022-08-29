<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unitKerja extends Model
{
    use HasFactory;
    protected $table = 'unit_kerja';
    
    public function unitBidang(){
        return $this->belongsTo(unitBidang::class,'id','id_perangkat');
    }

    public function BidangUrusan()
    {
        return $this->belongsToMany(bidangUrusan::class, 'unit_kerja_bidang_urusan', 'id_unit_kerja', 'id_bidang_urusan');
    }

    public function User(){
        return $this->hasMany(User::class,'id_unit_kerja');
    }
}
