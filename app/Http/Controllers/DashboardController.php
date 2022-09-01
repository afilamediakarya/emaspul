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
            $data = DB::select("select COUNT(*) AS jumlah_paket, SUM(pagu) AS total_pagu_paket from alokasi_desa where tahun=".
            session('tahun_penganggaran')." and id_perangkat_desa=".Auth::user()->id_unit_kerja);

            $add = DB::table('pagu_desa')->select('pagu_desa')->where('id_perangkat_desa',Auth::user()->id_unit_kerja)->first()->pagu_desa;
            // $data[0]['total_add'] =;
            foreach ($data as $key => $value) {
               $value->total_add =  $add;
            }

            
        }else if($current == '4'){
            // return Auth::user()->id_unit_kerja;
            $data['jml_skpd'] = DB::table('unit_bidang_verifikasi')->where('id_bidang',Auth::user()->id_unit_kerja)->get()->count();
            $data['jml_dokumen'] = DB::table('documents')->select('documents.id')->join('unit_bidang_verifikasi','unit_bidang_verifikasi.id_perangkat','=','documents.id_perangkat')->where('documents.jenis_document','<=','4')->where('unit_bidang_verifikasi.id_bidang',Auth::user()->id_unit_kerja)->where('documents.tahun',session('tahun_penganggaran'))->get()->count();
            $data['jml_verifikator'] = DB::table('user')->where('id_unit_kerja',Auth::user()->id_unit_kerja)->get()->count();
        }else if($current == '2'){
          
            $counts = array();
            $total_pagu = 0;

            $perangkat_desa = DB::table('user')->select('perangkat_desa.id_desa')->join('perangkat_desa','user.id_unit_kerja','perangkat_desa.id')->where('user.id',Auth::user()->id)->first();

            $result = [];
            $paguPaket = DB::table('unit_kerja')->select('id','nama_unit_kerja')->whereRaw("id<>''")->get();
            $tahun = session('tahun_penganggaran');

            foreach($paguPaket as $unit_kerja_list){
                $unit_kerja_list->Dpa=DB::table('dpa')
                ->join('sub_kegiatan', 'dpa.id_sub_kegiatan', '=', 'sub_kegiatan.id')
                ->join('pegawai_penanggung_jawab', 'dpa.id_pegawai_penanggung_jawab', '=', 'pegawai_penanggung_jawab.id')
                ->select('dpa.id','dpa.nilai_pagu_dpa','sub_kegiatan.nama_sub_kegiatan','sub_kegiatan.kode_sub_kegiatan','pegawai_penanggung_jawab.nama_lengkap as ppk')
                ->where('dpa.tahun',$tahun)
                ->where('dpa.id_unit_kerja',$unit_kerja_list->id)
                ->get();

                foreach ( $unit_kerja_list->Dpa as $dpa ){
                    
                $dpa->Paket1=DB::table('paket_dau')
                    ->selectRaw("paket_dau.id,paket_dau.satuan,paket_dau.id_sumber_dana_dpa,paket_dau.nama_paket,paket_dau.volume,paket_dau.pagu,paket_dau.keterangan,@jenis_paket:='dau' as jenis_paket,sumber_dana_dpa.sumber_dana")
                    ->join('sumber_dana_dpa','sumber_dana_dpa.id','=','paket_dau.id_sumber_dana_dpa')
                    ->whereRaw("paket_dau.id_dpa='$dpa->id'");
                    $dpa->Paket=DB::table('paket_dak')
                    ->selectRaw("paket_dak.id,paket_dak.satuan,paket_dak.id_sumber_dana_dpa,paket_dak.nama_paket,paket_dak.volume,paket_dak.anggaran_dak as pagu,@keterangan:='' as keterangan,@jenis_paket:='dak' as jenis_paket,sumber_dana_dpa.sumber_dana")
                    ->join('sumber_dana_dpa','sumber_dana_dpa.id','=','paket_dak.id_sumber_dana_dpa')
                    ->whereRaw("paket_dak.id_dpa='$dpa->id' AND sumber_dana_dpa.jenis_belanja='Belanja Modal'")
                    ->union($dpa->Paket1)->get();

                    foreach($dpa->Paket as $paket){
                        if($paket->jenis_paket=='dau'){
                            $paket->Lokasi=DB::table('paket_dau_lokasi')
                            ->join('desa','paket_dau_lokasi.id_desa','=','desa.id')
                            ->join('kecamatan','desa.id_kecamatan','=','kecamatan.id')
                            ->select('desa.nama as nama_desa','kecamatan.nama as nama_kecamatan')
                            ->whereRaw("id_paket_dau='$paket->id' AND desa.id=".$perangkat_desa->id_desa)
                            ->get();
                        }else{
                            $paket->Lokasi=DB::table('paket_dak_lokasi')
                            ->join('desa','paket_dak_lokasi.id_desa','=','desa.id')
                            ->join('kecamatan','desa.id_kecamatan','=','kecamatan.id')
                            ->select('desa.nama as nama_desa','kecamatan.nama as nama_kecamatan')
                            ->whereRaw("id_paket_dak='$paket->id' AND desa.id=".$perangkat_desa->id_desa)
                            ->get();
                        }
                        $paket->Desa='';
                        $paket->Kecamatan='';
                        foreach($paket->Lokasi as $lokasi){
                            $paket->Desa.=$lokasi->nama_desa;
                            $paket->Kecamatan.=$lokasi->nama_kecamatan;

                        }
                    }
                }

            }

            foreach ( $paguPaket as $key => $res ){
                
                foreach ($res->Dpa as $in => $dpa ){
                    foreach ( $dpa->Paket as $i => $paket ){
                        if($paket->Desa=='' || $paket->Kecamatan==''){
                            continue; 
                        }
                    $counts[] = $res->nama_unit_kerja;
                    $total_pagu += $paket->pagu;
                    }
                }
            }


            $data['jml_dokumen'] = DB::table('documents')->select('documents.id')->where('user_insert',Auth::user()->id)->where('documents.tahun',session('tahun_penganggaran'))->get()->count();
            $data['jml_paket'] = count($counts);
            $data['total_pagu'] = 'Rp. '.number_format($total_pagu);
        } 

        return response()->json($data);
    }
}
