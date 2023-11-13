<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function dologin(Request $request) {
        // validasi
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);



        if (auth()->attempt($credentials)) {

            // buat ulang session login
            $request->session()->regenerate();

            if (auth()->user()->role_id == 1) {
                return redirect()->intended('/superadmin');
            } 
            elseif (auth()->user()->role_id == 2) {
                return redirect()->intended('/bos');
            } 
            elseif (auth()->user()->role_id == 3) {
                return redirect()->intended('/hr');
            } 
            elseif (auth()->user()->role_id == 4) {
                return redirect()->intended('/pegawai');
            } 
            elseif (auth()->user()->role_id == 5) {
                return redirect()->intended('/trainer');
            } 
            elseif (auth()->user()->role_id == 6) {
                return redirect()->intended('/qc');
            } 
            elseif (auth()->user()->role_id == 7) {
                return redirect()->intended('/produksi');
            } 
            else {
                return redirect()->intended('/umum');
            }
        }

        // jika email atau password salah
        // kirimkan session error
        return back()->with('error', 'email atau password salah');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
