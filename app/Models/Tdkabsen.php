<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tdkabsen extends Model
{
    use HasFactory;
    protected $fillable = [
        'ta_int',
        'ta_cod',
        'ta_tgl',
        'no_payroll',
        'nama_asli',
        'pdsaat',
        'masuk',
        'pulang',
        'gkcod',   
        'status',   
       
    ];
}
