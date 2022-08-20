<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\document;
use App\Models\document_history;
use App\Models\master_verifikasi;
use App\Models\verifikasi_document;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\InfoRequest;
use Str;
use Auth;
use Illuminate\Support\Facades\Session;
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

    public function get_desa(){
        $data = DB::table('desa')->select('id','nama as value')->get();
        return $data;
    }

    public function get_perangkat_desa(){
        $data = DB::table('perangkat_desa')->select('id_desa as id','nama_desa as value')->get();
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

    public function get_master_verifikasi($params){
       
        $data = verifikasi_document::with('master_verifikasi')->where('id_documents',$params)->get();
        return $data;
    }

    public function setTahunAnggaran(){
      
        // session(['tahun_penganggaran' => request('tahun')]);
        Session::put('tahun_penganggaran',request('tahun'));

        return redirect()->back();
    }

    public function datatable_list(){
        $jenis = request('jenis');
        $type = request('type');
        $data = array();



        if ($type == 'type_a') {
            $data = document::select('id','nama_documents','nomor_perbub','tanggal_perbub','file_document')->where('jenis_document',$jenis)->where('id_perangkat',Auth::user()->id_unit_kerja)->latest()->get();
        }else if($type == 'type_b'){
            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document, (SELECT perangkat_desa.nama_desa FROM perangkat_desa INNER JOIN user ON user.`id_unit_kerja`=perangkat_desa.`id` WHERE user.`id` = documents.`user_insert`) AS nama_desa, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=".$jenis." AND documents.id_perangkat =".Auth::user()->id_unit_kerja);
        }else{
            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document,documents.jenis_document, (SELECT unit_kerja.nama_unit_kerja FROM unit_kerja INNER JOIN user ON user.`id_unit_kerja`=unit_kerja.`id` WHERE user.`id` = documents.`user_insert`) AS nama_unit_kerja, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=".$jenis);
        }     
       

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Data Gagal di Proses',
                'data' => $data,
            ]);
        }
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

    public function master_verifikasi(Request $request){
        $jenis = request('jenis');
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
        }else{
            $status_document = 1;
        }	
        

        if ($data) {
            $document = document::where('id',request('document'))->first();
            $document->id_verifikator = Auth::user()->id;
            $document->status_document = $status_document;
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
        $data = document::where('nama_documents',$params1)->where('id_perangkat',Auth::user()->id_unit_kerja)->where('jenis_document',$params2)->where('tahun',session('tahun_penganggaran'))->exists();
        return $data;
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
        }elseif ($jenis == 'renja'){
            $jenis_document = 4;
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
}
