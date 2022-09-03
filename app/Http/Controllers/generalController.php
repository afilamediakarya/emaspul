<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\document;
use App\Models\document_history;
use App\Models\master_verifikasi;
use App\Models\jadwal;
use App\Models\verifikasi_document;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\InfoRequest;
use App\Rules\MatchOldPassword;
use Str;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
class generalController extends Controller
{

    public function index(){
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->id_role == 1) {
                return redirect('dashboard-admin');
            } elseif ($user->id_role == 2) {
                return redirect('dashboard-admin-opd');
            }elseif ($user->id_role == 3) {
                return redirect('dashboard.admin_desa');
            }elseif ($user->id_role == 4) {
                return redirect('dashboard.admin.verifikator');
            }
        }else{
            return redirect('login');
        }
    }

    public function verifikasi(){
        $document = request('document');
        $jenis = request('jenis');
        $breadcumb = 'Dokumen '.$jenis;
        $current_breadcumb = 'Verifikasi';
        return view('module.admin.verifikasi',compact('breadcumb','current_breadcumb','document','jenis'));
    }

    public function get_desa(){
        $data = DB::table('desa')->select('id','nama as value')->get();
        return $data;
    }

    public function get_perangkat_desa(){
        $data = DB::table('perangkat_desa')->select('id','nama_desa as value')->get();
        return $data;
    }

    public function get_bidang(){
        $data = DB::table('bidang_verifikasi')->select('id','nama_bidang as value')->get();
        return $data;
    }

    public function get_unit_kerja(){
        $data = DB::table('unit_kerja')->select('id','nama_unit_kerja as value')->get();
        return $data;
    }

    public function get_satuan(){
        $data = DB::table('satuan')->select('nama_satuan as id','nama_satuan as value')->get();
        return $data;
    }

    public function pagu_desa(){
       $data = DB::table('pagu_desa')->select('pagu_desa as id','pagu_desa as value')->where('id_perangkat_desa',Auth::user()->id_unit_kerja)->where('tahun',session('tahun_penganggaran'))->get();
       return $data;
    }

    public function get_master_verifikasi($params){
       
        $data = verifikasi_document::select('verifikasi_documents.id','verifikasi_documents.id_documents','verifikasi_documents.id_master_verifikasi','verifikasi_documents.tindak_lanjut','verifikasi_documents.verifikasi','documents.jenis_document','documents.nama_documents')->with('master_verifikasi')->join('documents','verifikasi_documents.id_documents','=','documents.id')->where('id_documents',$params)->get();
        return $data;
    }

    public function checkJadwal(){
        $result = [];
        $data = jadwal::where('tahapan', request('tahapan'))
            ->where('sub_tahapan', request('sub_tahapan'))
            ->whereDate('jadwal_mulai', '<=', now())
            ->whereDate('jadwal_selesai', '>=', now())
            ->exists();

            $jadwal = jadwal::select('jadwal_mulai','jadwal_selesai')
            ->where('sub_tahapan', request('sub_tahapan'))
            // ->where('tahun', request('tahun_penganggaran'))
            ->first();

        
        return $result = [
            'status' => $data,
            'jadwal' => $jadwal
        ];
    }

    public function setTahunAnggaran(){
      
        // session(['tahun_penganggaran' => request('tahun')]);
        Session::put('tahun_penganggaran',request('tahun'));

        return redirect()->back();
    }

    public function datatable_list(){
        $jenis = request('jenis');
        $type = request('type');
        $document_type = request('document_type');
        $data = array();

        if ($type == 'type_a') {

            if (request('type_query') == 'query_2') {
                $data = document::select('id','nama_documents','nomor_perbub','tanggal_perbub','file_document')->where('jenis_document',$jenis)->where('tahun',session('tahun_penganggaran'))->latest()->get();
            }else{
                $data = document::select('id','nama_documents','nomor_perbub','tanggal_perbub','file_document')->where('jenis_document',$jenis)->where('id_perangkat',Auth::user()->id_unit_kerja)->where('tahun',session('tahun_penganggaran'))->latest()->get();
            }

            
        

        }else if($type == 'type_b'){
            // return Auth::user()->id_unit_kerja; 30
            $other_query = "";
            $queryByBidang = "";
            if (Auth::user()->id_role == 3) {
                $other_query = "AND documents.id_perangkat =".Auth::user()->id_unit_kerja;
            }

            if (Auth::user()->id_role == 4) {
                $queryByBidang = "INNER JOIN unit_bidang_verifikasi ON unit_bidang_verifikasi.id_perangkat = documents.id_perangkat";
            }

            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document, (SELECT perangkat_desa.nama_desa FROM perangkat_desa INNER JOIN user ON user.`id_unit_kerja`=perangkat_desa.`id` WHERE user.`id` = documents.`user_insert`) AS nama_desa, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents ".$queryByBidang." where jenis_document=".$jenis." ".$other_query." AND tahun=".session('tahun_penganggaran'));

        }else if($type == 'type_c'){
            $other_query = '';
        
            if (Auth::user()->id_role == 2) {
                $other_query = "AND documents.user_insert =".Auth::user()->id;
                $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,(SELECT unit_kerja.nama_unit_kerja FROM unit_kerja INNER JOIN user ON user.`id_unit_kerja`=unit_kerja.`id` WHERE user.`id` = documents.`user_insert`) AS nama_unit_kerja, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=".$jenis." ".$other_query." AND tahun=".session('tahun_penganggaran'));
            }elseif (Auth::user()->id_role == 1) {
                $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,(SELECT unit_kerja.nama_unit_kerja FROM unit_kerja INNER JOIN user ON user.`id_unit_kerja`=unit_kerja.`id` WHERE user.`id` = documents.`user_insert`) AS nama_unit_kerja, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=".$jenis." AND tahun=".session('tahun_penganggaran'));
            }else{
                if ($jenis == 3 || $jenis == 4) {
                 
                    $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,(SELECT unit_kerja.nama_unit_kerja FROM unit_kerja INNER JOIN user ON user.`id_unit_kerja`=unit_kerja.`id` WHERE user.`id` = documents.`user_insert`) AS nama_unit_kerja, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents INNER JOIN unit_bidang_verifikasi ON unit_bidang_verifikasi.id_perangkat = documents.id_perangkat where documents.jenis_document = ".$jenis." AND unit_bidang_verifikasi.id_bidang=".Auth::user()->id_unit_kerja." AND documents.tahun=".session('tahun_penganggaran'));
              
                }else{
                     
                        $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,(SELECT unit_kerja.nama_unit_kerja FROM unit_kerja INNER JOIN user ON user.`id_unit_kerja`=unit_kerja.`id` WHERE user.`id` = documents.`user_insert`) AS nama_unit_kerja, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents INNER JOIN unit_bidang_verifikasi ON unit_bidang_verifikasi.id_perangkat = documents.id_perangkat where documents.jenis_document = ".$jenis." AND unit_bidang_verifikasi.id_bidang=".Auth::user()->id_unit_kerja." AND documents.tahun=".session('tahun_penganggaran'));
                       
                }
            }
           
        }else{

            if (Auth::user()->id_role == 4) {
                
                $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,documents.user_insert, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents INNER JOIN unit_bidang_verifikasi ON unit_bidang_verifikasi.id_perangkat = documents.id_perangkat WHERE documents.jenis_document <= 4  AND unit_bidang_verifikasi.id_bidang=".Auth::user()->id_unit_kerja." AND documents.tahun=".session('tahun_penganggaran'));

                foreach ($data as $key => $value) {
                    if (strpos($value->nama_documents, 'Renstra') !== false || strpos($value->nama_documents, 'Renja') !== false) {
                        $value->unit_kerja = DB::table('unit_kerja')->select('unit_kerja.nama_unit_kerja')->join('user','user.id_unit_kerja','=','unit_kerja.id')->where('user.id',$value->user_insert)->first()->nama_unit_kerja;
                    }else{
                        $value->unit_kerja = DB::table('perangkat_desa')->select('perangkat_desa.nama_desa')->join('user','user.id_unit_kerja','=','perangkat_desa.id')->where('user.id',$value->user_insert)->first()->nama_desa;
                        
                    }
                }

            }else if(Auth::user()->id_role == 3){
                $data = DB::select("SELECT documents.id,documents.nama_documents, documents.status_document, documents.jenis_document, (SELECT user.nama_lengkap FROM user WHERE user.id = documents.id_verifikator) AS verifikator FROM documents WHERE (id_perangkat = ".Auth::user()->id_unit_kerja.") AND (jenis_document <= ".$document_type.")");
            }else if(Auth::user()->id_role == 2){
                $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,documents.user_insert, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents WHERE documents.jenis_document <= 4  AND documents.user_insert=".Auth::user()->id." AND documents.tahun=".session('tahun_penganggaran'));
            }else{
                $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document,documents.id_perangkat,documents.user_insert, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents WHERE documents.jenis_document <= ".$document_type." AND documents.tahun=".session('tahun_penganggaran'));

                foreach ($data as $key => $value) {
                    if (strpos($value->nama_documents, 'Renstra') !== false || strpos($value->nama_documents, 'Renja') !== false) {
                        $value->unit_kerja = DB::table('unit_kerja')->select('unit_kerja.nama_unit_kerja')->join('user','user.id_unit_kerja','=','unit_kerja.id')->where('user.id',$value->user_insert)->first()->nama_unit_kerja;
                    }else{
                        $value->unit_kerja = DB::table('perangkat_desa')->select('perangkat_desa.nama_desa')->join('user','user.id_unit_kerja','=','perangkat_desa.id')->where('user.id',$value->user_insert)->first()->nama_desa;
                        
                    }
                }
            }

             

            
        }   
       
        return response()->json([
            'type' => 'success',
            'status' => true,
            'data' => $data,
        ]);
    }

    public function verifikasiDocument($params,$id){
        $data = master_verifikasi::where('jenis_documents',$params)->get();
        foreach ($data as $key => $value) {
            $verifikasi = new verifikasi_document();
            $verifikasi->verifikasi = '0';
            $verifikasi->id_master_verifikasi = $value->id;
            $verifikasi->id_documents = $id;
            $verifikasi->user_insert = Auth::user()->id;
            $verifikasi->save();
        }

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'RPJMD Berhasil di Tambahkan',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'RPJMD Gagal di Proses',
            ],400);
        }
    }

    public function documentByVerifikasi(){
        // $result = array();
        $jenis = request('jenis');
        $document = request('document');
        $data = DB::select("SELECT documents.id,documents.nama_documents, documents.nomor_perbub,documents.tanggal_perbub,documents.status_document,  (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=".$jenis." AND id=".$document." LIMIT 1");

        $master_verifikasi = $this->get_master_verifikasi($document);

        return [
            'document' => $data,
            'master_verifikasi' => $master_verifikasi
        ];
    }

    public function create_nomor_konsederan($jenis_document, $jenis, $nama){
        $num = 0;
        $data = document::where('nama_documents',$nama)->where('jenis_document',$jenis_document)->where('tahun',session('tahun_penganggaran'))->whereNotNull('nomor_konsederan')->get()->count();

        if ($data >= 9) {
            $num = $data + 1;
        }else{
            $num = '0'.$data + 1;
        }
        

        $nomor_konsederan = '050/'.$num.'/'.$this->getBulanRomawi().'/verifikasi.'.$jenis.'/Bappelitbangda/'.date('Y');
        return $nomor_konsederan;
    }

    public function master_verifikasi(Request $request){
   
        $jenis = request('jenis');
        // return $jenis;
        $data = array();
        $url = '';
        $status_document = 1;
        foreach ($request->id as $key => $value) {
           $data = verifikasi_document::where('id',$value)->first();
           $data->verifikasi = $request->status[$key];
           $data->tindak_lanjut = $request->tindak_lanjut[$key];
           $data->save();
        }



        if (in_array("0", $request->status) == true && in_array("1", $request->status) == true){
            $status_document = 3;
        }
        else if(in_array("1", $request->status) == true && in_array("0", $request->status) == false){
            $status_document = 4;
        }	
        

        if ($data) {
            $document = document::where('id',request('document'))->first();
            $document->id_verifikator = Auth::user()->id;
            $document->status_document = $status_document;
            if (is_null($document->nomor_konsederan)) {
                $document->nomor_konsederan =  $this->create_nomor_konsederan($request->jenis_document,$jenis,$request->nama_documents);
            }
            $document->save();
        }

        if ($jenis == 'RPJMDes') {
            $url = '/dokumen-desa?type=RPJMDes';
        }elseif($jenis == 'RKPDes'){
            $url = '/dokumen-desa?type=RKPDes';
        }elseif($jenis == 'Renstra'){
            $url = '/dokumen-skpd?type=Renstra';
        }elseif($jenis == 'Renja'){
            $url = '/dokumen-skpd?type=Renja';
        }

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => $jenis.' Berhasil di Proses Verfikasi',
                'data' => $data,
                'url' => $url
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => $jenis.' Gagal di Proses',
            ],400);
        }
    }

    public function perform(InfoRequest $request) 
    {
        
    }

    public function check_files($params1, $params2) 
    {
        $data = document::where('nama_documents',$params1)->where('id_perangkat',Auth::user()->id_unit_kerja)->where('jenis_document',$params2)->where('user_insert',Auth::user()->id)->where('tahun',session('tahun_penganggaran'))->exists();
        return $data;
    }

    function getBulanRomawi(){
        $bulan = date ("m");
        switch($bulan){
            case '01':
                $bulan_romawi = "I";
            break;
     
            case '02':			
                $bulan_romawi = "II";
            break;
     
            case '03':
                $bulan_romawi = "III";
            break;
     
            case '04':
                $bulan_romawi = "IV";
            break;
     
            case '05':
                $bulan_romawi = "V";
            break;
     
            case '06':
                $bulan_romawi = "VI";
            break;
     
            case '07':
                $bulan_romawi = "VII";
            break;

            case '08':
                $bulan_romawi = "VIII";
            break;

            case '09':
                $bulan_romawi = "IX";
            break;

            case '10':
                $bulan_romawi = "X";
            break;

            case '11':
                $bulan_romawi = "XI";
            break;

            case '12':
                $bulan_romawi = "XII";
            break;
            
            default:
                $bulan_romawi = "Tidak di ketahui";		
            break;
        }
       return $bulan_romawi;
    }

    public function storeDocuments(Request $request){
        $jenis = request('jenis');
        $type = request('type');
        $data = array();
        $status_document = 0;
        $jenis_document = 0;


        if ($type == 'type_a') {
            $status_document = 4;
        }else{
            $status_document = 1;
        }

        if ($jenis == 'rpjmd') {
            $jenis_document = 8;
        }elseif($jenis == 'rkpd'){
            $jenis_document = 9;
        }elseif ($jenis == 'daerah') {
            $jenis_document = 10;
        }elseif ($jenis == 'rpjmdes'){
            $jenis_document = 1;
        }elseif ($jenis == 'rkpdes') {
            $jenis_document = 2;
        }elseif ($jenis == 'sdgs') {
            $jenis_document = 5;
        }elseif ($jenis == 'renstra'){
            $jenis_document = 3;
            // $nomor_konsederan = $this->create_nomor_konsederan($jenis_document,$jenis,$request->nama_documents);
        }elseif ($jenis == 'renja'){
            $jenis_document = 4;
            // $nomor_konsederan = $this->create_nomor_konsederan($jenis_document,$jenis,$request->nama_documents);
        }elseif ($jenis == 'sektoral') {
            $jenis_document = 6;
        }elseif ($jenis == 'skpd') {
            $jenis_document = 7;
        }
        
        $check_files = $this->check_files($request->nama_documents,$jenis_document);

        if ($check_files > 0) {
            return response()->json([
                'type' => 'invalid_file',
                'status' => false,
                'message' => 'Maaf, Dokumen sudah ada',
            ],200);
        }else {
            $uploadedFile = $request->file('file');
        
            $filename = '0_'.$request->referensi_nama_dokumen.'_'.Str::slug($request->nama_documents, '_').'_'.$jenis.'_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
            '/files/'.$request->referensi_nama_dokumen.'/'.$jenis,
            $uploadedFile,
            $filename
            );

            if ($type == 'type_a') {
                $status_document = 4;
            }else{
                $status_document = 1;
            }

            $data = new document();
            $data->nama_documents = $request->nama_documents;
            $data->nomor_perbub = $request->nomor_perbub;
            $data->tanggal_perbub = $request->tanggal_perbub;
            $data->status_document = $status_document;
            $data->jenis_document = $jenis_document;
            $data->periode_awal = $request->periode_awal;
            $data->periode_akhir = $request->periode_akhir;
            $data->tahun = session('tahun_penganggaran');
            $data->id_perangkat = Auth::user()->id_unit_kerja;
            $data->user_insert = Auth::user()->id;
            $data->file_document = $filename;
            $data->save();

            if ($type == 'type_b') {
                $this->verifikasiDocument($data->jenis_document, $data->id);
            }

            $history = new document_history;
            $history->action = 'tambah data';
            $history->id_documents = $data->id;
            $history->user_insert = Auth::user()->id;
            $history->save();
        
            if ($data) {
                return response()->json([
                    'type' => 'success',
                    'status' => true,
                    'message' => $jenis.' berhasil di proses',
                    'data' => $data
                ],200);
            }else{
                return response()->json([
                    'type' => 'failed',
                    'status' => false,
                    'message' => $jenis.' Gagal di proses',
                ],400);
            }
        }

    }

    public function updateDocuments(Request $request,$params){
        $jenis = request('jenis');
        $type = request('type');
        $data = array();

        $status_document = 0;
        $jenis_document = 0;

        if ($type == 'type_a') {
            $status_document = 4;
        }else{
            $status_document = 2;
        }

        if ($jenis == 'rpjmd') {
            $jenis_document = 8;
        }elseif($jenis == 'rkpd'){
            $jenis_document = 9;
        }elseif ($jenis == 'daerah') {
            $jenis_document = 10;
        }elseif ($jenis == 'rpjmdes'){
            $jenis_document = 1;
        }elseif ($jenis == 'rkpdes') {
            $jenis_document = 2;
        }elseif ($jenis == 'sdgs') {
            $jenis_document = 5;
        }elseif ($jenis == 'renstra'){
            $jenis_document = 3;
        }elseif ($jenis == 'renja'){
            $jenis_document = 4;
        }elseif ($jenis == 'sektoral') {
            $jenis_document = 6;
        }elseif ($jenis == 'skpd') {
            $jenis_document = 7;
        }

        $data = document::where('id',$params)->first();
        $data->nama_documents = $request->nama_documents;
        $data->nomor_perbub = $request->nomor_perbub;
        $data->tanggal_perbub = $request->tanggal_perbub;
        $data->status_document = $status_document;
        $data->jenis_document = $jenis_document;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = Auth::user()->id_unit_kerja;
        $data->user_insert = Auth::user()->id;
        if (isset($request->file)) {
            $uploadedFile = $request->file('file');
        
            $filename = '0_'.$request->referensi_nama_dokumen.'_'.Str::slug($request->nama_documents, '_').'_'.$jenis.'_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();
      
            Storage::disk('public')->putFileAs(
            '/files/'.$request->referensi_nama_dokumen.'/'.$jenis,
            $uploadedFile,
            $filename
            );

            $data->file_document = $filename;
        }

        $data->save();

        // if ($jenis == 'RPJMD') {
        //     $data = $this->update_dokumen_rpjmd($request,$params);
        // }elseif($jenis == 'RKPD'){
        //     $data = $this->update_dokumen_rkpd($request,$params);
        // }elseif ($jenis == 'daerah') {
        //     $data = $this->update_dokumen_daerah($request,$params);
        // }elseif ($jenis == 'rpjmdes'){
        //     $data = $this->update_dokumen_rpjmdes($request,$params);
        // }elseif ($jenis == 'rkpdes'){
        //     $data = $this->update_dokumen_rkpdes($request,$params);
        // }elseif ($jenis == 'sdgs') {
        //     $data = $this->update_dokumen_sdgs($request,$params);
        // }elseif ($jenis == 'renstra') {
        //     $data = $this->update_dokumen_renstra($request,$params);
        // }elseif ($jenis == 'renja') {
        //     $data = $this->update_dokumen_renja($request,$params);
        // }

        $history = new document_history;
        $history->action = 'update data';
        $history->id_documents = $data->id;
        $history->user_insert = Auth::user()->id;
        $history->save();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => $jenis.' berhasil di proses',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => $jenis.' Gagal di proses',
            ],400);
        }
    }

    public function byParams($params){
        $data = document::where('id',$params)->first();
        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data,
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
            ],400);
        }
    }


    public function update_dokumen_rpjmd($request,$params){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'nomor_perbub' => 'required',
            'tanggal_perbub' => 'required',
            'file' => 'mimes:pdf|max:30000',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        $data = document::where('id',$params)->first();
        $data->nama_documents = $request->nama_documents;
        $data->nomor_perbub = $request->nomor_perbub;
        $data->tanggal_perbub = $request->tanggal_perbub;
        $data->status_document = 4;
        $data->jenis_document = 8;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = Auth::user()->id_unit_kerja;
        $data->user_insert = Auth::user()->id;
        if (isset($request->file)) {
            $uploadedFile = $request->file('file');
        
            $filename = '0_'.'dokumen_daerah_'.Str::slug($request->nama_documents, '_').'_rpjmd_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();
      
            Storage::disk('public')->putFileAs(
              'files/dokumen_daerah/rpjmd',
              $uploadedFile,
              $filename
            );

            $data->file_document = $filename;
        }

        $data->save();
      
        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'RPJMD Berhasil di Update',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'RPJMD Gagal di Proses',
            ],400);
        }
    }

    public function pengaturan_akun(){
        $breadcumb = 'Pengaturan Akun';
        $current_breadcumb = '';
        $user = DB::table('user')->select('nama_lengkap','nip','username')->where('id',Auth::user()->id)->first();
        return view('module.pengaturan_akun',compact('breadcumb','current_breadcumb','user'));
    }

    public function set_pengaturan_akun(Request $request){
        $request->validate([
            'password_lama' => ['required', new MatchOldPassword],
            'password' => 'required|confirmed|min:6',
        ]);
       
       $data = DB::table('user')->where('id', Auth::user()->id)->update(['password' => Hash::make($request->password)]);

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' =>'Akun berhasil di update',
                'data' => $data,
                'url' => '/pengaturan-akun'
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' =>'Akun Gagal di update',
                'data' => $data,
            ]);
        }
    }
}
