<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\document;
use Illuminate\Support\Facades\Storage;
use Str;
use Auth;
class rpjmdController extends Controller
{
    public function index(){
        $breadcumb = 'Dokumen Daerah';
        $current_breadcumb = 'RPJMD';
        $role = Auth::user()->id_role;
        return view('module.admin.dokumen_daerah.rpjmd.index',compact('breadcumb','current_breadcumb','role'));
    }

}
