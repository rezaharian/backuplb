<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pkinerja extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
         'kode',
         'no_payroll',
         'nama',
         'bagian',
         'jabatan',
         'periode',
         'review',
         'mkr',
         'mkr_value',
         'ijin',
         'ijin_value',
         'sakit',
         'sakit_value',
         'mdt',
         'mdt_value',
         'sp',
         'sp_value',
         'score_a',
         'score_b',
         'score_c',
         'total_score',
         'remark',
         'awal',
         'akhir',

    ];
}
