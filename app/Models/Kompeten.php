<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompeten extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
         'nomor',
         'perf_faktor',
         'penjelasan',
         'type',
         'bagian',
         'jabatan',

    ];
}
