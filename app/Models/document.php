<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document extends Model
{
    use HasFactory;
    protected $table = 'documents';

    public function verifikator(){
        return $this->belongsTo(User::class,'id_verifikator','id');
    }
 
}
