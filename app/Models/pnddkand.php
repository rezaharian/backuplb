<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pnddkand extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_payroll',
        'tingkat',
        'sekolah',
        'tempat',
        'jurusan',
        'tahun_izs',
        'id_pdd',
        'keterangan',
       
    ];
}
