<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class train_d extends Model
{
    use  HasFactory, SoftDeletes;
    protected $fillable = [
         'train_cod',
         'train_dat',
         'no_payroll',  
         'nama_asli',  
         'nilai',  
         'nilai_pre',  
         'keterangan',  
         'approve',  
     ];
     protected $dates = ['deleted_at'];


     public function train_h()
     {
         return $this->belongsTo(train_h::class);
     }
}
