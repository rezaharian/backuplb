<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prob_msn extends Model
{
    use HasFactory;
    protected $fillable = [
        'internal',
        'prob_cod',  
        'tgl_input',  
        'line',  
        'unitmesin',  
        'masalah',  
        'img_pro01',  
        'img_pro02',  
        'img_pro03',  
        'img_pro04',  
    ];

}
