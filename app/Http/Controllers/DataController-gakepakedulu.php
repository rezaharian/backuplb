<?php

namespace App\Http\Controllers;

use App\Models\data;
use App\Models\DdataMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index($jalur_id, Request $request)
    {
        $datas = data::where('jalur_id', $jalur_id)
            ->limit(8)
            ->orderBy('created_at', 'desc')
            ->get();
        $awal = data::select('akhir')->where('jalur_id', $jalur_id);
        $tgl = Carbon::now()->format('d-m-Y ');
        // $wkt = Carbon::now()->format('H');

        $wkt = 3;

        $prev = data::orderBy('id', 'desc')
            ->where('jalur_id', $jalur_id)
            ->limit(1)
            ->get();
        // dd($wkt);
        if ($wkt >= 7 && $wkt <= 16) {
            $shiftany = 'I';
        } elseif ($wkt >= 17 && $wkt <= 23) {
            $shiftany = 'II';
        } else {
            $shiftany = 'III';
        }

        $arr = ['jalur_id' => $jalur_id];
        return view('data.data', compact('datas', 'awal', 'tgl', 'prev', 'arr', 'shiftany'));
    }

    public function insertdata($jalur_id, Request $request)
    {
        $datas = data::where('jalur_id', $jalur_id)->get();

        $nilaiakhir = data::where('jalur_id', $jalur_id)
            ->orderBy('id', 'DESC')
            ->first();

        // dd($nilaiakhir->akhir);

        $dms = DdataMaster::select('shift')
            ->orderBy('id', 'DESC')
            ->first();
        $arr = ['jalur_id' => $jalur_id];

        $tgl = Carbon::now()->format('d-m-Y ');

        return view('data.insert', compact('datas', 'dms', 'tgl', 'arr', 'nilaiakhir'));
    }
    public function insertfielddata($jalur_id, Request $request)
    {
        //     // $request->validate([
        //     //     'addMoreInputFields.*.tanggal' => 'required',
        //     //     'addMoreInputFields.*.shift' => 'required',
        //     //     'addMoreInputFields.*.reset' => 'required',
        //     //     'addMoreInputFields.*.awal' => 'required',
        //     //     'addMoreInputFields.*.akhir' => 'required',
        //     //     'addMoreInputFields.*.kalkulasi' => 'required',
        //     //     'addMoreInputFields.*.jumlahpershift' => 'required',
        //     //     'addMoreInputFields.*.jumlahperhari' => 'required',
        //     //     'addMoreInputFields.*.awaljam' => 'required',
        //     //     'addMoreInputFields.*.awalmenit' => 'required',
        //     //     'addMoreInputFields.*.akhirjam' => 'required',
        //     //     'addMoreInputFields.*.akhirmenit' => 'required',
        //     //     'addMoreInputFields.*.runtimemenit' => 'required',
        //     //     'addMoreInputFields.*.etc' => 'required',
        //     //     'addMoreInputFields.*.jalur_id' => 'required',
        //     //     'addMoreInputFields.*.runtimeshift' => 'required',
        //     //     'addMoreInputFields.*.runtimehari' => 'required',
        //     //     'addMoreInputFields.*.spk' => 'required',
        //     //     'addMoreInputFields.*.produk' => 'required',
        //     //     'addMoreInputFields.*.keterangan' => 'required',
        //     //     'addMoreInputFields.*.operator' => 'required',
        //     //     'addMoreInputFields.*.resetw' => 'required',
        //     // ]);

        $input = $request->all();
        $input['jalur_id'] = $jalur_id;
        foreach ($request->addMoreInputFields as $key => $value) {
            data::create($value);
        }

        return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    }

    public function store($jalur_id, Request $request)
    {
        $request->validate([
            'akhir' => 'required',
            'akhirjam' => 'required',
            'akhirmenit' => 'required',
        ]);

        $input = $request->all();
        $input['jalur_id'] = $jalur_id;

        data::create($input);
        return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    }

    public function edit($jalur_id, data $data)
    {
        $arr = ['jalur_id' => $jalur_id];

        return view('data.edit', compact('data', 'arr'));
    }

    // public function reset(data $data)
    // {

    //     $data->update([
    //         'reset' => 0,
    //         'awal' => 0,
    //         'akhir' => 0,
    //         'kalkulasi' => 0,
    //         'awaljam' => 0,
    //         'awalmenit' => 0,
    //         'akhirjam' => 0,
    //         'akhirmenit' => 0,
    //         'runtimemenit' => 0,
    //         'jumlahpershift' => 0,
    //         'jumlahperhari' => 0,
    //         'etc' => 0,
    //     ]);

    //     return redirect()->route('data.index', compact('data'));

    // }

    public function update($jalur_id, Request $request, data $data)
    {
        if ($request->ajax()) {
            data::find($request->pk)->update([
                $request->name => $request->value,
            ]);

            // return response()->json(['success' => true])->with();
            return redirect()->route('data.batalresetpcsrefresh', ['jalur_id' => $data->jalur_id, 'data' => $request->pk]);
        }
    }

    // public function update1($jalur_id, Request $request, data $data)
    // {
    //     $request->validate([
    //         'tanggal' => 'required',
    //         'reset' => 'required',
    //         'awal' => 'required',
    //         'akhir' => 'required',
    //         'kalkulasi' => 'required',
    //         'jumlahpershift' => 'required',
    //         'jumlahperhari' => 'required',
    //         'awaljam' => 'required',
    //         'awalmenit' => 'required',
    //         'akhirjam' => 'required',
    //         'akhirmenit' => 'required',
    //         'runtimemenit' => 'required',
    //         'etc' => 'required',
    //     ]);

    //     $datapro = data::where('id', '>=', $request->id)
    //         ->where('jalur_id', $jalur_id)
    //         ->take(100)
    //         ->orderBy('id', 'ASC')
    //         ->get();

    //     //   dd($datapro->toArray());

    //     $i = 0;
    //     $awala = 0;
    //     $awaljama = 0;
    //     $awalmenita = 0;
    //     $jumlahpershifta = 0;
    //     $jumlahperharia = 0;

    //     foreach ($datapro as $dt) {
    //         if ($dt->reset == 'YES' && $i > 0) {
    //             break;
    //         }
    //         if ($i == 0) {
    //             $awal = $request->awal;
    //             $akhir = $request->akhir;
    //             $kalkulasi = $request->akhir - $request->awal;
    //             $awaljam = $request->awaljam;
    //             $awalmenit = $request->awalmenit;
    //             $akhirjam = $request->akhirjam;
    //             $akhirmenit = $request->akhirmenit;
    //             $runtimemenit = $request->akhirjam * 60 + $request->akhirmenit - ($request->awaljam * 60 + $request->awalmenit);
    //             $jumlahpershift = $request->jumlahpershift;
    //             $jumlahperhari = $request->jumlahperhari;
    //             $etc = $request->etc;
    //             $reset = $request->reset;
    //         } else {
    //             $awal = $awala;
    //             $akhir = $dt->akhir;
    //             $kalkulasi = $akhir - $awal;
    //             $awaljam = $awaljama;
    //             $awalmenit = $awalmenita;
    //             $akhirjam = $dt->akhirjam;
    //             $akhirmenit = $dt->akhirmenit;
    //             $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
    //             $jumlahpershift = $jumlahpershifta + $dt->kalkulasi;
    //             $jumlahperhari = $jumlahperharia + $dt->jumlahpershift;
    //             $etc = $dt->etc;
    //             $reset = $dt->reset;
    //         }

    //         $awala = $akhir;
    //         $awaljama = $akhirjam;
    //         $awalmenita = $akhirmenit;
    //         $jumlahpershifta = $jumlahpershift;
    //         $jumlahperharia = $jumlahperhari;

    //         $dt->update([
    //             'reset' => $reset,
    //             'awal' => $awal,
    //             'akhir' => $akhir,
    //             'kalkulasi' => $kalkulasi,
    //             'awaljam' => $awaljam,
    //             'awalmenit' => $awalmenit,
    //             'akhirjam' => $akhirjam,
    //             'akhirmenit' => $akhirmenit,
    //             'runtimemenit' => $runtimemenit,
    //             'jumlahpershift' => $jumlahpershift,
    //             'jumlahperhari' => $jumlahperhari,
    //             'etc' => $etc,
    //         ]);

    //         $i++;
    //     }

    //     // $data->update($request->all());

    //     return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    // }

    public function reset($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        //   dd($datapro->toArray());
        $i = 0;
        $awala = 0;
        $awaljama = 0;
        $awalmenita = 0;
        $jumlahpershifta = 0;
        $jumlahperharia = 0;
        $runtimeshifta = 0;
        $runtimeharia = 0;

        foreach ($datapro as $dt) {
            if ($dt->reset == 'YES' && $i > 0) {
                break;
            }

            if ($i == 0) {
            
                $awal = 0;
                $akhir = $dt->akhir;
                $kalkulasi = 0;
                $jumlahpershift = 0;
                $jumlahperhari = 0;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $dt->runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = 'YES';
                $resetw = $dt->resetw;
            } else {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                $jumlahperhari =($jumlahpershifta + ($akhir - $awal)) + $jumlahperharia;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $dt->runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = $dt->resetw;
            }

            $awala = $akhir;
            $awaljama = $akhirjam;
            $awalmenita = $akhirmenit;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $runtimeshifta = $runtimeshift;
            $runtimeharia = $runtimehari;


            // dd($dt->toArray());
            $dt->update([
                'reset' => $reset,
                'resetw' => $resetw,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
                // 'awaljam' => $awaljam,
                // 'awalmenit' => $awalmenit,
                // 'akhirjam' => $akhirjam,
                // 'akhirmenit' => $akhirmenit,
                // 'runtimemenit' => $runtimemenit,
                // 'runtimeshift' => $runtimeshift,
                // 'runtimehari' => $runtimehari,
                'spk' => $spk,
                'produk' => $produk,
                'keterangan' => $keterangan,
                'operator' => $operator,
                'etc' => $etc,
            ]);

            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    }

    public function resetwaktu($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        //   dd($datapro->toArray());
        $i = 0;
        $awala = 0;
        $awaljama = 0;
        $awalmenita = 0;
        $jumlahpershifta = 0;
        $jumlahperharia = 0;
        $runtimeshifta = 0;
        $runtimeharia = 0;

        foreach ($datapro as $dt) {
            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + $dt->kalkulasi;
                $jumlahperhari = 
                $awaljam = 0;
                $awalmenit = 0;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = 0;
                $runtimeshift = 0;
                $runtimehari = 0;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = 'YES';
            } else {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                $jumlahperhari =($jumlahpershifta + ($akhir - $awal)) + $jumlahperharia;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = $dt->resetw;
            }

            $awala = $akhir;
            $awaljama = $akhirjam;
            $awalmenita = $akhirmenit;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $runtimeshifta = $runtimeshift;
            $runtimeharia = $runtimehari;

            $dt->update([
                'reset' => $reset,
                'resetw' => $resetw,
                // 'awal' => $awal,
                // 'akhir' => $akhir,
                // 'kalkulasi' => $kalkulasi,
                // 'jumlahpershift' => $jumlahpershift,
                // 'jumlahperhari' => $jumlahperhari,
                'awaljam' => $awaljam,
                'awalmenit' => $awalmenit,
                'akhirjam' => $akhirjam,
                'akhirmenit' => $akhirmenit,
                'runtimemenit' => $runtimemenit,
                'runtimeshift' => $runtimeshift,
                'runtimehari' => $runtimehari,
                'spk' => $spk,
                'produk' => $produk,
                'keterangan' => $keterangan,
                'operator' => $operator,
                'etc' => $etc,
            ]);
            if ($data->reset == 'YES') {
                break;
            }

            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    }

    public function batalresetpcs($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        $prev = data::where('jalur_id', $jalur_id)
            ->where('id', '<', $data->id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($prev->toArray());

        //   dd($datapro->toArray());
        $i = 0;
        $awala = $prev->akhir;
        $awaljama = 0;
        $awalmenita = 0;
        $jumlahpershifta = $prev->jumlahpershift;
        $jumlahperharia = $prev->jumlahperhari;
        $runtimeshifta = 0;
        $runtimeharia = 0;

        foreach ($datapro as $dt) {
            if ($dt->reset == 'YES' && $i > 0) {
                break;
            }

            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + $kalkulasi;
                $jumlahperhari = $jumlahperharia + $jumlahpershift;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $dt->runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = 'NO';
                $resetw = 'NO';
            } else {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                $jumlahperhari =($jumlahpershifta + ($akhir - $awal)) + $jumlahperharia;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $dt->runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = $dt->resetw;
            }

            $awala = $akhir;
            $awaljama = $akhirjam;
            $awalmenita = $akhirmenit;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $runtimeshifta = $runtimeshift;
            $runtimeharia = $runtimehari;

            $dt->update([
                'reset' => $reset,
                'resetw' => $resetw,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
                'awaljam' => $awaljam,
                'awalmenit' => $awalmenit,
                'akhirjam' => $akhirjam,
                'akhirmenit' => $akhirmenit,
                'runtimemenit' => $runtimemenit,
                'runtimeshift' => $runtimeshift,
                'runtimehari' => $runtimehari,
                'spk' => $spk,
                'produk' => $produk,
                'keterangan' => $keterangan,
                'operator' => $operator,
                'etc' => $etc,
            ]);
            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    }

    public function batalresettime($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        $prev = data::where('jalur_id', $jalur_id)
            ->where('id', '<', $data->id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($prev->toArray());

        //   dd($datapro->toArray());
        $i = 0;
        $awala = $prev->akhir;
        $awaljama = $prev->akhirjam;
        $awalmenita = $prev->akhirmenit;
        $jumlahpershifta = $prev->jumlahpershift;
        $jumlahperharia = $prev->jumlahperhari;
        $runtimeshifta = $prev->runtimeshift;
        $runtimeharia = $prev->runtimehari;

        foreach ($datapro as $dt) {
            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + $kalkulasi;
                $jumlahperhari = $jumlahperharia + $jumlahpershift;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $runtimemenit;
                $runtimehari = $runtimeharia + $runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = 'NO';
                $resetw = 'NO';
            } else {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                $jumlahperhari =($jumlahpershifta + ($akhir - $awal)) + $jumlahperharia;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $dt->runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = $dt->resetw;
            }

            $awala = $akhir;
            $awaljama = $akhirjam;
            $awalmenita = $akhirmenit;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $runtimeshifta = $runtimeshift;
            $runtimeharia = $runtimehari;

            $dt->update([
                'reset' => $reset,
                'resetw' => $resetw,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
                'awaljam' => $awaljam,
                'awalmenit' => $awalmenit,
                'akhirjam' => $akhirjam,
                'akhirmenit' => $akhirmenit,
                'runtimemenit' => $runtimemenit,
                'runtimeshift' => $runtimeshift,
                'runtimehari' => $runtimehari,
                'spk' => $spk,
                'produk' => $produk,
                'keterangan' => $keterangan,
                'operator' => $operator,
                'etc' => $etc,
            ]);
         

            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    }

    public function batalresetpcsrefresh($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        $prev = data::where('jalur_id', $jalur_id)
            ->where('id', '<', $data->id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($prev->toArray());

        //   dd($datapro->toArray());
        $i = 0;
        $awala = $prev->akhir;
        $awaljama = $prev->akhirjam;
        $awalmenita = $prev->akhirmenit;
        $jumlahpershifta = $prev->jumlahpershift;
        $jumlahperharia = $prev->jumlahperhari;
        $runtimeshifta = $prev->runtimeshift;
        $runtimeharia = $prev->runtimehari;

        foreach ($datapro as $dt) {
        
            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + $kalkulasi;
                $jumlahperhari = $jumlahperharia + $jumlahpershift;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = ($dt->akhirjam) * 60 + ($dt->akhirmenit) - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $runtimemenit;
                $runtimehari = $runtimeharia + $runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = 'NO';
                $resetw = 'NO';
            } else {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                $jumlahperhari =($jumlahpershifta + ($akhir - $awal)) + $jumlahperharia;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = ($dt->akhirjam) * 60 + ($dt->akhirmenit) - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + (($dt->akhirjam) * 60 + ($dt->akhirmenit) - ($awaljam * 60 + $awalmenit));
                $runtimehari = $runtimeharia + ($runtimeshifta + (($dt->akhirjam) * 60 + ($dt->akhirmenit) - ($awaljam * 60 + $awalmenit)));
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = $dt->resetw;
            }

            $awala = $akhir;
            $awaljama = $akhirjam;
            $awalmenita = $akhirmenit;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $runtimeshifta = $runtimeshift;
            $runtimeharia = $runtimehari;

            $dt->update([
                'reset' => $reset,
                'resetw' => $resetw,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
                'awaljam' => $awaljam,
                'awalmenit' => $awalmenit,
                'akhirjam' => $akhirjam,
                'akhirmenit' => $akhirmenit,
                'runtimemenit' => $runtimemenit,
                'runtimeshift' => $runtimeshift,
                'runtimehari' => $runtimehari,
                'spk' => $spk,
                'produk' => $produk,
                'keterangan' => $keterangan,
                'operator' => $operator,
                'etc' => $etc,
            ]);


            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('data.index', ['jalur_id' => $jalur_id]);
    }
}
