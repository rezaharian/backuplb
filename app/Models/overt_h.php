<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class overt_h extends Model
{
    use HasFactory;
    protected $fillable = [
        'ot_int',
         'ot_cod',
         'ot_bag',
         'ot_dat',  
         'ot_day',  
         'diterima',  
         'keterangan',  
         'disetujui',  
         'approved',  
     ];
}
