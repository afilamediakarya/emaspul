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
        return view('module.admin.dokumen_daerah.rkpd.index',compact('breadcumb','current_breadcumb'));
    }

    public function index_(){
        $breadcumb = 'Dokumen Daerah';
        $current_breadcumb = 'Lainnya';
        return view('module.admin.dokumen_daerah.lainnya',compact('breadcumb','current_breadcumb'));
    }
}
