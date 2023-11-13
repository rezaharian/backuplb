<?php

namespace App\Http\Controllers;

use App\Models\overt_h;
use App\Models\pegawai;
use Illuminate\Http\Request;

class apiController extends Controller
{

    public function list()
{
    $data = overt_h::limit(100)
        ->orderBy('ot_cod', 'desc')
        ->get();
    return response()->json($data);
}

public function karyawan(){

    $data = pegawai::orderBy('no_payroll', 'asc')
    ->get();

return response()->json($data);

}
}
