<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pegawai extends Model
{

    // protected $fillable = [
    //     'nama_asli',
    //     'no_reg',
  
    // ];

    
    protected $fillable = [
        'no_payroll',  
        'name',  
        'nama_asli',
        'temp_lahir',  
        'tgl_lahir',  
        'sex',  
        'agama',  
        'alamat',  
        'kota',  
        'departemen',  
        'bagian',  
        'jabatan',  
        'golongan',  
        'unit',  
        'line',  
        'daerahasal',  
        'suku_bangs',  
        'gol_darah',  
        'statpeg',  
        'jns_peg',  
        'sts_nikah',  
        'jml_anak',  
        'pend',  
        'telepon',  
        'seksi',  
        'transport',
        'kode_gol',  
        'kode_jab',  
        'no_astek',  
        'ms_krgbln',  
        'tgl_masuk',  
        'perpanjang',  
        'berakhir',  
        'tgl_keluar',  
        'ayah',  
        'gkcod',
        'ibu',  
        'suami_istr',  
        'no_ktp',  
        'tglberlaku',  
        'npwp',  
        'no_kk',  
        'kd_fas',  
        'faskes',  
        'rek_bank',  
        'bpjs_tk',  
        'bpjs_kes0',  
        'bpjs_kes1',  
        'bpjs_kes2',  
        'bpjs_kes3',  
        'bpjs_kes4',  
        'bpjs_kes5',  
        'foto',  
        'email',  
        'foto',  

    ];

    public function absen_h() {
        return $this->hasMany(absen_h::class, 'no_payroll', 'no_payroll');
    }


 

    
}
