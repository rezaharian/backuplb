<?php

namespace App\Http\Controllers;

use App\Models\onoff_tg;
use Illuminate\Http\Request;

class OnOffController extends Controller
{
    //
    public function index(){

        $onoff = onoff_tg::orderBy('tgl_on', 'asc')->get();

        // dd($onoff);
        return view('/hr/dashboard/onoff/index', compact('onoff'));
    }

    public function create(Request $request){

        $onoff = onoff_tg::create([
            'tgl_off' => $request->tgl_off,
            'tgl_on' => $request->tgl_on,

        ]);

        return redirect()
            ->route('hr.onoff.index')
            ->with('success', 'New Date has been added.');
    } 

    public function edit($id, Request $request)
    {
        $onoff = onoff_tg::findorfail($id);

        return view('hr/dashboard/onoff/edit', compact('onoff'));
    }

    public function update(Request $request, $id)
    {
        $onoff = onoff_tg::findOrFail($id);
        $onoff->tgl_off = $request->tgl_off;
        $onoff->tgl_on = $request->tgl_on;
        $onoff->save();

        return redirect()
            ->route('hr.onoff.index')
            ->with('success', 'Data has been Update.');
    }

    public function delete($id)
    {
        $onoff = onoff_tg::findOrFail($id);
        $onoff->delete();
        return redirect()
            ->route('hr.onoff.index')
            ->with('success', 'Data Berhasil di Hapus');
    }
}
