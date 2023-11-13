<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use App\Models\kompe_d;
use App\Models\kompe_h;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class kompetensiController extends Controller
{
    public function list()
    {
        $kompetensi = kompe_h::orderby('id', 'desc')->get();
        // dd($view_d->toArray());
        return view('hr.dashboard.training.kompetensi.list', compact('kompetensi'));
    }

    public function view($id)
    {
        $view = kompe_h::findorfail($id);
        $view_d = kompe_d::where('kompe_cod', $view->kompe_cod)->get();
        // dd($view_d->toArray());
        return view('hr.dashboard.training.kompetensi.view', compact('view', 'view_d'));
    }

    public function create()
    {
        $komd = kompe_d::orderBy('id', 'asc')->get();
        $komh = kompe_h::orderBy('id', 'asc')->get();
        $bagian = bagian::orderBy('id', 'asc')->get();

        $kom_h = kompe_h::select('id', 'kompe_cod')
            ->orderby('id', 'desc')
            ->first();

        // pecah no akhir
        $noakhir = $kom_h->kompe_cod;
        $tra_p = substr($noakhir, 0, 3);
        $thna_p = substr($noakhir, 3, 2);
        $bln_p = substr($noakhir, 6, 2);
        $urut_p = substr($noakhir, 8, 3);
        if ($urut_p == null) {
            $urut_p = $urut_p;
        }

        // tahun
        $thn = Carbon::now()->format('Y');
        $bln = Carbon::now()->format('m');
        $thn_p = substr($thn, 2, 2);
        $urut_p++;

        if ($thna_p != $thn_p) {
            $nmr_d = 001;
        } else {
            $nmr_d = $urut_p;
        }

        // dd($nmr_d);
        // dd($tra_p . $thn_p . $bln . $nmr_d);
        $nd = $tra_p . $thn_p . $bln . sprintf('%03s', $nmr_d);

        return view('hr.dashboard.training.kompetensi.create', compact('komd', 'komh', 'nd', 'bagian'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'kompe_cod' => 'required|unique:kompe_hs,kompe_cod',
         
        ]);

        $kompe_h = kompe_h::create([
            'kompe_cod' => $request->kompe_cod,
            'bagian' => $request->bagian,
            'tipe' => 'Training',
        ]);

        foreach ($request->kompetensi as $key => $value) {
            kompe_d::create([
                'kompe_cod' => $request->kompe_cod,
                'kompe_id' => $kompe_h->id,
                'kompetensi' => $value,
                'jenis' => $request->jenis[$key],
            ]);
        }

        //  dd($request->toArray());

        return redirect()->route('hr.kompetensi.list')->with('success', 'New subject has been added.');
    }


    public function edit($id){

        $data = kompe_h::findorfail($id);
        $data_d = kompe_d::where('kompe_cod', $data->kompe_cod)->orderby('id', 'asc')->get();
        $jmlh_d = count($data_d);
        $bag = bagian::get();
        $kompe_h = kompe_h::orderby('bagian', 'asc')->get();
        // dd($view_d->toArray());
        return view('hr.dashboard.training.kompetensi.edit', compact('data', 'data_d','jmlh_d', 'kompe_h', 'bag'));

    }


    
    public function update($id, Request $request, kompe_d $kompe_d, kompe_h $kompe_h)
    {

        $data_h = kompe_h::where('id', $id)->first();
        $data_d = kompe_d::all();

        $data_h->kompe_cod = $request->kompe_cod;
        $data_h->bagian = $request->bagian;
        $data_h->save();

        foreach ($request->kompetensi as $key => $value) {
            if (isset($request->item_id[$key])) {
                kompe_d::where('id', $request->item_id[$key])->update([
                    'kompe_cod' => $request->kompe_cod,
                    'kompetensi' => $value,
                    'jenis' => $request->jenis[$key],
                ]);
            } else {
                kompe_d::create([
                    'kompe_cod' => $request->kompe_cod,
                    'kompetensi' => $value,
                    'jenis' => $request->jenis[$key],
                ]);
            }
        }
        return redirect()->route('hr.kompetensi.list')->with('success', 'New subject has been added.');
    }


    public function delete($id, kompe_h $kompe_h)
    {
        $kompeh = kompe_h::findOrFail($id);
        $komped = kompe_d::where('kompe_cod', $kompeh->kompe_cod);
        $kompeh->delete();
        $komped->delete();
        return redirect()->route('hr.kompetensi.list')->with('success', 'Data berhasil dihapus!');
    }
}