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
}
