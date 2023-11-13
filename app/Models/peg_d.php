<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peg_d extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_payroll',
        'no_kontrak',
        'perpanjang',  
        'berakhir',  
    ];
}
