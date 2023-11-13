<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pt_gaji extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_payroll',
        'nama_asli',
        'gj_bulan',
        'no_bln',
        'thn',
        'tahun_cuti',
        'jml_hari',
        'keterangan',
    ];
}
