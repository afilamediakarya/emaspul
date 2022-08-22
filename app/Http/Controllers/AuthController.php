<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
class AuthController extends Controller
{
    public function login(){
        
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->id_role == 1) {
                return redirect()->intended('dashboard-admin');
            } elseif ($user->id_role == 2) {
                return redirect()->intended('dashboard-admin-opd');
            }elseif ($user->id_role == 3) {
                return redirect()->intended('dashboard.admin_desa');
            }elseif ($user->id_role == 4) {
                return redirect()->intended('dashboard.admin.verifikator');
            }
        }else{
            return view('auth.login');
        }
        
    }

    public function do_login(Request $request){

        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
    
        if(Auth::attempt($request->only('username', 'password'))){ 
            $user = Auth::user();
            // dd($user);
           if ($user->id_role == 1) {
                return response()->json([
                    'type' => 'success',
                    'status' => true,
                    'callback' => '/dashboard-admin',
                ]);
            } elseif ($user->id_role == 2) {
                return response()->json([
                    'type' => 'success',
                    'status' => true,
                    'callback' => '/dashboard-admin-opd'
                ]);
            }elseif ($user->id_role == 3) {
                return response()->json([
                    'type' => 'success',
                    'status' => true,
                    'callback' => '/dashboard-admin-desa'
                ]);
            }elseif ($user->id_role == 4) {
                return response()->json([
                    'type' => 'success',
                    'status' => true,
                    'callback' => '/dashboard-admin-verifikator'
                ]);
            }
        } 
        else{ 
            return response()->json([
                'type' => 'warning',
                'status' => false,
                'callback' => 'Silahkan isi username dan password anda dengan benar',
            ]);
        } 
    }

    public function logout(Request $request){
       $request->session()->flush();
       Auth::logout();
       return redirect('login');
    }
}
