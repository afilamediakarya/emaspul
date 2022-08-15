<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perangkat_desa extends Model
{
    use HasFactory;
    protected $table = 'perangkat_desa';

    public function perangkat_desa(){
        return $this->belongsTo(unitBidang::class,'id','id_perangkat');
    }
    
}
