<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use Illuminate\Http\Request;

class primeController extends Controller
{

    public function pegawailengkap(Request $request)
    {
        $term = $request->input('term');
        $employees = Pegawai::where('no_payroll', 'like', "%$term%") 
        ->orWhere('nama_asli', 'like', "%$term%") 
        ->where('jns_peg', '!=', 'SATPAM')
        ->where('jns_peg', '!=', 'KEBERSIHAN')
        ->where('bagian', '!=', 'DIREKSI')
        ->whereNull('tgl_keluar')->get();
        return response()->json($employees);
    }
    

}
