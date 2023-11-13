<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class datacap extends Model
{
    protected $fillable = [
        'tanggal',
        'reset',
        'awal',
        'akhir',
        'kalkulasi',
        'jumlahpershift',
        'jumlahperhari',
        'awaljam',
        'awalmenit',
        'akhirjam',
        'akhirmenit',
        'runtimemenit',
        'etc',
        'jalur_id',
        'shift',
        'resetw',
        'runtimeshift',
        'runtimehari',
        'spk',
        'produk',
        'operator'

    ];}
