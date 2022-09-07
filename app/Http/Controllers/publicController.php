<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\verifikasi_document;
class publicController extends Controller
{
    public function detailDokuments(){
        $jenis = request('jenis');
        $document = request('document');
        return view('general.detail',compact('jenis','document'));
    }

    public function get_master_verifikasi($params){
       
        $data = verifikasi_document::select('verifikasi_documents.id','verifikasi_documents.id_documents','verifikasi_documents.id_master_verifikasi','verifikasi_documents.tindak_lanjut','verifikasi_documents.verifikasi','documents.jenis_document','documents.nama_documents')->with('master_verifikasi')->join('documents','verifikasi_documents.id_documents','=','documents.id')->where('id_documents',$params)->get();
        return $data;
    }

    public function get_data(){
        
        $jenis = request('jenis');
        $document = request('document');

        $data = DB::select("SELECT documents.id,documents.nama_documents, documents.nomor_perbub,documents.tanggal_perbub,documents.status_document,  (SELECT user.nama_lengkap FROM user WHERE documents.id_verifikator = user.id) AS verifikator FROM documents where jenis_document=".$jenis." AND id=".$document." LIMIT 1");

        $master_verifikasi = $this->get_master_verifikasi($document);

        return [
            'document' => $data,
            'master_verifikasi' => $master_verifikasi
        ];

    }
}
