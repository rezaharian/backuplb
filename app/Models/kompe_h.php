<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kompe_h extends Model
{
    use HasFactory;
    protected $fillable = [
       'id',
        'kompe_cod',
        'bagian',
        'tipe',  
    ];
    public function kompe_ds()
    {
        return $this->hasMany(kompe_ds::class);
    }
}
