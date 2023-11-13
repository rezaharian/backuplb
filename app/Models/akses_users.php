<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class akses_users extends Model
{
    use HasFactory;
    protected $fillable = [
        'iduser',
        'bagian',
        'int_akses',
    ];
}
