<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal',
        'no_reg',
        'masuk',
        'keluar',
        'norm_m',
        'norm_k',
        'lama',
        'lembur',
        'gkcod',
       
    ];
}
