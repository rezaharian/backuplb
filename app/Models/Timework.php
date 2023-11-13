<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timework extends Model
{
    use HasFactory;
    protected $fillable = [
        'tw_cod',
        'tw_shf',
        'tw_ins',
        'tw_out',
        'tw_not',
        'vins01',
        'vins02',
        'vout01',
        'vout02',
        'tw_std',
        'tw_res',
        'tw_o_t',
        'tw_vot',
        'tw_qty',
       
    ];
}
