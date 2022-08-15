<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\document;
use App\Models\document_history;
use App\Models\master_verifikasi;
use App\Models\verifikasi_document;
use Illuminate\Support\Facades\Storage;
use Str;
use Auth;
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

    public function get_master_verifikasi($params){
       
        $data = verifikasi_document::with('master_verifikasi')->where('id_documents',$params)->get();
        return $data;
    }

    public function setTahunAnggaran(){
        session(['tahun_penganggaran' => request('tahun', date('Y'))]);
        return redirect()->back();
    }

    public function datatable_list(){
        $type = request('jenis');
        $data = array();

        if ($type == '8') {
            $data = document::select('id','nama_documents','nomor_perbub','tanggal_perbub','file_document')->where('jenis_document','8')->latest()->get();
        }else if($type == '9'){
            $data = document::select('id','nama_documents','nomor_perbub','tanggal_perbub','file_document')->where('jenis_document','9')->latest()->get();
        }else if($type == '10'){
            $data = document::select('id','nama_documents','file_document')->where('jenis_document','10')->latest()->get();
        }else if($type == '1'){
            // $data = document::with('verifikator')->select('id','nama_documents','periode_awal','periode_akhir','status_document','id_perangkat','id_verifikator','file_document')->where('jenis_document','1')->latest()->get();

            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.periode_awal,documents.periode_akhir,documents.file_document,documents.status_document, (SELECT perangkat_desa.nama_desa FROM perangkat_desa INNER JOIN user ON user.`id_unit_kerja`=perangkat_desa.`id` WHERE user.`id` = documents.`user_insert`) AS nama_desa, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=1");

        }else if($type == '2'){

            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.file_document,documents.status_document, (SELECT perangkat_desa.nama_desa FROM perangkat_desa INNER JOIN user ON user.`id_unit_kerja`=perangkat_desa.`id` WHERE user.`id` = documents.`user_insert`) AS nama_desa, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=2");

        }else if($type == '5'){
            // $data = document::select('id','nama_documents','id_verifikator','file_document')->where('jenis_document','5')->latest()->get();
            $data = DB::select("SELECT documents.id,documents.nama_documents,documents.file_document, (SELECT perangkat_desa.nama_desa FROM perangkat_desa INNER JOIN user ON user.`id_unit_kerja`=perangkat_desa.`id` WHERE user.`id` = documents.`user_insert`) AS nama_desa, (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=5");
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

    public function storeDocuments(Request $request){
        $jenis = request('jenis');
        $data = array();
        if ($jenis == 'RPJMD') {
            $data = $this->store_dokumen_rpjmd($request);
        }elseif($jenis == 'RKPD'){
            $data = $this->store_dokumen_rkpd($request);
        }elseif ($jenis == 'daerah') {
            $data = $this->store_dokumen_daerah($request);
        }elseif ($jenis == 'rpjmdes'){
            $data = $this->store_dokumen_rpjmdes($request);
            $this->verifikasiDocument( json_decode($data->content(), true)['data']['jenis_document'] ,json_decode($data->content(), true)['data']['id']);
        }elseif ($jenis == 'rkpdes') {
            $data = $this->store_dokumen_rkpdes($request);
            $this->verifikasiDocument( json_decode($data->content(), true)['data']['jenis_document'] ,json_decode($data->content(), true)['data']['id']);
        }elseif ($jenis == 'sdgs') {
            $data = $this->store_dokumen_sdgs($request);
        }

        $history = new document_history;
        $history->action = 'tambah data';
        $history->id_documents = json_decode($data->content(), true)['data']['id'];
        $history->user_insert = Auth::user()->id;
        $history->save();
        return $data;
    }

    public function updateDocuments(Request $request,$params){
        $jenis = request('jenis');
        $data = array();
        if ($jenis == 'RPJMD') {
            $data = $this->update_dokumen_rpjmd($request,$params);
        }elseif($jenis == 'RKPD'){
            $data = $this->update_dokumen_rkpd($request,$params);
        }elseif ($jenis == 'daerah') {
            $data = $this->update_dokumen_daerah($request,$params);
        }elseif ($jenis == 'rpjmdes'){
            $data = $this->update_dokumen_rpjmdes($request,$params);
        }elseif ($jenis == 'rkpdes'){
            $data = $this->update_dokumen_rkpdes($request,$params);
        }elseif ($jenis == 'sdgs') {
            $data = $this->update_dokumen_sdgs($request,$params);
        }

        $history = new document_history;
        $history->action = 'update data';
        $history->id_documents = json_decode($data->content(), true)['data']['id'];
        $history->user_insert = Auth::user()->id;
        $history->save();

        return $data;
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

    public function store_dokumen_rkpd($request){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'nomor_perbub' => 'required',
            'tanggal_perbub' => 'required',
            'file' => 'required|mimes:pdf|max:30000',
        ]);

        $uploadedFile = $request->file('file');
        
        $filename = '0_'.'dokumen_daerah_'.Str::slug($request->nama_documents, '_').'_rkpd_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();

        Storage::disk('public')->putFileAs(
          'files/dokumen_daerah/rkpd',
          $uploadedFile,
          $filename
        );

        $data = new document();
        $data->nama_documents = $request->nama_documents;
        $data->nomor_perbub = $request->nomor_perbub;
        $data->tanggal_perbub = $request->tanggal_perbub;
        $data->status_document = 4;
        $data->jenis_document = 9;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        $data->file_document = $filename;
        $data->save();
      

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'RKPD Berhasil di Tambahkan',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'RKPD Gagal di Proses',
            ],400);
        }
    }

    public function store_dokumen_daerah($request){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'required|mimes:pdf|max:30000',
        ]);
        // return $request->all();

        $uploadedFile = $request->file('file');
        
        $filename = '0_'.'dokumen_daerah_'.Str::slug($request->nama_documents, '_').'_daerah_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();
        
        // id_perangkat | jenis | nama | tahun
  
        Storage::disk('public')->putFileAs(
          'files/dokumen_daerah/lainnya',
          $uploadedFile,
          $filename
        );

        $data = new document();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 4;
        $data->jenis_document = 10;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        $data->file_document = $filename;
        $data->save();
      

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Dokumen Berhasil di Tambahkan',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Dokumen Gagal di Proses',
            ],400);
        }
    }

    public function store_dokumen_rpjmd($request){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'nomor_perbub' => 'required',
            'tanggal_perbub' => 'required',
            'file' => 'required|mimes:pdf|max:30000',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        $uploadedFile = $request->file('file');
        
        $filename = '0_'.'dokumen_daerah_'.Str::slug($request->nama_documents, '_').'_rpjmd_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();
        
        // id_perangkat | jenis | nama | tahun
        // public_uploads
        Storage::disk('public')->putFileAs(
          '/files/dokumen_daerah/rpjmd',
          $uploadedFile,
          $filename
        );

        $data = new document();
        $data->nama_documents = $request->nama_documents;
        $data->nomor_perbub = $request->nomor_perbub;
        $data->tanggal_perbub = $request->tanggal_perbub;
        $data->status_document = 4;
        $data->jenis_document = 8;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        $data->file_document = $filename;
        $data->save();
      

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

    public function store_dokumen_rpjmdes($request){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'required|mimes:pdf|max:30000',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        $uploadedFile = $request->file('file');
        
        $filename = '0_'.'dokumen_desa_'.Str::slug($request->nama_documents, '_').'_rpjmdes_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();

  
        Storage::disk('public')->putFileAs(
          'files/dokumen_daerah/rpjmdes',
          $uploadedFile,
          $filename
        );

        $data = new document();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 1;
        $data->jenis_document = 1;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        $data->file_document = $filename;
        $data->save();
      

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'RPJMDes Berhasil di Tambahkan',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'RPJMDes Gagal di Proses',
            ],400);
        }
    }

    public function store_dokumen_rkpdes($request){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'required|mimes:pdf|max:30000',
        ]);

        $uploadedFile = $request->file('file');

        $filename = '0_'.'dokumen_desa_'.Str::slug($request->nama_documents, '_').'_rkpdes_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();

  
        Storage::disk('public')->putFileAs(
          'files/dokumen_daerah/rkpdes',
          $uploadedFile,
          $filename
        );

        $data = new document();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 1;
        $data->jenis_document = 2;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        $data->file_document = $filename;
        $data->save();
      
        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'RKPDes Berhasil di Tambah',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'RKPDes Gagal di Proses',
            ],400);
        }
    }

    public function store_dokumen_sdgs($request){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'required|mimes:pdf|max:30000',
        ]);

        $uploadedFile = $request->file('file');

        $filename = '0_'.'dokumen_desa_'.Str::slug($request->nama_documents, '_').'_sdgs_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();

  
        Storage::disk('public')->putFileAs(
          'files/dokumen_daerah/sdgs',
          $uploadedFile,
          $filename
        );

        $data = new document();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 4;
        $data->jenis_document = 5;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        $data->file_document = $filename;
        $data->save();
      
        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'SDGd Desa Berhasil di Tambah',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'SDGd Desa Gagal di Proses',
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
        $data->id_perangkat = 0;
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

    public function update_dokumen_rkpd($request,$params){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'nomor_perbub' => 'required',
            'tanggal_perbub' => 'required',
            'file' => 'mimes:pdf|max:30000',
        ]);

        $data = document::where('id',$params)->first();
        $data->nama_documents = $request->nama_documents;
        $data->nomor_perbub = $request->nomor_perbub;
        $data->tanggal_perbub = $request->tanggal_perbub;
        $data->status_document = 4;
        $data->jenis_document = 9;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        if (isset($request->file)) {
            $uploadedFile = $request->file('file');
        
            $filename = '0_'.'dokumen_daerah_'.Str::slug($request->nama_documents, '_').'_rpkpd_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();
      
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

    public function update_dokumen_daerah($request,$params){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'mimes:pdf|max:30000',
        ]);

        $data = document::where('id',$params)->first();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 4;
        $data->jenis_document = 10;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        if (isset($request->file)) {
            $uploadedFile = $request->file('file');
        
            $filename = '0_'.'dokumen_daerah_'.Str::slug($request->nama_documents, '_').'_daerah_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();
      
            Storage::disk('public')->putFileAs(
              'files/dokumen_daerah/lainnya',
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
                'message' => 'Dokumen Berhasil di Update',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Dokumen Gagal di Proses',
            ],400);
        }
    }

    public function update_dokumen_rpjmdes($request,$params){
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'mimes:pdf|max:30000',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        $data = document::where('id',$params)->first();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 1;
        $data->jenis_document = 1;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        if (isset($request->file)) {
            $uploadedFile = $request->file('file');
        
            $filename = '0_'.'dokumen_desa_'.Str::slug($request->nama_documents, '_').'_rpjmdes_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
            'files/dokumen_daerah/rpjmdes',
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
                'message' => 'RPJMDes Berhasil di Tambahkan',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'RPJMDes Gagal di Proses',
            ],400);
        }
    }

    public function update_dokumen_rkpdes($request, $params){
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'mimes:pdf|max:30000',
        ]);

        $data = document::where('id',$params)->first();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 1;
        $data->jenis_document = 2;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        if (isset($request->file)) {
            $uploadedFile = $request->file('file');

            $filename = '0_'.'dokumen_desa_'.Str::slug($request->nama_documents, '_').'_rkpdes_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'files/dokumen_daerah/rkpdes',
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
                'message' => 'RKPDes Berhasil di Update',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'RKPDes Gagal di Proses',
            ],400);
        }
    }

    public function update_dokumen_sdgs($request,$params){
       
        $validated = $request->validate([
            'nama_documents' => 'required',
            'file' => 'mimes:pdf|max:30000',
        ]);

        $data = document::where('id',$params)->first();
        $data->nama_documents = $request->nama_documents;
        $data->status_document = 4;
        $data->jenis_document = 5;
        $data->tahun = session('tahun_penganggaran');
        $data->id_perangkat = 0;
        $data->user_insert = Auth::user()->id;
        if (isset($request->file)) {
            $uploadedFile = $request->file('file');

            $filename = '0_'.'dokumen_desa_'.Str::slug($request->nama_documents, '_').'_sdgs_'.session('tahun_penganggaran').'.'.$uploadedFile->getClientOriginalExtension();
    
      
            Storage::disk('public')->putFileAs(
              'files/dokumen_daerah/sdgs',
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
                'message' => 'SDGd Desa Berhasil di Update',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'SDGd Desa Gagal di Proses',
            ],400);
        }
    }
}
