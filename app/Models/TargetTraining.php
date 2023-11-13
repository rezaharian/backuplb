<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetTraining extends Model
{
    use HasFactory;
    protected $fillable = [
        'tgl_input',
        'bagian',  
        'jumlah_jam',  
        'periode_awal',  
        'periode_akhir',   
    ];

    protected $casts = [
        'periode_awal' => 'string',
        'periode_akhir' => 'string',
    ];

}
