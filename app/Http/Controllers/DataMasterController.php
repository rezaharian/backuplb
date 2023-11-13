<?php

namespace App\Http\Controllers;

use App\Models\DdataMaster;
use Illuminate\Http\Request;

class DataMasterController extends Controller
{
    
    public function index(){

        
        $st = DdataMaster::first();
         return view("line/master", compact('st'));
     }
     public function store(Request $request){
        $request->validate([
            'shift' => 'required',
          
        ]);

        $input = $request->all();

        DdataMaster::create($input);
        return back()->with(['message' => 'Berhasil Mengganti ke']);;
     }
    
}

