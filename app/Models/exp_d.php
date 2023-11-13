<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exp_d extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_payroll',
        'perusahaan',
        'alamat',
        'jabatan',
        'keterangan',
    ];
}
