<?php

namespace App\Http\Controllers;

use App\Models\absen_d;
use App\Models\absen_h;
use App\Models\ct_besar;
use App\Models\onoff_tg;
use App\Models\pegawai;
use App\Models\Presensi;
use App\Models\Pt_gaji;
use App\Models\TglLibur;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportCutiController extends Controller
{
    public function index()
    {
        $bulan = [
            1 => 'JANUARI',
            2 => 'FEBRUARI',
            3 => 'MARET',
            4 => 'APRIL',
            5 => 'MEI',
            6 => 'JUNI',
            7 => 'JULI',
            8 => 'AGUSTUS',
            9 => 'SEPTEMBER',
            10 => 'OKTOBER',
            11 => 'NOVEMBER',
            12 => 'DESEMBER',
        ];

        // cari tahun
        $tahunSekarang = date('Y');
        $tahunAwal = $tahunSekarang - 5;
        $tahunAkhir = $tahunSekarang + 5;

        $tahun = range($tahunAwal, $tahunAkhir);

        return view('../hr/dashboard/reportcuti/index', compact('bulan', 'tahun', 'tahunAwal', 'tahunAkhir', 'tahunSekarang'));
    }

    public function list(Request $request)
    {
        // dd($request->toArray());
        $bulanAwal = $request->input('bln_awal');
        $bulanAkhir = $request->input('bln_akhir');
        $tahun = $request->input('thn');
        $noPayroll = $request->input('no_payroll');
        $reportType = $request->input('report_type');

        // dd($peg->toArray());
        // Mencari H
        $absen = absen_h::where('no_payroll', $noPayroll)->first();

        $bulanIndonesia = [
            1 => 'JANUARI',
            2 => 'FEBRUARI',
            3 => 'MARET',
            4 => 'APRIL',
            5 => 'MEI',
            6 => 'JUNI',
            7 => 'JULI',
            8 => 'AGUSTUS',
            9 => 'SEPTEMBER',
            10 => 'OKTOBER',
            11 => 'NOVEMBER',
            12 => 'DESEMBER',
        ];

        $bulanAwalNama = $bulanIndonesia[$bulanAwal];
        $bulanAkhirNama = $bulanIndonesia[$bulanAkhir];

        $rangeBulan = [];
        for ($i = $bulanAwal; $i <= $bulanAkhir; $i++) {
            $rangeBulan[] = $bulanIndonesia[$i];
        }

        if ($reportType == 'uraian_cuti') {
            if ($noPayroll) {
                $peg = pegawai::where('no_payroll', $noPayroll)->first();

                $absen_d_query = absen_d::where('int_peg', $absen->int_peg)
                    ->where('thn_absen', $tahun)
                    ->whereIn('bln_absen', $rangeBulan);

                $absen_counts = $absen_d_query
                    ->selectRaw(
                        '
                        SUM(CASE WHEN jns_absen = "SD" THEN 1 ELSE 0 END) as SD,
                        SUM(CASE WHEN jns_absen = "IPC" THEN 1 ELSE 0 END) as IPC,
                        SUM(CASE WHEN jns_absen = "IC" THEN 1 ELSE 0 END) as IC,
                        SUM(CASE WHEN jns_absen IN ("H1", "H2") THEN 1 ELSE 0 END) as H,
                        SUM(CASE WHEN jns_absen = "SK" THEN 1 ELSE 0 END) as SK,
                        SUM(CASE WHEN jns_absen = "I" THEN 1 ELSE 0 END) as I
                    ',
                    )
                    ->first();

                $SD = $absen_counts->SD;
                $IPC = $absen_counts->IPC;
                $IC = $absen_counts->IC;
                $H = $absen_counts->H;
                $SK = $absen_counts->SK;
                $I = $absen_counts->I;

                // dd($IC);
                // Mencari M ============================================================================================================

                $M = 0;
                $tanggalAwalPertama = '01-' . str_pad($bulanAwal, 2, '0', STR_PAD_LEFT) . '-' . $tahun;
                $tanggalAwalPertama = Carbon::createFromFormat('d-m-Y', $tanggalAwalPertama)->format('Y-m-d');
                $tanggalAkhirTerakhir = date('t-m-Y', strtotime('01-' . str_pad($bulanAkhir, 2, '0', STR_PAD_LEFT) . '-' . $tahun));
                $tanggalAkhirTerakhir = Carbon::createFromFormat('d-m-Y', $tanggalAkhirTerakhir)->format('Y-m-t');
                $tanggalHariIni = Carbon::now();
                if ($tanggalHariIni->gte($tanggalAkhirTerakhir)) {
                    $tanggalAkhirTerakhir = $tanggalAkhirTerakhir;
                } else {
                    $tanggalAkhirTerakhir = $tanggalHariIni;
                }
                $tgl_list = [];
                $currentDate = strtotime($tanggalAwalPertama);
                $endDate = strtotime($tanggalAkhirTerakhir);
                while ($currentDate <= $endDate) {
                    $currentDayOfWeek = date('N', $currentDate);
                    if ($currentDayOfWeek < 6) {
                        $tgl_list[] = date('Y-m-d', $currentDate);
                    }
                    $currentDate = strtotime('+1 day', $currentDate);
                }

                $noPayroll = $absen->no_payroll;
                $tglabs = absen_d::where('int_peg', $absen->int_peg)
                    ->whereBetween('tgl_absen', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                    ->pluck('tgl_absen')
                    ->toArray();
                $prese = DB::table('presensis')
                    ->where('no_reg', $noPayroll)
                    ->whereBetween('tanggal', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                    ->pluck('tanggal')
                    ->toArray();
                $tglon = onoff_tg::whereBetween('tgl_on', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                    ->pluck('tgl_on')
                    ->toArray();
                $tgloff = onoff_tg::whereBetween('tgl_off', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                    ->pluck('tgl_off')
                    ->toArray();
                $tglLibur = TglLibur::whereBetween('tgl_libur', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                    ->pluck('tgl_libur')
                    ->toArray();
                $onoff = array_merge($tglon, $tgloff);
                $jmlh = array_diff(array_diff(array_diff(array_diff($tgl_list, $prese), $tglabs), $tglLibur), $onoff);
                $M = count($jmlh);
                // SELESAI MENCARI M ====================================================================================================

                // LMBT(X)
                $lmbtx = Presensi::where('no_reg', $absen->no_payroll)
                    ->whereNotNull('norm_m')
                    ->whereNotNull('norm_k')
                    ->whereNotNull('masuk')
                    ->whereNotNull('keluar')
                    ->whereColumn('masuk', '>', 'norm_m')
                    ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                        $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                    })
                    ->where(function ($query) {
                        $query
                            ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                            ->orWhereExists(function ($subquery) {
                                $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                            })
                            ->whereNotExists(function ($subquery) {
                                $subquery
                                    ->from('tgl_liburs')
                                    ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                    ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                            });
                    })
                    ->count();

                // LMBT(M)
                $lmbt = Presensi::where('no_reg', $absen->no_payroll)
                    ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                    ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                    ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                    ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('masuk', '>', 'norm_m')
                    ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                        $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                    })
                    ->where(function ($query) {
                        $query
                            ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                            ->orWhereExists(function ($subquery) {
                                $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                            })
                            ->whereNotExists(function ($subquery) {
                                $subquery
                                    ->from('tgl_liburs')
                                    ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                    ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                            });
                    })
                    ->get();

                $totalKeterlambatan = 0.0; // Inisialisasi total keterlambatan

                foreach ($lmbt as $presensi) {
                    $masuk = strtotime($presensi->masuk); // Mengubah masuk menjadi timestamp
                    $norm_m = strtotime($presensi->norm_m); // Mengubah norm_m menjadi timestamp

                    $selisihDetik = $masuk - $norm_m; // Menghitung selisih dalam detik
                    $selisihJam = $selisihDetik / 3600; // Menghitung selisih dalam jam

                    // Jika selisih jam negatif (keterlambatan), tambahkan ke total keterlambatan
                    if ($selisihJam > 0) {
                        $totalKeterlambatan += $selisihJam;
                    }
                }

                // Anda juga dapat menyimpan total keterlambatan dalam variabel $lmbtx
                $lmbtjm = number_format($totalKeterlambatan, 2);

                // IPA x
                $ipax = Presensi::where('no_reg', $absen->no_payroll)
                    ->whereNotNull('norm_m')
                    ->whereNotNull('norm_k')
                    ->whereNotNull('masuk')
                    ->whereNotNull('keluar')
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('keluar', '<', 'norm_k')
                    ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                        $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                    })
                    ->where(function ($query) {
                        $query
                            ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                            ->orWhereExists(function ($subquery) {
                                $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                            })
                            ->whereNotExists(function ($subquery) {
                                $subquery
                                    ->from('tgl_liburs')
                                    ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                    ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                            });
                    })
                    ->count();

                // IPA Jam
                $ipaj = Presensi::where('no_reg', $absen->no_payroll)
                    ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                    ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                    ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                    ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('keluar', '<', 'norm_k')
                    ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                        $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                    })
                    ->where(function ($query) {
                        $query
                            ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                            ->orWhereExists(function ($subquery) {
                                $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                            })
                            ->whereNotExists(function ($subquery) {
                                $subquery
                                    ->from('tgl_liburs')
                                    ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                    ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                            });
                    })
                    ->get();

                $totalKeterlambatan = 0.0; // Inisialisasi total keterlambatan

                foreach ($ipaj as $presensi) {
                    $keluar = strtotime($presensi->keluar); // Mengubah masuk menjadi timestamp
                    $norm_k = strtotime($presensi->norm_k); // Mengubah norm_m menjadi timestamp

                    $selisihDetik = $norm_k - $keluar; // Menghitung selisih dalam detik
                    $selisihJam = $selisihDetik / 3600; // Menghitung selisih dalam jam

                    // Jika selisih jam negatif (keterlambatan), tambahkan ke total keterlambatan
                    if ($selisihJam > 0) {
                        $totalKeterlambatan += $selisihJam;
                    }
                }

                // Anda juga dapat menyimpan total keterlambatan dalam variabel $ipajam
                $ipajam = number_format($totalKeterlambatan, 2);

                // DL
                $dl = absen_d::where('int_peg', $absen->int_peg)
                    ->whereIn('jns_absen', ['DL', 'DL1', 'DL2', 'DL3'])
                    ->where('thn_jns', $tahun)
                    ->whereIn('bln_absen', $rangeBulan)
                    ->count();

                // ICB

                $icb = absen_d::where('int_peg', $absen->int_peg)
                    ->where('jns_absen', 'ICB')
                    ->whereBetween('tgl_absen', [$absen->waktu_masuk, now()])
                    ->count();

                // dd($rangeBulan);

                //MENGHITUNG CUTIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
                $inputBulan = $bulanAkhir; // Ganti dengan bulan yang diinginkan, misalnya 10 untuk Oktober
                $tahun = date('Y'); // Tahun saat ini

                if (array_key_exists($inputBulan, $bulanIndonesia)) {
                    $tanggalAkhirBulan = date('Y-m-t', strtotime("$tahun-{$inputBulan}-01"));
                    $blncoba = new DateTime($tanggalAkhirBulan); // Konversi ke objek DateTime
                } else {
                    // echo "Bulan tidak valid.";
                }

                $masuk = new DateTime($peg->tgl_masuk); // Konversi ke objek DateTime
                // dd($masuk);
                $interval = $masuk->diff($blncoba);
                $selisihBulan = $interval->y * 12 + $interval->m;

                $selisihTahun = $blncoba->diff($masuk)->y;

                if ($selisihBulan < 12) {
                    $cuti = 0;
                } elseif ($blncoba != 'januari' && $selisihBulan >= 12) {
                    $cuti = min(12, $selisihBulan - 11); // Menghitung cuti hingga akhir tahun
                } else {
                    $cuti = 12; // Awal tahun langsung dapat cuti 12
                }

                // dd($cuti);

                $potonggaji = Pt_gaji::where('no_payroll', $noPayroll)
                    ->whereBetween('no_bln', [$bulanAwal, $bulanAkhir])
                    ->where('thn', $tahun)
                    ->count();
                //SCTB

                // dd($IC);
                $SCTB = $cuti - $IC - $IPC - $M + $potonggaji;
                // dd($potonggaji);

                $tahunSebelumnya = $tahun - 1;
                // SCB BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB
                $mskrj = Carbon::parse($peg->tgl_masuk); // Mengonversi tanggal masuk ke objek Carbon
                $sdbulan = Carbon::createFromDate($tahun, $bulanAkhir, 1); // Objek Carbon untuk tanggal yang diinginkan

                // Menyesuaikan tanggal referensi ke tanggal masuk pegawai
                $sdbulan->day($mskrj->day);

                // Menghitung selisih tahun antara tanggal masuk dan tanggal referensi
                $yearsOfWork = $mskrj->diffInYears($sdbulan);

                // dd($yearsOfWork);
                if ($yearsOfWork == 10) {
                    $SCB = 20;
                } elseif ($yearsOfWork > 10) {
                    // Nilai $SCB jika belum mencapai 10 tahun
                    $SCB = ct_besar::where('no_payroll', $noPayroll)
                        ->where('tahun', $tahunSebelumnya)
                        ->value('sisa_cb');
                } else {
                    $SCB = 0;
                }

                $tahunIni = Carbon::now()->year;
                $icbthini = absen_d::where('int_peg', $absen->int_peg)
                    ->where('jns_absen', 'ICB')
                    ->whereYear('thn_absen', $tahunIni)
                    ->count();

                $SCB = $SCB - $icbthini;

                // Menggunakan $sdbulan dan $SCB sesuai kebutuhan

                // dd($SCB);
                return view('../hr/dashboard/reportcuti/list', compact('bulanAwal', 'bulanAkhir', 'tahun', 'peg', 'H', 'SK', 'SD', 'I', 'IPC', 'IC', 'M', 'lmbtx', 'lmbtjm', 'ipax', 'ipajam', 'dl', 'icb', 'SCTB', 'SCB'));
                //  batassssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss----------------------------------------------------------------------------------------------------------------------------------------------------------
            } else {
                ini_set('max_execution_time', 1000); // Set the maximum execution time to 200 seconds (3 minutes)

                $peg = pegawai::with([
                    'absen_h.absen_d' => function ($query) use ($tahun, $rangeBulan) {
                        $query
                            ->whereIn('jns_absen', ['SK', 'SD', 'H1', 'H2', 'I', 'IPC', 'IC'])
                            ->where('thn_jns', $tahun)
                            ->whereIn('bln_absen', $rangeBulan);
                    },
                ])
                    ->whereNotIn('bagian', ['security', 'kebersihan', 'direksi', 'KEBERSIHAN', 'SATPAM'])
                    ->whereNotIn('jns_peg', ['security', 'kebersihan', 'direksi', 'KEBERSIHAN', 'SATPAM'])
                    ->whereNull('tgl_keluar')
                    ->orderBy('no_payroll', 'asc')
                    ->paginate(300);

                $results = [];

                foreach ($peg as $pegawai) {
                    $absenData = []; // Inisialisasi array untuk data absensi pegawai

                    foreach ($pegawai->absen_h as $absen) {
                        $IC = $absen->absen_d->where('jns_absen', 'IC')->count();
                        $SK = $absen->absen_d->where('jns_absen', 'SK')->count();
                        $SD = $absen->absen_d->where('jns_absen', 'SD')->count();
                        $H = $absen->absen_d->whereIn('jns_absen', ['H1', 'H2'])->count();
                        $I = $absen->absen_d->where('jns_absen', 'I')->count();
                        $IPC = $absen->absen_d->where('jns_absen', 'IPC')->count();

                        // Tambahkan data absen ke array absenData
                        $absenData[] = [
                            'IC' => $IC,
                            'SK' => $SK,
                            'SD' => $SD,
                            'H' => $H,
                            'I' => $I,
                            'IPC' => $IPC,
                        ];
                    }

                    // Check if $absenData is not empty before accessing its last element
                    if (!empty($absenData)) {
                        $latestAbsen = end($absenData);
                    } else {
                        // Handle the case when $absenData is empty, e.g., set default values
                        $latestAbsen = [
                            'IC' => 0,
                            'SK' => 0,
                            'SD' => 0,
                            'H' => 0,
                            'I' => 0,
                            'IPC' => 0,
                        ];
                    }

                    // Mencari M ============================================================================================================

                    $M = 0;
                    $tanggalAwalPertama = '01-' . str_pad($bulanAwal, 2, '0', STR_PAD_LEFT) . '-' . $tahun;
                    $tanggalAwalPertama = Carbon::createFromFormat('d-m-Y', $tanggalAwalPertama)->format('Y-m-d');
                    $tanggalAkhirTerakhir = date('t-m-Y', strtotime('01-' . str_pad($bulanAkhir, 2, '0', STR_PAD_LEFT) . '-' . $tahun));
                    $tanggalAkhirTerakhir = Carbon::createFromFormat('d-m-Y', $tanggalAkhirTerakhir)->format('Y-m-t');
                    $tanggalHariIni = Carbon::now();
                    if ($tanggalHariIni->gte($tanggalAkhirTerakhir)) {
                        $tanggalAkhirTerakhir = $tanggalAkhirTerakhir;
                    } else {
                        $tanggalAkhirTerakhir = $tanggalHariIni;
                    }
                    $tgl_list = [];
                    $currentDate = strtotime($tanggalAwalPertama);
                    $endDate = strtotime($tanggalAkhirTerakhir);
                    while ($currentDate <= $endDate) {
                        $currentDayOfWeek = date('N', $currentDate);
                        if ($currentDayOfWeek < 6) {
                            $tgl_list[] = date('Y-m-d', $currentDate);
                        }
                        $currentDate = strtotime('+1 day', $currentDate);
                    }
                    $noPayroll = $absen->no_payroll;
                    $tglabs = absen_d::where('int_peg', $absen->int_peg)
                        ->whereBetween('tgl_absen', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_absen')
                        ->toArray();
                    $prese = DB::table('presensis')
                        ->where('no_reg', $noPayroll)
                        ->whereBetween('tanggal', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tanggal')
                        ->toArray();
                    $tglon = onoff_tg::whereBetween('tgl_on', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_on')
                        ->toArray();
                    $tgloff = onoff_tg::whereBetween('tgl_off', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_off')
                        ->toArray();
                    $tglLibur = TglLibur::whereBetween('tgl_libur', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_libur')
                        ->toArray();
                    $onoff = array_merge($tglon, $tgloff);
                    $jmlh = array_diff(array_diff(array_diff(array_diff($tgl_list, $prese), $tglabs), $tglLibur), $onoff);
                    $M = count($jmlh);
                    // SELESAI MENCARI M ====================================================================================================

                    // LMBT(X)
                    $lmbtx = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        // ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        // ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        // ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        // ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->count();
                    // lmbt jam
                    $lmbt = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->get();

                    $totalKeterlambatan = 0.0; // Inisialisasi total keterlambatan

                    foreach ($lmbt as $presensi) {
                        $masuk = strtotime($presensi->masuk); // Mengubah masuk menjadi timestamp
                        $norm_m = strtotime($presensi->norm_m); // Mengubah norm_m menjadi timestamp

                        $selisihDetik = $masuk - $norm_m; // Menghitung selisih dalam detik
                        $selisihJam = $selisihDetik / 3600; // Menghitung selisih dalam jam

                        // Jika selisih jam negatif (keterlambatan), tambahkan ke total keterlambatan
                        if ($selisihJam > 0) {
                            $totalKeterlambatan += $selisihJam;
                        }
                    }

                    // Anda juga dapat menyimpan total keterlambatan dalam variabel $lmbtx
                    $lmbtjm = number_format($totalKeterlambatan, 2);

                    // IPA x

                    $ipax = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->count();

                    // IPA Jam

                    $ipaj = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->get();

                    $totalKeterlambatan = 0.0; // Inisialisasi total keterlambatan

                    foreach ($ipaj as $presensi) {
                        $keluar = strtotime($presensi->keluar); // Mengubah masuk menjadi timestamp
                        $norm_k = strtotime($presensi->norm_k); // Mengubah norm_m menjadi timestamp

                        $selisihDetik = $norm_k - $keluar; // Menghitung selisih dalam detik
                        $selisihJam = $selisihDetik / 3600; // Menghitung selisih dalam jam

                        // Jika selisih jam negatif (keterlambatan), tambahkan ke total keterlambatan
                        if ($selisihJam > 0) {
                            $totalKeterlambatan += $selisihJam;
                        }
                    }

                    // Anda juga dapat menyimpan total keterlambatan dalam variabel $lmbtx
                    $ipajam = number_format($totalKeterlambatan, 2);

                    // DL
                    $dl = absen_d::where('int_peg', $absen->int_peg)
                        ->where(function ($query) {
                            $query
                                ->where('jns_absen', 'DL')
                                ->orWhere('jns_absen', 'DL1')
                                ->orWhere('jns_absen', 'DL2')
                                ->orWhere('jns_absen', 'DL3');
                        })
                        ->where('thn_jns', $tahun)
                        ->whereIn('bln_absen', $rangeBulan)
                        ->count();

                    // ICB

                    $icb = absen_d::where('int_peg', $absen->int_peg)
                        ->where('jns_absen', 'ICB')
                        ->whereBetween('tgl_absen', [$absen->waktu_masuk, now()])
                        ->count();

                    //    dd($dl);

                    //MENGHITUNG CUTIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
                    $inputBulan = $bulanAkhir; // Ganti dengan bulan yang diinginkan, misalnya 10 untuk Oktober
                    $tahun = date('Y'); // Tahun saat ini

                    if (array_key_exists($inputBulan, $bulanIndonesia)) {
                        $tanggalAkhirBulan = date('Y-m-t', strtotime("$tahun-{$inputBulan}-01"));
                        $blncoba = new DateTime($tanggalAkhirBulan); // Konversi ke objek DateTime
                    } else {
                        // echo "Bulan tidak valid.";
                    }

                    $masuk = new DateTime($absen->tgl_masuk); // Konversi ke objek DateTime
                    // dd($masuk);
                    $interval = $masuk->diff($blncoba);
                    $selisihBulan = $interval->y * 12 + $interval->m;

                    $selisihTahun = $blncoba->diff($masuk)->y;

                    if ($selisihBulan < 12) {
                        $cuti = 0;
                    } elseif ($blncoba != 'januari' && $selisihBulan >= 12) {
                        $cuti = min(12, $selisihBulan - 11); // Menghitung cuti hingga akhir tahun
                    } else {
                        $cuti = 12; // Awal tahun langsung dapat cuti 12
                    }

                    // dd($cuti);

                    $potonggaji = Pt_gaji::where('no_payroll', $noPayroll)
                        ->whereBetween('no_bln', [$bulanAwal, $bulanAkhir])
                        ->where('thn', $tahun)
                        ->count();
                    //SCTB

                    // dd($IC);
                    $SCTB = $cuti - $IC - $IPC - $M + $potonggaji;
                    // dd($potonggaji);

                    $tahunSebelumnya = $tahun - 1;
                    // SCB BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB
                    // SCB BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB
                    $mskrj = Carbon::parse($peg->tgl_masuk); // Mengonversi tanggal masuk ke objek Carbon
                    $sdbulan = Carbon::createFromDate($tahun, $bulanAkhir, 1); // Objek Carbon untuk tanggal yang diinginkan

                    // Menyesuaikan tanggal referensi ke tanggal masuk pegawai
                    $sdbulan->day($mskrj->day);

                    // Menghitung selisih tahun antara tanggal masuk dan tanggal referensi
                    $yearsOfWork = $mskrj->diffInYears($sdbulan);

                    // dd($yearsOfWork);
                    if ($yearsOfWork == 10) {
                        $SCB = 20;
                    } elseif ($yearsOfWork > 10) {
                        // Nilai $SCB jika belum mencapai 10 tahun
                        $SCB = ct_besar::where('no_payroll', $noPayroll)
                            ->where('tahun', $tahunSebelumnya)
                            ->value('sisa_cb');
                    } else {
                        $SCB = 0;
                    }

                    $tahunIni = Carbon::now()->year;
                    $icbthini = absen_d::where('int_peg', $absen->int_peg)
                        ->where('jns_absen', 'ICB')
                        ->whereYear('thn_absen', $tahunIni)
                        ->count();

                    $SCB = $SCB - $icbthini;

                    // Tambahkan hasil akhir ke array $results
                    $results[] = [
                        'pegawai' => $pegawai,
                        'IC' => $latestAbsen['IC'],
                        'SK' => $latestAbsen['SK'],
                        'SD' => $latestAbsen['SD'],
                        'H' => $latestAbsen['H'],
                        'I' => $latestAbsen['I'],
                        'IPC' => $latestAbsen['IPC'],
                        'M' => $M,
                        'lmbtx' => $lmbtx,
                        'lmbtjm' => $lmbtjm,
                        'ipax' => $ipax,
                        'ipajam' => $ipajam,
                        'dl' => $dl,
                        'icb' => $icb,
                        'SCTB' => $SCTB,
                        'SCB' => $SCB,
                    ];
                }

                // Pass the results array to the view
                return View('../hr/dashboard/reportcuti/listsemua', [
                    'peg' => $peg, // Pass the paginated result
                    'results' => $results,
                    'bulanAwal' => $bulanAwal,
                    'bulanAkhir' => $bulanAkhir,
                    'tahun' => $tahun,
                ]);
            }
        } else {
            if ($noPayroll) {
            } else {
                ini_set('max_execution_time', 1000); // Set the maximum execution time to 400 seconds (6 minutes)

                $peg = pegawai::with([
                    'absen_h.absen_d' => function ($query) use ($tahun, $rangeBulan) {
                        $query
                            ->whereIn('jns_absen', ['SK', 'SD', 'H1', 'H2', 'I', 'IPC', 'IC'])
                            ->where('thn_jns', $tahun)
                            ->whereIn('bln_absen', $rangeBulan);
                    },
                ])
                    ->whereNotIn('bagian', ['security', 'kebersihan', 'direksi', 'KEBERSIHAN', 'SATPAM'])
                    ->whereNotIn('jns_peg', ['security', 'kebersihan', 'direksi', 'KEBERSIHAN', 'SATPAM'])
                    ->whereNull('tgl_keluar')
                    ->orderBy('no_payroll', 'asc')
                    ->paginate(300);

                $results = [];

                foreach ($peg as $pegawai) {
                    $absenData = [];

                    foreach ($pegawai->absen_h as $absen) {
                        $IC = $absen->absen_d->where('jns_absen', 'IC')->count();
                        $SK = $absen->absen_d->where('jns_absen', 'SK')->count();
                        $SD = $absen->absen_d->where('jns_absen', 'SD')->count();
                        $H = $absen->absen_d->whereIn('jns_absen', ['H1', 'H2'])->count();
                        $I = $absen->absen_d->where('jns_absen', 'I')->count();
                        $IPC = $absen->absen_d->where('jns_absen', 'IPC')->count();

                        // Tambahkan data absen ke array absenData
                        $absenData[] = [
                            'IC' => $IC,
                            'SK' => $SK,
                            'SD' => $SD,
                            'H' => $H,
                            'I' => $I,
                            'IPC' => $IPC,
                        ];
                    }

                    // Check if $absenData is not empty before accessing its last element
                    if (!empty($absenData)) {
                        $latestAbsen = end($absenData);
                    } else {
                        // Handle the case when $absenData is empty, e.g., set default values
                        $latestAbsen = [
                            'IC' => 0,
                            'SK' => 0,
                            'SD' => 0,
                            'H' => 0,
                            'I' => 0,
                            'IPC' => 0,
                        ];
                    }

                    // Mencari M ============================================================================================================

                    $M = 0;
                    $tanggalAwalPertama = '01-' . str_pad($bulanAwal, 2, '0', STR_PAD_LEFT) . '-' . $tahun;
                    $tanggalAwalPertama = Carbon::createFromFormat('d-m-Y', $tanggalAwalPertama)->format('Y-m-d');
                    $tanggalAkhirTerakhir = date('t-m-Y', strtotime('01-' . str_pad($bulanAkhir, 2, '0', STR_PAD_LEFT) . '-' . $tahun));
                    $tanggalAkhirTerakhir = Carbon::createFromFormat('d-m-Y', $tanggalAkhirTerakhir)->format('Y-m-t');
                    $tanggalHariIni = Carbon::now();
                    if ($tanggalHariIni->gte($tanggalAkhirTerakhir)) {
                        $tanggalAkhirTerakhir = $tanggalAkhirTerakhir;
                    } else {
                        $tanggalAkhirTerakhir = $tanggalHariIni;
                    }
                    $tgl_list = [];
                    $currentDate = strtotime($tanggalAwalPertama);
                    $endDate = strtotime($tanggalAkhirTerakhir);
                    while ($currentDate <= $endDate) {
                        $currentDayOfWeek = date('N', $currentDate);
                        if ($currentDayOfWeek < 6) {
                            $tgl_list[] = date('Y-m-d', $currentDate);
                        }
                        $currentDate = strtotime('+1 day', $currentDate);
                    }
                    $noPayroll = $absen->no_payroll;
                    $tglabs = absen_d::where('int_peg', $absen->int_peg)
                        ->whereBetween('tgl_absen', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_absen')
                        ->toArray();
                    $prese = DB::table('presensis')
                        ->where('no_reg', $noPayroll)
                        ->whereBetween('tanggal', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tanggal')
                        ->toArray();
                    $tglon = onoff_tg::whereBetween('tgl_on', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_on')
                        ->toArray();
                    $tgloff = onoff_tg::whereBetween('tgl_off', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_off')
                        ->toArray();
                    $tglLibur = TglLibur::whereBetween('tgl_libur', [$tanggalAwalPertama, $tanggalAkhirTerakhir])
                        ->pluck('tgl_libur')
                        ->toArray();
                    $onoff = array_merge($tglon, $tgloff);
                    $jmlh = array_diff(array_diff(array_diff(array_diff($tgl_list, $prese), $tglabs), $tglLibur), $onoff);
                    $M = count($jmlh);
                    // SELESAI MENCARI M ====================================================================================================

                    // LMBT(X)
                    $lmbtx = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        // ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        // ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        // ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        // ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->count();
                    // lmbt jam
                    $lmbt = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->get();

                    $totalKeterlambatan = 0.0; // Inisialisasi total keterlambatan

                    foreach ($lmbt as $presensi) {
                        $masuk = strtotime($presensi->masuk); // Mengubah masuk menjadi timestamp
                        $norm_m = strtotime($presensi->norm_m); // Mengubah norm_m menjadi timestamp

                        $selisihDetik = $masuk - $norm_m; // Menghitung selisih dalam detik
                        $selisihJam = $selisihDetik / 3600; // Menghitung selisih dalam jam

                        // Jika selisih jam negatif (keterlambatan), tambahkan ke total keterlambatan
                        if ($selisihJam > 0) {
                            $totalKeterlambatan += $selisihJam;
                        }
                    }

                    // Anda juga dapat menyimpan total keterlambatan dalam variabel $lmbtx
                    $lmbtjm = number_format($totalKeterlambatan, 2);

                    // IPA x

                    $ipax = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->count();

                    // IPA Jam

                    $ipaj = Presensi::where('no_reg', $absen->no_payroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($bulanAwal, $bulanAkhir, $tahun) {
                            $query->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])->whereYear('tanggal', $tahun);
                        })
                        ->where(function ($query) {
                            $query
                                ->whereRaw('DAYOFWEEK(tanggal) NOT IN (1, 7)') // Tanggal bukan hari Sabtu (7) atau Minggu (1)
                                ->orWhereExists(function ($subquery) {
                                    $subquery->from('onoff_tgs')->whereColumn('onoff_tgs.tgl_on', '=', 'presensis.tanggal');
                                })
                                ->whereNotExists(function ($subquery) {
                                    $subquery
                                        ->from('tgl_liburs')
                                        ->whereColumn('tgl_liburs.tgl_libur', '=', 'presensis.tanggal')
                                        ->whereNotNull('tgl_liburs.keterangan'); // Tanggal yang memiliki keterangan tidak dihitung
                                });
                        })
                        ->get();

                    $totalKeterlambatan = 0.0; // Inisialisasi total keterlambatan

                    foreach ($ipaj as $presensi) {
                        $keluar = strtotime($presensi->keluar); // Mengubah masuk menjadi timestamp
                        $norm_k = strtotime($presensi->norm_k); // Mengubah norm_m menjadi timestamp

                        $selisihDetik = $norm_k - $keluar; // Menghitung selisih dalam detik
                        $selisihJam = $selisihDetik / 3600; // Menghitung selisih dalam jam

                        // Jika selisih jam negatif (keterlambatan), tambahkan ke total keterlambatan
                        if ($selisihJam > 0) {
                            $totalKeterlambatan += $selisihJam;
                        }
                    }

                    // Anda juga dapat menyimpan total keterlambatan dalam variabel $lmbtx
                    $ipajam = number_format($totalKeterlambatan, 2);

                    // DL
                    $dl = absen_d::where('int_peg', $absen->int_peg)
                        ->where(function ($query) {
                            $query
                                ->where('jns_absen', 'DL')
                                ->orWhere('jns_absen', 'DL1')
                                ->orWhere('jns_absen', 'DL2')
                                ->orWhere('jns_absen', 'DL3');
                        })
                        ->where('thn_jns', $tahun)
                        ->whereIn('bln_absen', $rangeBulan)
                        ->count();

                    // ICB

                    $icb = absen_d::where('int_peg', $absen->int_peg)
                        ->where('jns_absen', 'ICB')
                        ->whereBetween('tgl_absen', [$absen->waktu_masuk, now()])
                        ->count();

                    //    dd($dl);

                    //MENGHITUNG CUTIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
                    $inputBulan = $bulanAkhir; // Ganti dengan bulan yang diinginkan, misalnya 10 untuk Oktober
                    $tahun = date('Y'); // Tahun saat ini

                    if (array_key_exists($inputBulan, $bulanIndonesia)) {
                        $tanggalAkhirBulan = date('Y-m-t', strtotime("$tahun-{$inputBulan}-01"));
                        $blncoba = new DateTime($tanggalAkhirBulan); // Konversi ke objek DateTime
                    } else {
                        // echo "Bulan tidak valid.";
                    }

                    $masuk = new DateTime($absen->tgl_masuk); // Konversi ke objek DateTime
                    // dd($masuk);
                    $interval = $masuk->diff($blncoba);
                    $selisihBulan = $interval->y * 12 + $interval->m;

                    $selisihTahun = $blncoba->diff($masuk)->y;

                    if ($selisihBulan < 12) {
                        $cuti = 0;
                    } elseif ($blncoba != 'januari' && $selisihBulan >= 12) {
                        $cuti = min(12, $selisihBulan - 11); // Menghitung cuti hingga akhir tahun
                    } else {
                        $cuti = 12; // Awal tahun langsung dapat cuti 12
                    }

                    // dd($cuti);

                    $potonggaji = Pt_gaji::where('no_payroll', $noPayroll)
                        ->whereBetween('no_bln', [$bulanAwal, $bulanAkhir])
                        ->where('thn', $tahun)
                        ->count();
                    //SCTB

                    // dd($IC);
                    $SCTB = $cuti - $IC - $IPC - $M + $potonggaji;
                    // dd($potonggaji);

                    $tahunSebelumnya = $tahun - 1;
                    // SCB BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB
                    // SCB BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB
                    $mskrj = Carbon::parse($peg->tgl_masuk); // Mengonversi tanggal masuk ke objek Carbon
                    $sdbulan = Carbon::createFromDate($tahun, $bulanAkhir, 1); // Objek Carbon untuk tanggal yang diinginkan

                    // Menyesuaikan tanggal referensi ke tanggal masuk pegawai
                    $sdbulan->day($mskrj->day);

                    // Menghitung selisih tahun antara tanggal masuk dan tanggal referensi
                    $yearsOfWork = $mskrj->diffInYears($sdbulan);

                    // dd($yearsOfWork);
                    if ($yearsOfWork == 10) {
                        $SCB = 20;
                    } elseif ($yearsOfWork > 10) {
                        // Nilai $SCB jika belum mencapai 10 tahun
                        $SCB = ct_besar::where('no_payroll', $noPayroll)
                            ->where('tahun', $tahunSebelumnya)
                            ->value('sisa_cb');
                    } else {
                        $SCB = 0;
                    }

                    $tahunIni = Carbon::now()->year;
                    $icbthini = absen_d::where('int_peg', $absen->int_peg)
                        ->where('jns_absen', 'ICB')
                        ->whereYear('thn_absen', $tahunIni)
                        ->count();

                    $SCB = $SCB - $icbthini;

                    // Tambahkan hasil akhir ke array $results
                    $results[] = [
                        'pegawai' => $pegawai,
                        'IC' => $latestAbsen['IC'],
                        'SK' => $latestAbsen['SK'],
                        'SD' => $latestAbsen['SD'],
                        'H' => $latestAbsen['H'],
                        'I' => $latestAbsen['I'],
                        'IPC' => $latestAbsen['IPC'],
                        'M' => $M,
                        'lmbtx' => $lmbtx,
                        'lmbtjm' => $lmbtjm,
                        'ipax' => $ipax,
                        'ipajam' => $ipajam,
                        'dl' => $dl,
                        'icb' => $icb,
                        'SCTB' => $SCTB,
                        'SCB' => $SCB,
                    ];
                }

                // Membuat objek Spreadsheet
                $spreadsheet = new Spreadsheet();

                // Membuat objek worksheet
                $worksheet = $spreadsheet->getActiveSheet();

                // ...

                // Menambahkan header kolom
                $worksheet->setCellValue('A1', 'Nama Pegawai');
                $worksheet->setCellValue('B1', 'No Payroll'); // Tambahkan kolom No Payroll
                $worksheet->setCellValue('C1', 'Tgl Masuk'); // Tambahkan kolom Tanggal Masuk
                $worksheet->setCellValue('D1', 'IC');
                $worksheet->setCellValue('E1', 'SK');
                $worksheet->setCellValue('F1', 'SD');
                $worksheet->setCellValue('G1', 'H');
                $worksheet->setCellValue('H1', 'I');
                $worksheet->setCellValue('I1', 'IPC');
                $worksheet->setCellValue('J1', 'M');
                $worksheet->setCellValue('K1', 'lmbtx');
                $worksheet->setCellValue('L1', 'lmbtjm');
                $worksheet->setCellValue('M1', 'ipax');
                $worksheet->setCellValue('N1', 'ipajam');
                $worksheet->setCellValue('O1', 'dl');
                $worksheet->setCellValue('P1', 'CB');
                $worksheet->setCellValue('Q1', 'SCTB');
                $worksheet->setCellValue('R1', 'SCB');

                $row = 2; // Mulai dari baris ke-2

                foreach ($results as $result) {
                    $pegawai = $result['pegawai'];

                    // Mengisi data pegawai
                    $worksheet->setCellValue('A' . $row, $pegawai->nama_asli);
                    $worksheet->setCellValue('B' . $row, $pegawai->no_payroll); // Isi No Payroll
                    $worksheet->setCellValue('C' . $row, $pegawai->tgl_masuk); // Isi Tanggal Masuk
                    $worksheet->setCellValue('D' . $row, $result['IC']);
                    $worksheet->setCellValue('E' . $row, $result['SK']);
                    $worksheet->setCellValue('F' . $row, $result['SD']);
                    $worksheet->setCellValue('G' . $row, $result['H']);
                    $worksheet->setCellValue('H' . $row, $result['I']);
                    $worksheet->setCellValue('I' . $row, $result['IPC']);
                    $worksheet->setCellValue('J' . $row, $result['M']);
                    $worksheet->setCellValue('K' . $row, $result['lmbtx']);
                    $worksheet->setCellValue('L' . $row, $result['lmbtjm']);
                    $worksheet->setCellValue('M' . $row, $result['ipax']);
                    $worksheet->setCellValue('N' . $row, $result['ipajam']);
                    $worksheet->setCellValue('O' . $row, $result['dl']);
                    $worksheet->setCellValue('P' . $row, $result['icb']);
                    $worksheet->setCellValue('Q' . $row, $result['SCTB']);
                    $worksheet->setCellValue('R' . $row, $result['SCB']);

                    $row++;
                }

                // ...

                $tanggalSekarang = Carbon::now()->format('d F Y H:i:s');
                $namaFile = "data_cuti_{$bulanAwal}sampai{$bulanAkhir}_{$tanggalSekarang}.xls";
                // Set header agar browser mengenali file Excel
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $namaFile . '"');
                header('Cache-Control: max-age=0');

                // Outputkan file Excel ke output buffer
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit();
            }
        }
    }
}
