<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BosController extends Controller
{
    public function index() {
        return view('bos.index');
    }
}


