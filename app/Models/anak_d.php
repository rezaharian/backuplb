<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anak_d extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_anak',
        'no_payroll',
        'nama',
        'kelamin',  
        'tgl_lahir',  
        'pendidikan',  
    ];
}

