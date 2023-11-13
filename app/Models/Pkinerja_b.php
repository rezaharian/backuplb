<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pkinerja_b extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
         'kode',
         'perf_faktor',
         'penjelasan',
         'nilai1',
         'nilai2',
         'nilai3',
         'un1',
         'un2',
         'un3',
       
    ];
}
