<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dokumenOpdController extends Controller
{
    public function index(){
        $type = request('type');
        if ($type == 'Renstra') {
            $breadcumb = 'Dokumen Renstra';
            $current_breadcumb = '';
            return view('module.opd.dokumen.renstra',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'Renja') {
            $breadcumb = 'Dokumen Renja';
            $current_breadcumb = '';
            return view('module.opd.dokumen.renja',compact('breadcumb','current_breadcumb'));
        }
    }

    public function index_verifikator(){
        $type = request('type');
        if ($type == 'Renstra') {
            $breadcumb = 'Dokumen Renstra';
            $current_breadcumb = 'Renstra';
            return view('module.opd.dokumen.partials.renstra',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'Renja') {
            $breadcumb = 'Dokumen Renja';
            $current_breadcumb = 'Renja';
            return view('module.opd.dokumen.partials.renja',compact('breadcumb','current_breadcumb'));
        }
    }
}
