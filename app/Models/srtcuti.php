<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class srtcuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'ct_int',
        'ct_nom',
        'ct_tgl',
        'ct_unt',
        'ct_reg',
        'ct_peg',
        'ct_nam',
        'ct_jbt',
        'ct_regi',
        'ct_jml',
        'ct_tls',
        'ct_dr1',
        'ct_sd1',
        'ct_dr2',
        'ct_sd2',
        'ct_not',
        'pemohon',
        'setuju',
        'ket_atas',
        'ket_pers',
        'skt',
        'ijn',
        'cti',
        'tlb',
        'mkr',
        'ipa',
        'scl',
        'scs',
        'scb',
    ];
}
