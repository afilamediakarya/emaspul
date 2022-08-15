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
                return '/dashboard-admin';
            } elseif ($user->id_role == 2) {
                return '/dashboard-admin-opd';
            }elseif ($user->id_role == 3) {
                return '/dashboard-admin-desa';
            }elseif ($user->id_role == 4) {
                return '/dashboard-admin-verifikator';
            }

        } 
        else{ 
            return redirect()->back()->with('error', 'These credentials do not match our records.');
        } 
    }

    public function logout(Request $request){
       $request->session()->flush();
       Auth::logout();
       return redirect('login');
    }
}
