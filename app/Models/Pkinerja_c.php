<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pkinerja_c extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
         'kode',
         'major_job',
         'perform_ach',
         'nilai1',
         'nilai2',

       
    ];
}
