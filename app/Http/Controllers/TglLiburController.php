<?php

namespace App\Http\Controllers;

use App\Models\TglLibur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TglLiburController extends Controller
{
    
    public function list(){
        $tgllibur = TglLibur::select('id', 'tgl_libur', 'keterangan')
        ->orderBy('tgl_libur', 'asc')
        ->get();
    
    $tgllibur->transform(function ($item) {
        $item->tgl_libur = Carbon::parse($item->tgl_libur)->format('d-m-Y');
        return $item;
    });
    
        return view('hr.dashboard.tgllibur.list', compact('tgllibur'));
    }

    public function create(Request $request){

        $tgllibur = TglLibur::create([
            'tgl_libur' => $request->tgl_libur,
            'keterangan' => $request->keterangan,

        ]);

        return redirect()
            ->route('hr.tgllibur.list')
            ->with('success', 'New Date has been added.');
    }

    public function edit($id, Request $request)
    {
        $tgllibur = TglLibur::findorfail($id);

        return view('hr/dashboard/tgllibur/edit', compact('tgllibur'));
    }

    public function update(Request $request, $id)
    {
        $tgllibur = TglLibur::findOrFail($id);
        $tgllibur->tgl_libur = $request->tgl_libur;
        $tgllibur->keterangan = $request->keterangan;
        $tgllibur->save();

        return redirect()
            ->route('hr.tgllibur.list')
            ->with('success', 'Data has been Update.');
    }

    public function delete($id)
    {
        $tgllibur = tgllibur::findOrFail($id);
        $tgllibur->delete();
        return redirect()
            ->route('hr.tgllibur.list')
            ->with('success', 'Data Berhasil di Hapus');
    }
}
