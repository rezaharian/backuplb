<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prob_msd extends Model
{
    use HasFactory;
    protected $fillable = [
        'internal',
        'id_no',
        'prob_cod',  
        'tgl_input',  
        'penyebab',  
        'perbaikan',  
        'tgl_rpr',  
        'pencegahan',  
        'tgl_pre',  
    ];
}
