<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class PemateriController extends Controller
{
    public function index(){
        $pemateri = Trainer::orderBy('id', 'desc')->get();

        return view('hr.dashboard.training.pemateri.index', compact('pemateri'));
    }

    public function store( Request $request, Trainer $Trainer){

        $validatedData = $request->validate([
            'nama' => 'required',
            'level' => 'required',
            'ket' => '',
        ]);

        Trainer::create([
            'nama' => $validatedData['nama'],
            'level' => $validatedData['level'],
            'ket' => $validatedData['ket'],
        ]);

        return back()->with('success', 'New subject has been added.');
    }

    
public function edit($id){

    $pemateri = Trainer::findorfail($id);

    return view('hr.dashboard.training.pemateri.edit', compact('pemateri'));
}

public function update( Request $request, $id){

            $tra = Trainer::findOrFail($id);
            $tra->nama = $request->nama;
            $tra->level = $request->level;
            $tra->ket = $request->ket;
            // dd($request->toArray());
            $tra->save();

    return redirect()->route('pemateri.index')->with('success', 'New subject has been Update.');
}

    public function delete($id, Trainer $Trainer){

        $tra = Trainer::findOrFail($id);
        $tra->delete();
        return back()
            ->with('success', 'Data berhasil dihapus!');
    }
}
