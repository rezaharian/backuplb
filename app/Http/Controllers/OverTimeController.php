<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use App\Models\overt_h;
use App\Models\overtime;
use App\Models\pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OverTimeController extends Controller
{
    public function list()
    {
        $data = overt_h::limit(100)
            ->orderBy('ot_cod', 'desc')
            ->get();
            // dd($data->toArray());

        return view('hr.dashboard.overt.list', compact('data'));
    }

    public function create()
    {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $bag = bagian::all();
        $ot_cod_d = Str::random(10);
        $kar = pegawai::orderBy('nama_asli', 'asc')
        ->whereNull('tgl_keluar')
        ->where('jns_peg', '!=', 'SATPAM')
        ->where('jns_peg', '!=', 'KEBERSIHAN')
        ->get();

        return view('hr.dashboard.overt.create', compact('hari', 'bag', 'ot_cod_d', 'kar'));
    }

    public function store(Request $request)
    {
        // dd($request->toArray());
        $data = overt_h::limit(100)
            ->orderBy('ot_cod', 'desc')
            ->get();

        $ot_int_d = Str::random(10);
        $kode = overt_h::orderBy('ot_cod', 'desc')->first();
        $kode_d = $kode->ot_cod;
        $kode_e = substr($kode_d, -3);
        $thn = Carbon::now()->format('y');
        $bln = Carbon::now()->format('m');


        $kode_f = 'LBR'.$thn.$bln.sprintf('%03s', ($kode_e + 1));

        $overt = overt_h::create([
            'ot_cod' => $kode_f,
            'ot_int' => $ot_int_d,
            'ot_day' => $request->ot_day,
            'ot_dat' => $request->ot_dat,
            'ot_bag' => $request->ot_bag,
            'keterangan' => $request->keterangan,
        ]);

        foreach ($request->ot_cod_d as $key => $value) {
            $nik = pegawai::where('nama_asli', $request->nama_asli[$key])->first();
            overtime::create([
                'ot_cod' => $kode_f,
                'ot_cod_d' => $value,
                'ot_day' => $request->ot_day,
                'ot_dat' => $request->ot_dat,
                'ot_bag' => $request->ot_bag,
                'no_payroll' => $nik->no_payroll,
                'ot_int' => $ot_int_d,
                'nama_asli' => $request->nama_asli[$key],
                'ot_hrb' => $request->ot_hrb[$key],
                'ot_hre' => $request->ot_hre[$key],
                'tugas' => $request->tugas[$key],
                'catatan' => $request->catatan[$key],
                'spk_nomor' => $request->spk_nomor[$key],
                'line' => $request->line[$key],
            ]);
        }

        return redirect()
        ->route('hr.overt.list')
        ->with('success', 'New subject has been added.');    
    }

    public function detail($id)
    {
        $data = overt_h::where('id', $id)
        ->first();
      $data_d = overtime::where('ot_cod', $data->ot_cod)
        ->get();

        return view('hr.dashboard.overt.detail', compact('data','data_d'));
    }

    public function edit($id)
    {
        $bag = bagian::all();

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $data = overt_h::where('id', $id) ->first();
        $data_d = overtime::where('ot_cod', $data->ot_cod)  ->get();
        $ot_cod_d = Str::random(10);
        $jmlh_data_d  = count($data_d);
        $kar = pegawai::orderBy('nama_asli', 'asc')->get();



        return view('hr.dashboard.overt.edit', compact('data','data_d','ot_cod_d','jmlh_data_d','kar','hari','bag'));
    }


    public function delete($id)
{
    $data_h = overt_h::findOrFail($id);
    $data_d = overtime::where('ot_cod', $data_h->ot_cod);
    $data_h->delete();
    $data_d->delete();
    return redirect()
        ->route('hr.overt.list')
        ->with('success', 'Data berhasil dihapus!');
}

public function update(Request $request, $id)
{
    // dd($request->toArray());
    $data_h = overt_h::where('id', $id)->first();
    $data_h->ot_cod = $request->ot_cod;
    $data_h->ot_dat = $request->ot_dat;
    $data_h->ot_day = $request->ot_day;
    $data_h->ot_bag = $request->ot_bag;
    $data_h->keterangan = $request->keterangan;
    $data_h->save();

    // $data_hu = absen_h::where('id', $id)->first();
    // $int = $data_hu->int_absen;
    // $bln = $data_hu->bln_absen;
    // $thn = $data_hu->thn_absen;
    
    // dd($request->toArray());
    foreach ($request->ot_cod_d as $key => $value) {
        $nik = pegawai::where('nama_asli', $request->nama_asli[$key])->first();
        if (isset($request->item_id[$key])) {
            // $bsen_kode = definisi::where('dsc_absen', $request->dsc_absen[$key])->first();
            overtime::where('id', $request->item_id[$key])->update([
                'ot_cod_d' => $value,
                'ot_cod' => $request->ot_cod,
                'ot_dat' => $request->ot_dat,
                'ot_day' => $request->ot_day,
                'no_payroll' => $nik->no_payroll,
                'ot_bag' => $request->ot_bag,
                'ot_cod_d' => $request->ot_cod_d[$key],
                'nama_asli' => $request->nama_asli[$key],
                'ot_hrb' => $request->ot_hrb[$key],
                'ot_hre' => $request->ot_hre[$key],
                'catatan' => $request->catatan[$key],
                'spk_nomor' => $request->spk_nomor[$key],
                'line' => $request->line[$key],
                'tugas' => $request->tugas[$key],
            
    
            ]);
        } else {
            // $bsen_kode = definisi::where('dsc_absen', $request->dsc_absen[$key])->first();
            overtime::create([
                'int_absen_d' => $value,
                'ot_cod' => $request->ot_cod,
                'ot_day' => $request->ot_day,
                'no_payroll' => $nik->no_payroll,
                'ot_cod_d' => $request->ot_cod_d[$key],
                'nama_asli' => $request->nama_asli[$key],
                'ot_hrb' => $request->ot_hrb[$key],
                'ot_hre' => $request->ot_hre[$key],
                'catatan' => $request->catatan[$key],
                'spk_nomor' => $request->spk_nomor[$key],
                'line' => $request->line[$key],
                'tugas' => $request->tugas[$key],
            ]);
        }
    
    }
    // dd($data_h->toArray());
    return redirect()
    ->route('hr.overt.list')
    ->with('success', 'New subject has been updated.');
}

public function delete_d($id)
{
    $data_d = overtime::findOrFail($id);
    $data_d->delete();
    return back();
}
public function print($id)
{
    $data = overt_h::where('id', $id)
    ->first();
  $data_d = overtime::where('ot_cod', $data->ot_cod)
    ->get();
    
    $tgl = Carbon::now()->format('d F Y');


    $pdf = Pdf::loadview('hr.dashboard.overt.print', compact('data','data_d','tgl'));
    return $pdf->setPaper('a4', 'potrait')->stream('OverTime.pdf');
}

}

