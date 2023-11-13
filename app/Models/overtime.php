<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class overtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'ot_int',
         'ot_cod',
         'ot_cod_d',
         'ot_bag',
         'ot_dat',  
         'ot_day',  
         'ot_hrb',  
         'ot_hre',  
         'int_peg',  
         'no_payroll',  
         'nama_asli',  
         'catatan',  
         'spk_nomor',  
         'line',  
         'tugas',  
         'keterangan',  
         'o_u',  
         'takterdata',   
     ];
}
