<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use App\Models\Materi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriController extends Controller
{
    public function list()
    {
        // $kode = Materi::max('kode_materi');
        // $kd_mt = $kode + 1;

        $bag = bagian::orderBy('bagian', 'asc')->get();
        $materi = Materi::orderBy('bagian', 'asc')->orderBy('kode_materi','asc')->get();

        return view('hr/dashboard/training/materi/list', compact('materi', 'bag'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'file_materi' => 'mimes:pdf,xls,xlsx,doc,docx,ppt,pptx|max:9048',
        ]);

        // Jika ada file yang diunggah, simpan file ke direktori /uploads/materi
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads/materi'), $filename);
        }

        // Membuat penomeran kode
        $bagian = $request->input('bagian');
        $kodeMateri = '';

        switch ($bagian) {
            case 'ACCOUNTING':
                // mengambil data terakhir dari tabel materi yang memiliki bagian accounting
                $lastMateri = Materi::where('bagian', 'ACCOUNTING')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'ACC' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'EDP':
                // mengambil data terakhir dari tabel materi yang memiliki bagian EDP
                $lastMateri = Materi::where('bagian', 'EDP')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'EDP' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'TEKNIK':
                // mengambil data terakhir dari tabel materi yang memiliki bagian TEKNIK
                $lastMateri = Materi::where('bagian', 'TEKNIK')
                    ->latest()
                    ->first();

                 // mendapatkan informasi tanggal dan waktu saat ini
                 $currentYearMonth = date('y'); // format: YYMM
                 $currentYearMonth1= date('ym'); // format: YYMM
 
                 // jika ada data terakhir
                 if ($lastMateri) {
                     // mendapatkan tahun dan bulan pada kode materi terakhir
                     $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'TKN' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'DIREKSI':
                // mengambil data terakhir dari tabel materi yang memiliki bagian EXPEDISI
                $lastMateri = Materi::where('bagian', 'DIREKSI')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'DRK' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'EXPEDISI':
                // mengambil data terakhir dari tabel materi yang memiliki bagian EXPEDISI
                $lastMateri = Materi::where('bagian', 'EXPEDISI')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'EXP' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'GA':
                // mengambil data terakhir dari tabel materi yang memiliki bagian GA
                $lastMateri = Materi::where('bagian', 'GA')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'G A' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'GUDANG':
                // mengambil data terakhir dari tabel materi yang memiliki bagian GUDANG
                $lastMateri = Materi::where('bagian', 'GUDANG')
                    ->latest()
                    ->first();

                  // mendapatkan informasi tanggal dan waktu saat ini
                  $currentYearMonth = date('y'); // format: YYMM
                  $currentYearMonth1= date('ym'); // format: YYMM
  
                  // jika ada data terakhir
                  if ($lastMateri) {
                      // mendapatkan tahun dan bulan pada kode materi terakhir
                      $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'GDG' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'HR OFFICER':
                // mengambil data terakhir dari tabel materi yang memiliki bagian HR OFFICER
                $lastMateri = Materi::where('bagian', 'HR OFFICER')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'HRO' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'KEUANGAN':
                // mengambil data terakhir dari tabel materi yang memiliki bagian KEUANGAN
                $lastMateri = Materi::where('bagian', 'KEUANGAN')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'KEU' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'MARKETING':
                // mengambil data terakhir dari tabel materi yang memiliki bagian MARKETING
                $lastMateri = Materi::where('bagian', 'MARKETING')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'MKT' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'PEMBELIAN':
                // mengambil data terakhir dari tabel materi yang memiliki bagian PEMBELIAN
                $lastMateri = Materi::where('bagian', 'PEMBELIAN')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'PMB' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'PREPARASI':
                // mengambil data terakhir dari tabel materi yang memiliki bagian PREPARASI
                $lastMateri = Materi::where('bagian', 'PREPARASI')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'PRP' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'PROD. LANGSUNG':
                // mengambil data terakhir dari tabel materi yang memiliki bagian PROD. LANGSUNG
                $lastMateri = Materi::where('bagian', 'PROD. LANGSUNG')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'PLS' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'PROD. TAK LANGSUNG':
                // mengambil data terakhir dari tabel materi yang memiliki bagian PROD. TAK LANGSUNG
                $lastMateri = Materi::where('bagian', 'PROD. TAK LANGSUNG')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'PTL' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'PROD. TAK LANGSUNG':
                // mengambil data terakhir dari tabel materi yang memiliki bagian PROD. TAK LANGSUNG
                $lastMateri = Materi::where('bagian', 'PROD. TAK LANGSUNG')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'PTL' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'PROD.LANGSUNG TINTA':
                // mengambil data terakhir dari tabel materi yang memiliki bagian PROD.LANGSUNG TINTA
                $lastMateri = Materi::where('bagian', 'PROD.LANGSUNG TINTA')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'PLT' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            case 'Q C':
                // mengambil data terakhir dari tabel materi yang memiliki bagian PROD.LANGSUNG Q C
                $lastMateri = Materi::where('bagian', 'Q C')
                    ->latest()
                    ->first();

                // mendapatkan informasi tanggal dan waktu saat ini
                $currentYearMonth = date('y'); // format: YYMM
                $currentYearMonth1= date('ym'); // format: YYMM

                // jika ada data terakhir
                if ($lastMateri) {
                    // mendapatkan tahun dan bulan pada kode materi terakhir
                    $lastYearMonth = substr($lastMateri->kode_materi, 3, 2);

                    // jika tahun dan bulan pada kode materi terakhir sama dengan tahun dan bulan saat ini
                    if ($lastYearMonth == $currentYearMonth) {
                        // mengambil nomor urut terakhir dan menambahkan 1
                        $lastKodeMateriNumber = substr($lastMateri->kode_materi, 7, 3);
                        $newKodeMateriNumber = intval($lastKodeMateriNumber) + 1;
                    } else {
                        // jika tahun dan bulan pada kode materi terakhir beda dengan tahun dan bulan saat ini
                        $newKodeMateriNumber = 1;
                    }
                } else {
                    // jika tidak ada data terakhir, maka membuat kode materi baru dengan nomor urut 001 dan menggunakan tahun dan bulan saat ini
                    $newKodeMateriNumber = 1;
                }

                $newKodeMateriNumberFormatted = sprintf('%03d', $newKodeMateriNumber);
                // membuat kode materi baru dengan menambahkan nomor urut ke kode bagian, tahun, dan bulan
                $kodeMateri = 'Q C' . $currentYearMonth1 . $newKodeMateriNumberFormatted;
                break;

            // tambahkan case untuk bagian lainnya jika perlu
            default:
                $kodeMateri = '000';
                break;
        }

        $materi = Materi::create([
            'kode_materi' => $kodeMateri,
            'materi' => $request->materi,
            'bagian' => $request->bagian,
            'keterangan' => $request->keterangan,
            'file_materi' => $filename ?? null, // Jika tidak ada file yang diunggah, simpan null
        ]);

        return redirect()
            ->route('hr.training.materi.list')
            ->with('success', 'New Materi has been added.');
    }
    public function view($id, Request $request)
    {
        $bag = bagian::orderBy('bagian', 'asc')->get();
        $data = Materi::findorfail($id);
        $url = url('uploads/materi/'.$data->file_materi);


        return view('hr/dashboard/training/materi/view', compact('data', 'bag','url'));
    }
    public function edit($id, Request $request)
    {
        $bag = bagian::orderBy('bagian', 'asc')->get();
        $data = Materi::findorfail($id);

        return view('hr/dashboard/training/materi/edit', compact('data', 'bag'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file_materi' => 'mimes:pdf,xls,xlsx,doc,docx,ppt,pptx|max:9048',
        ]);

        
        $materi = Materi::findOrFail($id);
        $materi->kode_materi = $request->kode_materi;
        $materi->materi = $request->materi;
        $materi->bagian = $request->bagian;
        $materi->keterangan = $request->keterangan;
        
        // Jika ada file yang diunggah
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads/materi/'), $filename);
        
            // Hapus file lama jika ada
            if ($materi->file_materi && file_exists(public_path('uploads/materi/' . $materi->file_materi))) {
                unlink(public_path('uploads/materi/' . $materi->file_materi));
            }
        
            $materi->file_materi = $filename;
        }
        
 
         
        $materi->save();
        
        
        return redirect()
            ->route('hr.training.materi.list')
            ->with('success', 'Subject has been updated.');
        
    }

    public function delete($id)
    {
        $materi = Materi::findOrFail($id);
        if ($materi->file_materi && file_exists(public_path('uploads/materi/' . $materi->file_materi))) {
            unlink(public_path('uploads/materi/' . $materi->file_materi));
        }
        $materi->delete();
        return redirect()
            ->route('hr.training.materi.list')
            ->with('success', 'Data Berhasil di Hapus');
        
    }

    //laporan
    public function laporan_list(Request $request)
    {
        $mat = $request->materi;
        $bag = $request->bagian;

        $bagian = bagian::orderBy('bagian', 'asc')->get();
        $materi = Materi::orderBy('materi', 'asc')->get();

        if ($mat) {
            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select('train_ds.no_payroll', DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), 'train_hs.pemateri', 'train_ds.no_payroll', 'train_ds.nilai', 'train_ds.nilai_pre', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->where('train_hs.train_tema', $mat)
                ->orderBy('bagian', 'ASC')
                ->orderBy('no_payroll', 'ASC')
                ->get();
        } else {
            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select('train_ds.no_payroll', DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), 'train_hs.pemateri', 'train_ds.no_payroll', 'train_ds.nilai', 'train_ds.nilai_pre', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->where('train_hs.train_tema', $mat)
                ->orderBy('bagian', 'ASC')
                ->orderBy('no_payroll', 'ASC')
                ->limit(0) //tidak memunculkan data
                ->get();
        }

        return view('/hr/dashboard/training/materi/laporan/list', compact('data', 'bagian', 'materi', 'mat', 'bag'));
    }

    public function laporan_list_print(Request $request)
    {
        $bagian = bagian::orderBy('bagian', 'asc')->get();
        $materi = Materi::orderBy('materi', 'asc')->get();
        $mtr = $request->mat;

        $data = DB::table('train_ds')
            ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
            ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
            ->select('train_ds.no_payroll', DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), 'train_hs.pemateri', 'train_ds.no_payroll', 'train_ds.nilai', 'train_ds.nilai_pre', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
            ->where('train_hs.approve', 'YES')
            ->where('train_ds.approve', 'Y')
            ->whereNull('deleted_at')
            ->where('pegawais.tgl_keluar', null)
            ->where('train_hs.train_tema', $request->mat)
            ->orderBy('bagian', 'ASC')
            ->orderBy('no_payroll', 'ASC')
            ->get();

        $pdf = Pdf::loadview('hr.dashboard.training.materi.laporan.list_print', compact('data', 'mtr'));
        return $pdf->setPaper('a4', 'potrait')->stream('Materi_Training.pdf');
    }

    public function find_bag(Request $request)
    {
        $dt['materi'] = materi::where('bagian', $request->bagian)->get(['id', 'materi']);
        return response()->json($dt);
    }
}
