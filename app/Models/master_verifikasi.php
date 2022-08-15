<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_verifikasi extends Model
{
    use HasFactory;
    protected $table = 'master_verifikasi';

    public function verifikasi_document(){
        return $this->belongsTo(verifikasi_document::class,'id','id_master_verifikasi');
    }
}
