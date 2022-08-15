<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verifikasi_document extends Model
{
    use HasFactory;
    protected $table = 'verifikasi_documents';

    public function master_verifikasi(){
        return $this->belongsTo(master_verifikasi::class,'id_master_verifikasi','id');
    }
}
