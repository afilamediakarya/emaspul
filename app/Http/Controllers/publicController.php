<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class publicController extends Controller
{
    public function detailDokuments(){
        return view('general.detail');
    }
}