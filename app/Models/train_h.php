<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class train_h extends Model
{
    use HasFactory;
    protected $fillable = [
        'train_cod',
        'train_dat',
        'hari',  
        'jam',  
        'sdjam',  
        'tempat',  
        'pltran_cod',  
        'pltran_nam',  
        'train_tema',  
        'kompe_cod',  
        'kompetensi',  
        'pemateri',  
        'approve',  
        'tipe', 
        'file', 
        'file_absen', 
        'kode_materi', 
    ];
    public function train_d()
    {
        return $this->hasMany(train_d::class);
    }
}
