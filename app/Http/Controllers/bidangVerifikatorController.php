<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bidangVerifikator;
use App\Models\unitBidang;
use Auth;
class bidangVerifikatorController extends Controller
{
    public function index(){
        $breadcumb = 'Master';
        $current_breadcumb = 'Bidang Verifikator';
        return view('module.admin.master.bidang.index',compact('breadcumb','current_breadcumb'));
    }

    public function datatable_list(){
        $data = bidangVerifikator::with('uniBidang')->latest()->get();

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

        $bidang = new bidangVerifikator;
        $bidang->nama_bidang = $request->nama_bidang;
        $bidang->status = $request->status;
        $bidang->user_insert = Auth::user()->id;
        $bidang->save();

        foreach ($request->perangkat_desa as $key => $value) {
            $unit  = new unitBidang();
            $unit->id_perangkat = $value;
            $unit->id_bidang = $bidang->id;
            $unit->user_insert = Auth::user()->id;
            $unit->save();
        }

        if ($bidang) {
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

        $data = bidangVerifikator::where('id',$params)->first();
        $data->perangkat_desa = unitBidang::where('id_bidang',$data->id)->get();
       
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
}
