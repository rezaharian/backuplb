<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kompe_d extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'kompe_cod',
        'kompe_id',
        'kompetensi',  
        'jenis',  
    ];

    public function kompe_hs()
    {
        return $this->belongsTo(kompe_hs::class);
    }
}
