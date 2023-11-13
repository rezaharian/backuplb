<?php

namespace App\Http\Controllers;

use App\Models\absen_h;
use App\Models\bagian;
use App\Models\Materi;
use App\Models\pegawai;
use App\Models\TargetTraining;
use App\Models\TglLibur;
use App\Models\train_d;
use App\Models\train_h;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HrController extends Controller
{
    
    public function index(){
        // $data = DB::table('pegawais')
        // ->selectRaw('bagian, COUNT(*) as total')
        // ->groupBy('bagian')
        // ->get();
        // // dd($data->toArray());

        $karyawan = DB::table('pegawais')
        ->select(DB::raw('count(*) as jumlah, sex, count(sex) as jk'))
        ->whereNull('tgl_keluar')
        ->groupBy('sex')
        ->get();

        
        $jns_peg = DB::table('pegawais')
        ->select(DB::raw('count(*) as jumlah, jns_peg, count(jns_peg) as jp'))
        ->whereNull('tgl_keluar')
        ->groupBy('jns_peg')
        ->get();

        $peg_bag = DB::table('pegawais')
        ->select(DB::raw('count(*) as jumlah, bagian, count(bagian) as pb'))
        ->whereNull('tgl_keluar')
        ->groupBy('bagian')
        ->get();

            // return view('dashboard', compact('pengunjung'));


// untuk dashboard card
// pegawai
            $jml_peg = pegawai::whereNull('pegawais.tgl_keluar')
            ->count();
            // kontrak
            $now = Carbon::now();
            $r_bln = $now->translatedFormat('F');

            $r_thn = Carbon::now()->year;
            $bln = Carbon::now()->month;
            $thn = Carbon::now()->year;
            $data = DB::table('peg_ds')
                ->join('pegawais', 'pegawais.no_payroll', '=', 'peg_ds.no_payroll')
                ->select('pegawais.nama_asli', DB::raw("DATE_FORMAT(peg_ds.Perpanjang, '%d %M %Y') as perpanjang"), DB::raw("DATE_FORMAT(peg_ds.berakhir, '%d %M %Y') as Berakhir"))
                ->where(DB::raw('YEAR(peg_ds.berakhir)'), $thn)
                ->where(DB::raw('MONTH(peg_ds.berakhir)'), $bln)
                // ->whereNull('pegawais.tgl_keluar')
                ->orderBy('peg_ds.berakhir', 'ASC')
                ->get();
                $jml_kntr = $data->count();
            // Tgl Libur
            $jml_libur = TglLibur::count();
            $r_thn = $now->translatedFormat('Y');
            // Absensi
            $tahunSekarang = date('Y');
            $jml_absen = absen_h::where('thn_absen', $tahunSekarang)->count();
            // traiing
            $jml_tra = train_h::count();
            // materi
            $jml_mtr = Materi::count();
            // bagian
            $jml_bag = bagian::count();
            // target
            $jml_tar = TargetTraining::count();



  
        return view('hr.dashboard.index',compact('jml_tar','jml_bag','jml_mtr','jml_tra','jml_absen','r_thn','jml_libur','r_bln',
        'jml_kntr','jml_peg','karyawan','jns_peg','peg_bag', 'tahunSekarang'));
    }
    public function coba(){
        
        return view('hr.dashboard.coba');
    }

    public function chart_peg_bag(){

        $data = DB::table('pegawais')
        ->selectRaw('bagian, COUNT(*) as total')
        ->groupBy('bagian')
        ->get();

        return response()->json($data);

    }
    public function index_profile(){

        $profile = Auth::user();

        return view('hr.dashboard.profile.index', compact( 'profile'));
    }
    public function edit_profile(){

        $profile = Auth::user();

        return view('hr.dashboard.profile.edit', compact( 'profile'));
    }

    public function update_profile(Request $request, $id)
    {
        // dd($request->toArray());
        $user = User::find($id);
        $user->id = $request->id;
        $user->name = $request->name;
        $user->level = $request->level;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();

        return back()->with('success', 'Profile Update successfully');
    }


}
