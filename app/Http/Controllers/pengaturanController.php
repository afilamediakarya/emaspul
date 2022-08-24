<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jadwal;

class pengaturanController extends Controller
{
    public function index(){
        $breadcumb = 'Pengaturan';
        $current_breadcumb = 'Jadwal';
        return view('module.admin.pengaturan.jadwal',compact('breadcumb','current_breadcumb'));
    }

    public function datatable_list(){

        $data = jadwal::latest()->get();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'data' => $data
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => true,
                'data' => $data
            ]);
        }
    }

    public function store(Request $request){
        $validated = $request->validate([
            'tahapan' => 'required',
            'sub_tahapan' => 'required',
            'jadwal_mulai' => 'required',
            'jadwal_selesai' => 'required',
            'status' => 'required'
        ]);

        $data = new jadwal();
        $data->tahapan = $request->tahapan;
        $data->sub_tahapan = $request->sub_tahapan;
        $data->jadwal_mulai = $request->jadwal_mulai;
        $data->jadwal_selesai = $request->jadwal_selesai;
        $data->status = $request->status;
        $data->tahun = session('tahun_penganggaran');
        $data->save();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Jadwal Berhasil di Tambahkan',
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Jadwal Gagal di Proses',
            ]);
        }
    }

    public function byParams($params){

        $data = jadwal::select('id','jadwal_mulai','jadwal_selesai','status','tahapan','sub_tahapan')->where('id',$params)->first();
        $data->tahapans = ['tahapan'=>$data->tahapan, 'sub_tahapan'=>$data->sub_tahapan];
       
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
            ]);
        }
    }

    public function update(Request $request, $params){
        $validated = $request->validate([
            'tahapan' => 'required',
            'sub_tahapan' => 'required',
            'jadwal_mulai' => 'required',
            'jadwal_selesai' => 'required',
            'status' => 'required'
        ]);

        $data = jadwal::where('id',$params)->first();
        $data->tahapan = $request->tahapan;
        $data->sub_tahapan = $request->sub_tahapan;
        $data->jadwal_mulai = $request->jadwal_mulai;
        $data->jadwal_selesai = $request->jadwal_selesai;
        $data->status = $request->status;
        $data->tahun = session('tahun_penganggaran');
        $data->save();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Jadwal Berhasil di Update',
            ]);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Jadwal Gagal di Proses',
            ]);
        }
    }


}
