<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_materi',
         'materi',
         'bagian',
         'keterangan',  
         'file_materi'
     ];
}
