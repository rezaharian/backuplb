<?php

namespace App\Http\Controllers;

use App\Models\absen_d;
use App\Models\absen_h;
use App\Models\definisi;
use App\Models\peg_d;
use App\Models\pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UmumAbsenController extends Controller
{
    //
    public function list(Request $request)
    {
        $nm = $request->cari;

        if (request()->has('bulan') || request()->has('tahun')) {
            $bln = request()->input('bulan');
            $thn = request()->input('tahun');
            $data = DB::table('absen_ds')
                ->leftjoin('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
                ->select('absen_hs.no_payroll', 'absen_hs.nama_asli', 'absen_hs.bagian')
                ->where('absen_hs.bln_absen', 'LIKE', '%' . $bln . '%')
                ->where('absen_hs.thn_absen', 'LIKE', '%' . $thn . '%')
                ->where('absen_hs.no_payroll', 'LIKE', '%' . $nm . '%')
                ->groupBy('nama_asli', 'no_payroll', 'bagian')
                ->orderBy('absen_ds.tgl_absen', 'desc')
                ->limit(100)
                ->get();
        } else {
            $tgl_s = Carbon::now();
            $bln = $tgl_s->locale('id')->isoFormat('MMMM');
            $thn = $tgl_s->year;
            $data = DB::table('absen_ds')
                ->leftjoin('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
                ->select('absen_hs.no_payroll', 'absen_hs.nama_asli', 'absen_hs.bagian')
                ->where('absen_hs.bln_absen', 'LIKE', '%' . $bln . '%')
                ->where('absen_hs.thn_absen', 'LIKE', '%' . $thn . '%')
                ->where('absen_hs.no_payroll', 'LIKE', '%' . $nm . '%')
                ->groupBy('nama_asli', 'no_payroll', 'bagian')
                ->orderBy('absen_ds.tgl_absen', 'desc')
                ->limit(100)
                ->get();
            // dd($bln.$thn);
        }


        $bulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $namaBulan = '';
            switch ($i) {
                case 1:
                    $namaBulan = 'Januari';
                    break;
                case 2:
                    $namaBulan = 'Februari';
                    break;
                case 3:
                    $namaBulan = 'Maret';
                    break;
                case 4:
                    $namaBulan = 'April';
                    break;
                case 5:
                    $namaBulan = 'Mei';
                    break;
                case 6:
                    $namaBulan = 'Juni';
                    break;
                case 7:
                    $namaBulan = 'Juli';
                    break;
                case 8:
                    $namaBulan = 'Agustus';
                    break;
                case 9:
                    $namaBulan = 'September';
                    break;
                case 10:
                    $namaBulan = 'Oktober';
                    break;
                case 11:
                    $namaBulan = 'November';
                    break;
                case 12:
                    $namaBulan = 'Desember';
                    break;
            }
            $bulan[] = $namaBulan;
        }
        
        
        $tahun = range(2000, 2050);

        // dd($bulan);

        return view('umum.dashboard.absen.list', compact('data', 'bulan', 'tahun', 'bln', 'thn'));
    }


    public function autocomplete(Request $request)
    {
        $pegawai = [];
        if ($request->has('q')) {
            $search = $request->q;
            $pegawai = pegawai::select('id', 'no_payroll', 'nama_asli')
                ->where('nama_asli', 'LIKE', "%$search%")
                ->orWhere('no_payroll', 'LIKE', "%$search%")
                ->where('tgl_keluar', null)
                ->get();
        }
        return response()->json($pegawai);
    }
    public function create($bln, $thn)
    {

       

        $int_absen = Str::random(10);
        $int_absen_d = Str::random(10);
        $pegawai = pegawai::orderBy('nama_asli', 'ASC')
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->whereNull('tgl_keluar')
            ->get();

        $def = definisi::all();

        return view('umum.dashboard.absen.create', compact('pegawai', 'int_absen', 'int_absen_d', 'def', 'bln', 'thn'));
    }

    public function find_pegawai(Request $request, $id)
    {
        $pegawai = pegawai::findOrFail($id);
        // $date = date('d,m,Y', strtotime($pegawai->tgl_masuk));
        return response()->json([
            'nama_asli' => $pegawai->nama_asli,
            'tgl_masuk' => $pegawai->tgl_masuk,
            'bagian' => $pegawai->bagian,
            'no_payroll' => $pegawai->no_payroll,
        ]);
    }
    public function find_absen_h(Request $request, $id)
    {
        $absen_h = absen_h::findOrFail($id);
        // $date = date('d,m,Y', strtotime($pegawai->tgl_masuk));
        return response()->json([
            'nama_asli' => $absen_h->nama_asli,
            'tgl_masuk' => $absen_h->tgl_masuk,
            'bagian' => $absen_h->bagian,
            'no_payroll' => $absen_h->no_payroll,
        ]);
    }

    public function store(Request $request, $bln, $thn)
    {

        $dua = db::table('absen_hs')
        ->where('thn_absen', $thn)
        ->where('bln_absen', $bln)
        ->where('nama_asli', $request->nama_asli)
        ->exists();

        $dtt = absen_h::where('nama_asli', $request->nama_asli)
        ->where('bln_absen', $bln)
        ->where('thn_absen', $thn)
        ->first();

        // dd($dtt);
        if ($dua) {
            return redirect()
            ->route('umum.absen.edit', $dtt->id)
            ->with('success', 'Data sudah ada pada bulan tersebut, Silahkan tambahkan di sini !');  
      
           }else{
        $absen_h = absen_h::create([
            'no_reg' => $request->no_payroll,
            'no_payroll' => $request->no_payroll,
            'int_absen' => $request->int_absen,
            'nama_asli' => $request->nama_asli,
            'tgl_masuk' => $request->tgl_masuk,
            'bagian' => $request->bagian,
            'bln_absen' => $bln,
            'thn_absen' => $thn,
        ]);

        foreach ($request->int_absen_d as $key => $value) {
            $bsen_kode = definisi::where('dsc_absen', $request->dsc_absen[$key])->first();
            absen_d::create([
                'int_absen_d' => $value,
                'tgl_absen' => $request->tgl_absen[$key],
                'dsc_absen' => $request->dsc_absen[$key],
                'keterangan' => $request->keterangan[$key],
                'thn_jns' => $request->thn_jns[$key],
                'jns_absen' => $bsen_kode->jns_absen,
                'no_reg' => $request->no_payroll,
                'int_absen' => $request->int_absen,
                'bln_absen' => $bln,
                'thn_absen' => $thn,
            ]);
        }

        return redirect()
            ->route('umum.absen.list', ['tahun' =>$thn, 'bulan'=>$bln])
            ->with('success', 'New subject has been added.');
    }
    }

    public function detail($bln, $thn, $id)
    {
        $data = absen_h::where('no_payroll', $id)
            ->where('bln_absen', $bln)
            ->where('thn_absen', $thn)
            ->first();
        $data_d = absen_d::where('int_absen', $data->int_absen)
            ->where('bln_absen', $bln)
            ->where('thn_absen', $thn)
            ->get();

        return view('umum.dashboard.absen.detail', compact('data', 'data_d','bln','thn'));
    }

    public function print($bln, $thn, $id)
    {

        // dd($id);
        $data = absen_h::where('id', $id)
            ->where('bln_absen', $bln)
            ->where('thn_absen', $thn)
            ->first();
            $data_d = absen_d::where('int_absen', $data->int_absen)
            ->where('bln_absen', $bln)
            ->where('thn_absen', $thn)
            ->get();


            $pdf = Pdf::loadview('umum.dashboard.absen.print', compact('data', 'data_d', 'bln', 'thn'));
            return $pdf->setPaper('a4', 'portrait')->stream('laporan absen.pdf');

    }


    public function edit(Request $request, $id)
    {
        $data = absen_h::where('id', $id)->first();
        $data_d = absen_d::where('int_absen', $data->int_absen)->get();
        $int_absen_d = Str::random(10);
        $def = definisi::all();
        $jmlh_data_d  = count($data_d);
        // dd($data_d->toArray());

        return view('umum.dashboard.absen.edit', compact('data', 'data_d','def','int_absen_d','jmlh_data_d'));
    }

    public function update(Request $request, $id)
    {
        $data_h = absen_h::where('id', $id)->first();
        $data_h->no_payroll = $request->no_payroll;
        $data_h->nama_asli = $request->nama_asli;
        $data_h->bagian = $request->bagian;
        $data_h->tgl_masuk = $request->tgl_masuk;
        $data_h->save();

        $data_hu = absen_h::where('id', $id)->first();
        $int = $data_hu->int_absen;
        $bln = $data_hu->bln_absen;
        $thn = $data_hu->thn_absen;
        
        // dd($request->toArray());
        foreach ($request->int_absen_d as $key => $value) {
            if (isset($request->item_id[$key])) {
                $bsen_kode = definisi::where('dsc_absen', $request->dsc_absen[$key])->first();
                absen_d::where('id', $request->item_id[$key])->update([
                    'int_absen_d' => $value,
                    'tgl_absen' => $request->tgl_absen[$key],
                    'dsc_absen' => $request->dsc_absen[$key],
                    'keterangan' => $request->keterangan[$key],
                    'thn_jns' => $request->thn_jns[$key],
                    'jns_absen' => $bsen_kode->jns_absen,
                    'bln_absen' => $bln,
                    'thn_absen' =>$thn,
                    'int_absen' =>$int,
        
                ]);
            } else {
                $bsen_kode = definisi::where('dsc_absen', $request->dsc_absen[$key])->first();
                absen_d::create([
                    'int_absen_d' => $value,
                    'tgl_absen' => $request->tgl_absen[$key],
                    'dsc_absen' => $request->dsc_absen[$key],
                    'keterangan' => $request->keterangan[$key],
                    'thn_jns' => $request->thn_jns[$key],
                    'jns_absen' => $bsen_kode->jns_absen,
                    'bln_absen' => $bln,
                    'thn_absen' =>$thn,
                    'int_absen' =>$int,
                ]);
            }
        
        }
        // dd($data_h->toArray());
        return redirect()
        ->route('umum.absen.list', ['tahun' =>$thn, 'bulan'=>$bln])
        ->with('success', 'New subject has been updated.');
}

public function delete_d($id)
{
    $data_d = absen_d::findOrFail($id);
    $data_d->delete();
    return back();
}

public function delete($id)
{
    $data_h = absen_h::findOrFail($id);
    $data_d = absen_d::where('int_absen', $data_h->int_absen);
    $data_h->delete();
    $data_d->delete();
    return redirect()
        ->route('umum.absen.list')
        ->with('success', 'Data berhasil dihapus!');
}


}