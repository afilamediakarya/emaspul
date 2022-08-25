<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\document;
use Illuminate\Support\Facades\Storage;
use Str;
use Auth;

class rkpdController extends Controller
{
    public function index(){
        $breadcumb = 'Dokumen Daerah';
        $current_breadcumb = 'RKPD';
        $role = Auth::user()->id_role;
        return view('module.admin.dokumen_daerah.rkpd.index',compact('breadcumb','current_breadcumb','role'));
    }

    public function index_(){
        $breadcumb = 'Dokumen Daerah';
        $current_breadcumb = 'Lainnya';
        $role = Auth::user()->id_role;
        return view('module.admin.dokumen_daerah.lainnya',compact('breadcumb','current_breadcumb','role'));
    }
}
