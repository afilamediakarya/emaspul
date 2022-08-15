<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bidangVerifikator extends Model
{
    use HasFactory;
    protected $table = 'bidang_verifikasi';
    
    public function uniBidang(){
        return $this->hasMany(unitBidang::class,'id_bidang','id');
    }
}
