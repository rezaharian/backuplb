<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function cek() {
        if (auth()->user()->role_id == 1) {
            return redirect('/superadmin');
        } elseif (auth()->user()->role_id == 2) {
            return redirect('/bos');
        }elseif (auth()->user()->role_id == 3) {
            return redirect('/hr');
        } elseif (auth()->user()->role_id == 4) {
            return redirect('/pegawai');
        } elseif (auth()->user()->role_id == 5) {
            return redirect('/trainer');
        } elseif (auth()->user()->role_id == 6) {
            return redirect('/qc');
        } elseif (auth()->user()->role_id == 7) {
            return redirect('/produksi');
        } else {
            return redirect('/umum');
        }
    }
}