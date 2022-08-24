<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class akunController extends Controller
{
    public function index(){
        $breadcumb = 'Master';
        $current_breadcumb = 'Akun';
        return view('module.admin.master.akun.index',compact('breadcumb','current_breadcumb'));
    }

    public function datatable_list(){
        $data = DB::table('user')->select('id_unit_kerja','nama_lengkap','id','username','id_role')->where('id_role','!=', 1)->latest()->get();
        // return $data;
        foreach ($data as $key => $value) {
            if ($value->id_role == 2) {
               $value->bidang = DB::table('unit_kerja')->select('id','nama_unit_kerja')->where('id',$value->id_unit_kerja)->first()->nama_unit_kerja;
            }else if($value->id_role == 3){
                $value->bidang = DB::table('perangkat_desa')->select('id','nama_desa')->where('id',$value->id_unit_kerja)->first()->nama_desa;
            }else{
                $value->bidang = DB::table('bidang_verifikasi')->select('id','nama_bidang')->where('id',$value->id_unit_kerja)->first()->nama_bidang;
            }
        }

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
            'jabatan' => 'required',
            'nama_lengkap' => 'required',
            'nip' => 'required|unique:user',
            'no_telp' => 'required|numeric',
            'password' => 'required|confirmed|min:6',
            'perangkat' => 'required',
            'role' => 'required',
            'status' => 'required',
            'username' => 'required',
        ]);
      
        $data = new User();
        $data->jabatan = $request->jabatan;
        $data->uuid = Uuid::uuid4()->toString();
        $data->nama_lengkap = $request->nama_lengkap;
        $data->nip = $request->nip;
        $data->no_telp = $request->no_telp;
        $data->id_unit_kerja = $request->perangkat;
        $data->id_role = $request->role;
        $data->status = $request->status;
        $data->username = $request->username;
        $data->password = Hash::make($request->password);
        $data->save();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Akun Berhasil di Tambahkan',
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Akun Gagal di Proses',
            ],400);
        }
     
    }

    public function byParams($params){

        $data = User::where('id',$params)->first();
        $data->roles = ['id_role'=>$data->id_role, 'perangkat_bidang'=>$data->id_unit_kerja];
       
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
            'jabatan' => 'required',
            'nama_lengkap' => 'required',
            'no_telp' => 'required|numeric',
            'perangkat' => 'required',
            'role' => 'required',
            'status' => 'required',
            'username' => 'required',
            'password' => 'confirmed',
        ]);
      
        $data = User::where('id',$params)->first();
        $data->jabatan = $request->jabatan;
        $data->nama_lengkap = $request->nama_lengkap;
        $data->nip = $request->nip;
        $data->no_telp = $request->no_telp;
        $data->id_unit_kerja = $request->perangkat;
        $data->id_role = $request->role;
        $data->status = $request->status;
        $data->username = $request->username;
        if (isset($request->password)) {
            $data->password = Hash::make($request->password);
        }
        $data->save();

        if ($data) {
            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => 'Akun Berhasil di Update',
            ],200);
        }else{
            return response()->json([
                'type' => 'failed',
                'status' => false,
                'message' => 'Akun Gagal di Proses',
            ],400);
        }
     
    }
}
