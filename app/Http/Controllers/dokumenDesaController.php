<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class dokumenDesaController extends Controller
{
    public function index(){
        $type = request('type');
        if ($type == 'RPJMDes') {
            $breadcumb = 'Dokumen RPJMDes';
            $current_breadcumb = '';
            return view('module.desa.dokumen.rpjmdes',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'RKPDes') {
            $breadcumb = 'Dokumen RKPDes';
            $current_breadcumb = '';
            return view('module.desa.dokumen.rkpdes',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'SDGS') {
            $breadcumb = 'Dokumen SDGs Desa';
            $current_breadcumb = '';
            return view('module.desa.dokumen.sdgs',compact('breadcumb','current_breadcumb'));
        }
    }

    public function index_verifikator(){
        $type = request('type');
        if ($type == 'RPJMDes') {
            $breadcumb = 'Dokumen Desa';
            $current_breadcumb = 'RPJMDes';
            return view('module.desa.dokumen.partials.rpjmdes',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'RKPDes') {
            $breadcumb = 'Dokumen Desa';
            $current_breadcumb = 'RKPDes';
            return view('module.desa.dokumen.partials.rkpdes',compact('breadcumb','current_breadcumb'));
        }
        if ($type == 'SPGs') {
            $breadcumb = 'Dokumen SPGs Desa';
            $current_breadcumb = '';
            return view('module.desa.dokumen.partials.spgs',compact('breadcumb','current_breadcumb'));
        }
    }

    public function verifikasi(){
        $document = request('document');
        $jenis = request('jenis');
        $breadcumb = 'Dokumen Desa';
        $current_breadcumb = 'Verifikasi';
        return view('module.desa.dokumen.partials.verifikasi',compact('breadcumb','current_breadcumb','document','jenis'));
    }
}
