<?php

namespace App\Http\Controllers;

use App\Models\absen_d;
use App\Models\absen_h;
use App\Models\onoff_tg;
use App\Models\overtime;
use App\Models\pegawai;
use App\Models\Presensi;
use App\Models\Tdkabsen;
use App\Models\TglLibur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Border as StyleBorder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border as PhpSpreadsheetStyleBorder;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class hrReportKerajinanController extends Controller
{
    public function index()
    {
        return view('../hr/dashboard/reportkerajinan/index');
    }

    public function list(Request $request)
    {
        set_time_limit(300);

        $tglawal = $request->tgl_awal;
        $tglakhir = $request->tgl_akhir;
        $reportType = $request->report_type;

        $noPayroll = $request->no_payroll;
        $absen = absen_h::where('no_payroll', $noPayroll)->pluck('int_absen')->toArray();
        $absendua = absen_h::where('no_payroll', $noPayroll)->first();

        if ($reportType == 'uraian') {
            if ($noPayroll) {
                # code...

                $peg = pegawai::where('no_payroll', $noPayroll)->first();

                $absen_d_query = absen_d::whereIn('int_absen', $absen)->whereBetween('tgl_absen', [$tglawal, $tglakhir]);

                $absen_counts = $absen_d_query
                    ->selectRaw(
                        '
                    SUM(CASE WHEN jns_absen = "SD" THEN 1 ELSE 0 END) as SD,
                    SUM(CASE WHEN jns_absen = "ITU" THEN 1 ELSE 0 END) as ITU,
                    SUM(CASE WHEN jns_absen = "IPC" THEN 1 ELSE 0 END) as IPC,
                    SUM(CASE WHEN jns_absen = "IC" THEN 1 ELSE 0 END) as IC,
                    SUM(CASE WHEN jns_absen IN ("H1", "H2") THEN 1 ELSE 0 END) as H,
                    SUM(CASE WHEN jns_absen = "SK" THEN 1 ELSE 0 END) as SK,
                    SUM(CASE WHEN jns_absen = "I" THEN 1 ELSE 0 END) as I
                    ',
                    )
                    ->first();

                $SD = $absen_counts->SD;
                $ITU = $absen_counts->ITU;
                $IPC = $absen_counts->IPC;
                $IC = $absen_counts->IC;
                $H = $absen_counts->H;
                $SK = $absen_counts->SK;
                $I = $absen_counts->I;

                // Mencari M ============================================================================================================

                $M = 0;
                $tanggalAwalPertama = $tglawal;
                $tanggalAkhirTerakhir = $tglakhir;
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

                $tglabs = absen_d::whereIn('int_absen', $absen)
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
                $lmbtx = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m')
                    ->whereNotNull('norm_k')
                    ->whereNotNull('masuk')
                    ->whereNotNull('keluar')
                    ->whereColumn('masuk', '>', 'norm_m')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                $lmbt = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                    ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                    ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                    ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('masuk', '>', 'norm_m')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                $ipax = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m')
                    ->whereNotNull('norm_k')
                    ->whereNotNull('masuk')
                    ->whereNotNull('keluar')
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('keluar', '<', 'norm_k')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                $ipaj = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                    ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                    ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                    ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('keluar', '<', 'norm_k')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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

                if ($peg->no_payroll < 570) {
                    if ($peg->golongan != ['E', 'F', 'G'] && ($SD > 3 || $SK > 0 || $H > 2 || $IPC > 0 || $M > 0 || $lmbtjm > 50)) {
                        $PRM = 0;
                    } else {
                        $PRM = 1;
                    }
                } else {
                    $PRM = 0;
                }

                return view('../hr/dashboard/reportkerajinan/list', compact('PRM', 'tglawal', 'tglakhir', 'peg', 'H', 'SK', 'SD', 'ITU', 'I', 'IPC', 'IC', 'M', 'lmbtx', 'lmbtjm', 'ipax', 'ipajam'));
            } else {
                # code...
                $pegawai = pegawai::where('jns_peg', '!=', 'SATPAM')
                    ->where('jns_peg', '!=', 'KEBERSIHAN')
                    ->where('bagian', '!=', 'DIREKSI')
                    ->where('jabatan', '!=', 'DIREKSI')
                    ->whereNull('tgl_keluar')
                    ->orderBy('no_payroll', 'asc')
                    // ->where('no_payroll', 1223)
                    // ->limit(3)
                    ->get();

                // dd($pegawai);
                // Ambil semua data pegawai dari database

                // Looping untuk setiap pegawai
                foreach ($pegawai as $pegawai) {
                    $noPayroll = $pegawai->no_payroll;
                    $absenok = absen_h::where('no_payroll', $noPayroll)->pluck('int_absen')->toArray();

                    // dd($absen);
                    if ($absenok) {
                    $absen_d_query = absen_d::whereIn('int_absen', $absenok)->whereBetween('tgl_absen', [$tglawal, $tglakhir]);
                    $absen_counts = $absen_d_query
                        ->selectRaw(
                            '
                            SUM(CASE WHEN jns_absen = "SD" THEN 1 ELSE 0 END) as SD,
                            SUM(CASE WHEN jns_absen = "ITU" THEN 1 ELSE 0 END) as ITU,
                            SUM(CASE WHEN jns_absen = "IPC" THEN 1 ELSE 0 END) as IPC,
                            SUM(CASE WHEN jns_absen = "IC" THEN 1 ELSE 0 END) as IC,
                            SUM(CASE WHEN jns_absen IN ("H1", "H2") THEN 1 ELSE 0 END) as H,
                            SUM(CASE WHEN jns_absen = "SK" THEN 1 ELSE 0 END) as SK,
                            SUM(CASE WHEN jns_absen = "I" THEN 1 ELSE 0 END) as I
                            ',
                        )
                        ->first();

                    $SD = $absen_counts->SD;
                    $ITU = $absen_counts->ITU;
                    $IPC = $absen_counts->IPC;
                    $IC = $absen_counts->IC;
                    $H = $absen_counts->H;
                    $SK = $absen_counts->SK;
                    $I = $absen_counts->I;

                    // dd($IC);
                    // Mencari M ============================================================================================================

                    $M = 0;
                    $tanggalAwalPertama = $tglawal;
                    $tanggalAkhirTerakhir = $tglakhir;

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

                    $tglabs = absen_d::whereIn('int_absen', $absenok)
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
                    $lmbtx = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                    $lmbt = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                    $ipax = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                    $ipaj = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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

                    if ($pegawai->no_payroll < 570) {
                        if ($pegawai->golongan != ['E', 'F', 'G'] && ($SD > 3 || $SK > 0 || $H > 2 || $IPC > 0 || $M > 0 || $lmbtjm > 50)) {
                            $PRM = 0;
                        } else {
                            $PRM = 1;
                        }
                    } else {
                        $PRM = 0;
                    }

                    // Menyimpan data laporan untuk pegawai yang bersangkutan dalam array
                    $laporan[$noPayroll] = [
                        'tglawal' => $tglawal,
                        'tglakhir' => $tglakhir,
                        'pegawai' => $pegawai,
                        'H' => $H,
                        'SK' => $SK,
                        'SD' => $SD,
                        'ITU' => $ITU,
                        'I' => $I,
                        'IPC' => $IPC,
                        'IC' => $IC,
                        'M' => $M,
                        'lmbtx' => $lmbtx,
                        'lmbtjm' => $lmbtjm,
                        'ipax' => $ipax,
                        'ipajam' => $ipajam,
                        'PRM' => $PRM,
                    ];
                }
                }

                return view('../hr/dashboard/reportkerajinan/listbanyak', compact('laporan'));
            }

            // excel bosss ======================================--------=-=-=-=-+===+++++=++=++=+++=++=+++++++++++++++++++++++++++++++++++++++=============+
        } else {
            if ($noPayroll) {
                # code...

                $peg = pegawai::where('no_payroll', $noPayroll)->first();
                

                $absen_d_query = absen_d::whereIn('int_absen', $absen)->whereBetween('tgl_absen', [$tglawal, $tglakhir]);

                $absen_counts = $absen_d_query
                    ->selectRaw(
                        '
                    SUM(CASE WHEN jns_absen = "SD" THEN 1 ELSE 0 END) as SD,
                    SUM(CASE WHEN jns_absen = "ITU" THEN 1 ELSE 0 END) as ITU,
                    SUM(CASE WHEN jns_absen = "IPC" THEN 1 ELSE 0 END) as IPC,
                    SUM(CASE WHEN jns_absen = "IC" THEN 1 ELSE 0 END) as IC,
                    SUM(CASE WHEN jns_absen IN ("H1", "H2") THEN 1 ELSE 0 END) as H,
                    SUM(CASE WHEN jns_absen = "SK" THEN 1 ELSE 0 END) as SK,
                    SUM(CASE WHEN jns_absen = "I" THEN 1 ELSE 0 END) as I
                    ',
                    )
                    ->first();

                $SD = $absen_counts->SD;
                $ITU = $absen_counts->ITU;
                $IPC = $absen_counts->IPC;
                $IC = $absen_counts->IC;
                $H = $absen_counts->H;
                $SK = $absen_counts->SK;
                $I = $absen_counts->I;

                // dd($IC);
                // Mencari M ============================================================================================================

                $M = 0;
                $tanggalAwalPertama = $tglawal;
                $tanggalAkhirTerakhir = $tglakhir;

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

                $noPayroll = $absendua->no_payroll;
                $tglabs = absen_d::whereIn('int_absen', $absen)
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
                $lmbtx = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m')
                    ->whereNotNull('norm_k')
                    ->whereNotNull('masuk')
                    ->whereNotNull('keluar')
                    ->whereColumn('masuk', '>', 'norm_m')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                $lmbt = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                    ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                    ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                    ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('masuk', '>', 'norm_m')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                $ipax = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m')
                    ->whereNotNull('norm_k')
                    ->whereNotNull('masuk')
                    ->whereNotNull('keluar')
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('keluar', '<', 'norm_k')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                $ipaj = Presensi::where('no_reg', $absendua->no_payroll)
                    ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                    ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                    ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                    ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                    ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                    ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                    ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                    ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                    ->whereColumn('keluar', '<', 'norm_k')
                    ->where(function ($query) use ($tglawal, $tglakhir) {
                        $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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

                if ($peg->no_payroll < 570) {
                    if ($peg->golongan != ['E', 'F', 'G'] && ($SD > 3 || $SK > 0 || $H > 2 || $IPC > 0 || $M > 0 || $lmbtjm > 50)) {
                        $PRM = 0;
                    } else {
                        $PRM = 1;
                    }
                } else {
                    $PRM = 0;
                }

                // Create a new PhpSpreadsheet object
                $spreadsheet = new Spreadsheet();

                // Get the active worksheet
                $worksheet = $spreadsheet->getActiveSheet();

                // Set title
                $title = 'LAPORAN KERAJINAN KARYAWAN';
                $worksheet->setCellValue('A1', $title);
                $worksheet->mergeCells('A1:N1'); // Merge cells for the title
                $worksheet
                    ->getStyle('A1')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(16);
                $worksheet
                    ->getStyle('A1')
                    ->getAlignment()
                    ->setHorizontal('center');

                // Set headers
                $headers = ['Nama Pegawai', 'No Payroll', 'Gol', 'IC', 'SK', 'SD', 'H', 'I','ITU','IC', 'IPC', 'M', 'lmbtx', 'lmbtjm', 'ipax', 'ipajam'];
                $col = 'A';
                foreach ($headers as $header) {
                    $worksheet->setCellValue($col . '2', $header);
                    $col++;
                }

                // Add data to the spreadsheet
                $row = 3;
                $worksheet->setCellValue('A' . $row, $peg->nama_asli);
                $worksheet->setCellValue('B' . $row, $peg->no_payroll);
                $worksheet->setCellValue('C' . $row, $peg->golongan);
                $worksheet->setCellValue('D' . $row, $IC);
                $worksheet->setCellValue('E' . $row, $SK);
                $worksheet->setCellValue('F' . $row, $SD);
                $worksheet->setCellValue('G' . $row, $H);
                $worksheet->setCellValue('H' . $row, $I);
                $worksheet->setCellValue('I' . $row, $ITU);
                $worksheet->setCellValue('J' . $row, $IC);
                $worksheet->setCellValue('K' . $row, $IPC);
                $worksheet->setCellValue('L' . $row, $M);
                $worksheet->setCellValue('M' . $row, $lmbtx);
                $worksheet->setCellValue('N' . $row, $lmbtjm);
                $worksheet->setCellValue('O' . $row, $ipax);
                $worksheet->setCellValue('P' . $row, $ipajam);

                // Apply borders to the cells
                $highestColumn = $worksheet->getHighestColumn();
                $highestRow = $worksheet->getHighestRow();
                $worksheet
                    ->getStyle('A2:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(PhpSpreadsheetStyleBorder::BORDER_THIN);

                // ... (rest of your code)

                // Get the current datetime in the desired format
                $now = Carbon::now()->format('Y-m-d_H-i-s'); // Example format: YYYY-MM-DD_HH-mm-ss
                // Create the file name with the formatted datetime and employee payroll number
                $filename = "report_kerajinan_{$peg->no_payroll}_{$now}.xlsx";

                // Save the Excel file to the server
                $excelWriter = new Xlsx($spreadsheet);
                $excelWriter->save($filename);

                // Set headers for Excel download
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');

                // Output the Excel file to the browser
                readfile($filename);

                // Delete the temporary Excel file from the server
                unlink($filename);

                exit();
            } else {
                # code...
                $pegawai = pegawai::where('jns_peg', '!=', 'SATPAM')
                    ->where('jns_peg', '!=', 'KEBERSIHAN')
                    ->where('bagian', '!=', 'DIREKSI')
                    ->where('jabatan', '!=', 'DIREKSI')
                    ->whereNull('tgl_keluar')
                    ->orderBy('no_payroll', 'asc')
                    ->get();

                // dd($pegawai);
                // Ambil semua data pegawai dari database

                // Looping untuk setiap pegawai
                foreach ($pegawai as $pegawai) {
                    $noPayroll = $pegawai->no_payroll;
                    $absenok = absen_h::where('no_payroll', $noPayroll)->pluck('int_absen')->toArray();

                  
                    if ($absenok) {
                    $absen_d_query = absen_d::whereIn('int_absen', $absenok)->whereBetween('tgl_absen', [$tglawal, $tglakhir]);
                    $absen_counts = $absen_d_query
                        ->selectRaw(
                            '
                            SUM(CASE WHEN jns_absen = "SD" THEN 1 ELSE 0 END) as SD,
                            SUM(CASE WHEN jns_absen = "ITU" THEN 1 ELSE 0 END) as ITU,
                            SUM(CASE WHEN jns_absen = "IPC" THEN 1 ELSE 0 END) as IPC,
                            SUM(CASE WHEN jns_absen = "IC" THEN 1 ELSE 0 END) as IC,
                            SUM(CASE WHEN jns_absen IN ("H1", "H2") THEN 1 ELSE 0 END) as H,
                            SUM(CASE WHEN jns_absen = "SK" THEN 1 ELSE 0 END) as SK,
                            SUM(CASE WHEN jns_absen = "I" THEN 1 ELSE 0 END) as I
                            ',
                        )
                        ->first();

                    $SD = $absen_counts->SD;
                    $ITU = $absen_counts->ITU;
                    $IPC = $absen_counts->IPC;
                    $IC = $absen_counts->IC;
                    $H = $absen_counts->H;
                    $SK = $absen_counts->SK;
                    $I = $absen_counts->I;

                    // dd($IC);
                    // Mencari M ============================================================================================================

                    $M = 0;
                    $tanggalAwalPertama = $tglawal;
                    $tanggalAkhirTerakhir = $tglakhir;

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

                    $tglabs = absen_d::whereIn('int_absen', $absenok)
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
                    $lmbtx = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                    $lmbt = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('masuk', '>', 'norm_m')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                    $ipax = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m')
                        ->whereNotNull('norm_k')
                        ->whereNotNull('masuk')
                        ->whereNotNull('keluar')
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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
                    $ipaj = Presensi::where('no_reg', $noPayroll)
                        ->whereNotNull('norm_m') // Kolom 'norm_m' tidak boleh NULL
                        ->whereNotNull('norm_k') // Kolom 'norm_k' tidak boleh NULL
                        ->whereNotNull('masuk') // Kolom 'masuk' tidak boleh NULL
                        ->whereNotNull('keluar') // Kolom 'keluar' tidak boleh NULL
                        ->where('norm_m', '<>', '') // Tidak sama dengan string kosong
                        ->where('norm_k', '<>', '') // Tidak sama dengan string kosong
                        ->where('masuk', '<>', '') // Tidak sama dengan string kosong
                        ->where('keluar', '<>', '') // Tidak sama dengan string kosong
                        ->whereColumn('keluar', '<', 'norm_k')
                        ->where(function ($query) use ($tglawal, $tglakhir) {
                            $query->whereRaw('tanggal BETWEEN ? AND ?', [$tglawal, $tglakhir]);
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

                    if ($pegawai->no_payroll < 570) {
                        if ($pegawai->golongan != ['E', 'F', 'G'] && ($SD > 3 || $SK > 0 || $H > 2 || $IPC > 0 || $M > 0 || $lmbtjm > 50)) {
                            $PRM = 0;
                        } else {
                            $PRM = 1;
                        }
                    } else {
                        $PRM = 0;
                    }

                    // Menyimpan data laporan untuk pegawai yang bersangkutan dalam array
                    $laporan[$noPayroll] = [
                        'tglawal' => $tglawal,
                        'tglakhir' => $tglakhir,
                        'pegawai' => $pegawai,
                        'H' => $H,
                        'SK' => $SK,
                        'SD' => $SD,
                        'ITU' => $ITU,
                        'I' => $I,
                        'IPC' => $IPC,
                        'IC' => $IC,
                        'M' => $M,
                        'lmbtx' => $lmbtx,
                        'lmbtjm' => $lmbtjm,
                        'ipax' => $ipax,
                        'ipajam' => $ipajam,
                        'PRM' => $PRM,
                    ];
                }
            }
            

      
                // Create a new Spreadsheet instance
                $spreadsheet = new Spreadsheet();
                
                // Create a new worksheet
                $worksheet = $spreadsheet->getActiveSheet();
                
                // Set title/header
                $worksheet->mergeCells('A1:P1');
                $worksheet->setCellValue('A1', 'DATA KERAJINAN KARYAWAN');
                $worksheet->getStyle('A1')->getAlignment()->setHorizontal(PhpSpreadsheetStyleBorder::BORDER_THIN);
                $worksheet->getStyle('A1')->getFont()->setSize(14);
                
                // Set the table headers
                $headers = [
                    'No Payroll', 'Nama Pegawai', 'Gol', 'H', 'SK', 'SD', 'ITU', 'I', 'IPC', 'IC', 'M', 'LMBT(X)', 'LMBT(Jam)', 'IPA(X)', 'IPA(Jam)', 'PRM'
                ];
                
                // Set the headers in the first row of the worksheet
                $columnIndex = 1;
                foreach ($headers as $header) {
                    $worksheet->setCellValueByColumnAndRow($columnIndex, 2, $header);
                    $worksheet->getStyleByColumnAndRow($columnIndex, 2)->getFont()->setBold(true);
                    $worksheet->getColumnDimensionByColumn($columnIndex)->setAutoSize(true);
                    $columnIndex++;
                }
                
                // Populate the data into the worksheet
                $rowIndex = 3;
                foreach ($laporan as $pegawaiData) {
                    $worksheet->setCellValueByColumnAndRow(1, $rowIndex, $pegawaiData['pegawai']->no_payroll);
                    $worksheet->setCellValueByColumnAndRow(2, $rowIndex, $pegawaiData['pegawai']->nama_asli); // Assuming 'nama' is the column name for the employee name
                    $worksheet->setCellValueByColumnAndRow(3, $rowIndex, $pegawaiData['pegawai']->golongan); // Assuming 'golongan' is the column name for the employee's golongan
                    $worksheet->setCellValueByColumnAndRow(4, $rowIndex, $pegawaiData['H']);
                    $worksheet->setCellValueByColumnAndRow(5, $rowIndex, $pegawaiData['SK']);
                    $worksheet->setCellValueByColumnAndRow(6, $rowIndex, $pegawaiData['SD']);
                    $worksheet->setCellValueByColumnAndRow(7, $rowIndex, $pegawaiData['ITU']);
                    $worksheet->setCellValueByColumnAndRow(8, $rowIndex, $pegawaiData['I']);
                    $worksheet->setCellValueByColumnAndRow(9, $rowIndex, $pegawaiData['IPC']);
                    $worksheet->setCellValueByColumnAndRow(10, $rowIndex, $pegawaiData['IC']);
                    $worksheet->setCellValueByColumnAndRow(11, $rowIndex, $pegawaiData['M']);
                    $worksheet->setCellValueByColumnAndRow(12, $rowIndex, $pegawaiData['lmbtx']);
                    $worksheet->setCellValueByColumnAndRow(13, $rowIndex, $pegawaiData['lmbtjm']);
                    $worksheet->setCellValueByColumnAndRow(14, $rowIndex, $pegawaiData['ipax']);
                    $worksheet->setCellValueByColumnAndRow(15, $rowIndex, $pegawaiData['ipajam']);
                    $worksheet->setCellValueByColumnAndRow(16, $rowIndex, $pegawaiData['PRM']);
                
                    // Apply borders to the cells
                    for ($i = 1; $i <= 16; $i++) {
                        $worksheet->getStyleByColumnAndRow($i, $rowIndex)->getBorders()->applyFromArray([
                            'allBorders' => [
                                'borderStyle' => PhpSpreadsheetStyleBorder::BORDER_THIN,
                            ],
                        ]);
                    }
                
                    $rowIndex++;
                }
                
                // Set headers for Excel download
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="laporan_kerajinan_Rekap.xlsx"');
                header('Cache-Control: max-age=0');
                
                // Create a writer for Xlsx
                $writer = new Xlsx($spreadsheet);
                
                // Save the Excel file to PHP output
                $writer->save('php://output');
                
                           
            }
        }
    }
}
