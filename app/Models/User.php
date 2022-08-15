<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'user';

    public function document(){
        return $this->belongsTo(document::class,'id','id_verifikator');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'nama_lengkap',
        'username',
        'password',
//        'id_pegawai',
        'nip',
        'jabatan',
        'no_telp',
        'id_unit_kerja',
        'id_role',
        'status',
        'user_insert',
        'user_update',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
