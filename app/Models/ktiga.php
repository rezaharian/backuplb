<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ktiga extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
         'no_urut',
         'pemohon',
         'tanggal',
         'bagian',
         'jenis_masalah',  
         'masalah',  
         'file_foto',  
         'klas_temuan',  
         'tgl_ttd',  
         'penerima',  
         'tgl_terima',  
         'analisa_sebab',  
         'analis',  
         'tgl_analis',  
         'perbaikan',  
         'pj_perbaikan',  
         'batas_perbaikan',  
         'r_verifikasi_perbaikan',  
         'atasan',  
         'tgl_atasan',  
         'pic',  
         'tgl_pic',  
         'pencegahan',  
         'pj_pencegahan',  
         'batas_pencegahan',  
         'hasil_verifikasi',  
         'r_verifikasi_cegah',  
         'so_pic',  
         'tgl_so',  
         'catatan_te',  
     
     ];
}
