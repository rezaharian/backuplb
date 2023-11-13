<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use Illuminate\Http\Request;

class HrTrainingController extends Controller
{
    public function bagianIndex(){

        $bagians = bagian::orderBy('id', 'asc')->get();
        return view('hr.dashboard.training.bagian.index', compact('bagians'));
    }

    public function storebag( Request $request, bagian $bagian){

        $validatedData = $request->validate([
            'bagian' => 'required',
            'departemen' => 'required',
            'seksi' => 'required',
        ]);

        bagian::create([
            'bagian' => $validatedData['bagian'],
            'departemen' => $validatedData['departemen'],
            'seksi' => $validatedData['seksi'],
        ]);

        return back()->with('success', 'New subject has been added.');
    }

public function edit($id){

    $bagian = bagian::findorfail($id);

    return view('hr.dashboard.training.bagian.edit', compact('bagian'));
}

public function update( Request $request, $id){

            $bag = bagian::findOrFail($id);
            $bag->bagian = $request->bagian;
            $bag->departemen = $request->departemen;
            $bag->seksi = $request->seksi;
            $bag->save();

    return redirect()->route('hr.bagian.index')->with('success', 'New subject has been Update.');
}





    public function delete($id, bagian $bagian){

        $bag = bagian::findOrFail($id);
        $bag->delete();
        return back()
            ->with('success', 'Data berhasil dihapus!');
    }
}
