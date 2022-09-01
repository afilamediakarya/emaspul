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
            
        } 

        return response()->json($data);
    }
}
