<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard_admin(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '';
        session(['tahun_penganggaran' => request('tahun', date('Y'))]);
        return view('module.dashboard.index',compact('breadcumb','current_breadcumb'));
    }

    public function dashboard_admin_opd(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '-';
        session(['tahun_penganggaran' => request('tahun', date('Y'))]);
        return view('module.dashboard.opd',compact('breadcumb','current_breadcumb'));
    }

    public function dashboard_admin_desa(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '-';
        session(['tahun_penganggaran' => request('tahun', date('Y'))]);
        return view('module.dashboard.desa',compact('breadcumb','current_breadcumb'));
    }

    public function dashboard_admin_verifikator(){
        $breadcumb = 'Dashboard';
        $current_breadcumb = '-';
        session(['tahun_penganggaran' => request('tahun', date('Y'))]);
        return view('module.dashboard.verifikator',compact('breadcumb','current_breadcumb'));
    }

    public function get_dashboard(){
      
        $current = Auth::user()->id_role;
        $data = array();
        if ($current == '3') {
            $data = DB::select("select COUNT(*) AS jumlah_paket, SUM(pagu) AS total_pagu_paket from alokasi_desa where tahun=".session('tahun_penganggaran')." and id_perangkat_desa=".Auth::user()->id_unit_kerja);
        }else if($current == '4'){
            // return Auth::user()->id_unit_kerja;
            $data['jml_skpd'] = DB::table('unit_bidang_verifikasi')->where('id_bidang',Auth::user()->id_unit_kerja)->get()->count();
            $data['jml_dokumen'] = DB::table('documents')->select('documents.id')->join('unit_bidang_verifikasi','unit_bidang_verifikasi.id_perangkat','=','documents.id_perangkat')->where('documents.jenis_document','<=','4')->where('unit_bidang_verifikasi.id_bidang',Auth::user()->id_unit_kerja)->where('documents.tahun',session('tahun_penganggaran'))->get()->count();
            $data['jml_verifikator'] = DB::table('user')->where('id_unit_kerja',Auth::user()->id_unit_kerja)->get()->count();
        } 

        return response()->json($data);
    }
}
