<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class definisi extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_def',
        'jns_absen',
        'dsc_absen',
        'noteabsen',

    ];
}
