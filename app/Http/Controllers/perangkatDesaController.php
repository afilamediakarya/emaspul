<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\perangkat_desa;
use App\Models\pagu_desa;
use Auth;
use DB;
class perangkatDesaController extends Controller
{
    public function index(){
        $breadcumb = 'Master';
        $current_breadcumb = 'Perangkat Desa';
        return view('module.admin.master.perangkat_desa.index',compact('breadcumb','current_breadcumb'));
    }

    public function datatable_list(){
        $data = DB::table('perangkat_desa')->select('perangkat_desa.id','perangkat_desa.nama_desa','perangkat_desa.nama_kepala','pagu_desa.pagu_desa')->join('pagu_desa','pagu_desa.id_perangkat_desa','=','perangkat_desa.id')->where('pagu_desa.tahun',session('tahun_penganggaran'))->get();

        // return $data;
        
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
                'message' => 'Perangkat Desa Gagal di Proses',
            ],400);
        }
    }

    public function store(Request $request){

        $validated = $request->validate([
            'desa' => 'required',
            'pagu_desa' => 'required',
            'jabatan_kepala' => 'required',
            'nama_kepala' => 'required',
            'status' => 'required',
            'kode_desa'=> 'required'
        ]);

        $perangkat_desa = new perangkat_desa;
        $perangkat_desa->id_desa = $request->desa;
        $perangkat_desa->nama_desa = $request->nama_desa;
        $perangkat_desa->nama_kepala = $request->nama_kepala;
        $perangkat_desa->jabatan_kepala = $request->jabatan_kepala;
        $perangkat_desa->status = $request->status;
        $perangkat_desa->kode_desa = $request->kode_desa;
        $perangkat_desa->user_insert = Auth::user()->id;
        $perangkat_desa->save();

        $pagu_desa = new pagu_desa;
        $pagu_desa->id_perangkat_desa = $perangkat_desa->id;
        $pagu_desa->tahun = date('Y');
        $pagu_desa->pagu_desa = str_replace(',', '', $request->pagu_desa);
        $pagu_desa->user_insert = Auth::user()->id;
        $pagu_desa->save();

        if ($perangkat_desa) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Perangkat Desa Berhasil di Tambahkan',
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Perangkat Desa Gagal di Proses',
            ],400);
        }
    }

    public function byParams($params){
        $data = DB::table('perangkat_desa')->select('perangkat_desa.id','perangkat_desa.nama_desa','perangkat_desa.nama_kepala','perangkat_desa.jabatan_kepala','perangkat_desa.status','perangkat_desa.kode_desa','perangkat_desa.id_desa','pagu_desa.pagu_desa')->join('pagu_desa','pagu_desa.id_perangkat_desa','=','perangkat_desa.id')->where('perangkat_desa.id',$params)->first();
       
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

    public function update(Request $request, $params){
        $validated = $request->validate([
            'desa' => 'required',
            'pagu_desa' => 'required',
            'jabatan_kepala' => 'required',
            'nama_kepala' => 'required',
            'status' => 'required',
            'kode_desa'=> 'required'
        ]);

        $perangkat_desa = perangkat_desa::where('id',$params)->first();
        $perangkat_desa->id_desa = $request->desa;
        $perangkat_desa->nama_desa = $request->nama_desa;
        $perangkat_desa->nama_kepala = $request->nama_kepala;
        $perangkat_desa->jabatan_kepala = $request->jabatan_kepala;
        $perangkat_desa->status = $request->status;
        $perangkat_desa->kode_desa = $request->kode_desa;
        $perangkat_desa->user_insert = Auth::user()->id;
        $perangkat_desa->save();

        $pagu_desa = pagu_desa::where('id_perangkat_desa',$params)->first();
        $pagu_desa->id_perangkat_desa = $perangkat_desa->id;
        $pagu_desa->tahun = date('Y');
        $pagu_desa->pagu_desa = str_replace(',', '', $request->pagu_desa);
        $pagu_desa->user_insert = Auth::user()->id;
        $pagu_desa->save();

        if ($perangkat_desa) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Perangkat Desa Berhasil di Tambahkan',
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Perangkat Desa Gagal di Proses',
            ],400);
        }
    }

}
