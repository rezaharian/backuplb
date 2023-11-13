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
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Maximum;

class ReportAbsenController extends Controller
{
    //

    public function index()
    {
        return view('../hr/dashboard/reportabsen/index');
    }

    public function list(Request $request)
    {
        if ($request->report_type == 'gaji_excel') {
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;

            $url = '/hr/dashboard/reportabsen/rekapgaji_excel';
            $url .= "?tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir";

            return redirect()->to(url($url));
        } else {
            // Add your code here for the case when report_type is not 'Gaji Excel'

            set_time_limit(500);
            $tgl_awal = Carbon::parse($request->tgl_awal);
            $tgl_akhir = Carbon::parse($request->tgl_akhir);

            $taw = Carbon::parse($request->tgl_awal)->format('d-m-Y');
            $tak = Carbon::parse($request->tgl_akhir)->format('d-m-Y');

            $daftar_tanggal = [];
            $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

            for ($i = 0; $i <= $jumlah_hari; $i++) {
                $daftar_tanggal[] = $tgl_awal
                    ->copy()
                    ->addDays($i)
                    ->format('Y-m-d');
            }

            $nik = $request->no_payroll;

            $pegawaiQuery = Pegawai::where(function ($query) {
                $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
            })
                ->where('bagian', '!=', 'DIREKSI')
                ->orderBy('no_payroll', 'asc');

            if ($nik) {
                $pegawaiQuery->where('no_payroll', $nik);
            }

            $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

            $pegawaiData = [];

            // Mengumpulkan semua nomor registrasi pegawai
            $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

            // Mengambil data absen pegawai
            $absen = Presensi::whereIn('no_reg', $noRegistrasiPegawai)
                ->whereIn('tanggal', $daftar_tanggal)
                ->orderBy('tanggal', 'asc')
                ->get();

            // Mengumpulkan semua tanggal absen
            $absen_tanggal = $absen->pluck('tanggal')->toArray();

            // Mengumpulkan data onoff_tg berdasarkan tanggal
            $onoff_tg = onoff_tg::whereIn('tgl_off', $absen_tanggal)
                ->orWhereIn('tgl_on', $absen_tanggal)
                ->get();

            // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model pertama tanpa absen_h
            $absen_d = absen_d::whereIn('tgl_absen', $absen_tanggal)
                ->whereIn('no_reg', $noRegistrasiPegawai)
                ->get();

            // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model kedua dengan absen_h
            // $absen_d = absen_d::join('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
            // ->whereIn('absen_ds.tgl_absen', $absen_tanggal)
            // ->get();

            // Mengumpulkan data tdkabsen berdasarkan nomor registrasi pegawai dan tanggal absen
            $tdkabsen = Tdkabsen::whereIn('no_payroll', $noRegistrasiPegawai)
                ->whereIn('ta_tgl', $absen_tanggal)
                ->get();

            // Mengumpulkan data lembur berdasarkan tanggal dan nomor registrasi pegawai
            $lembur = overtime::whereIn('ot_dat', $absen_tanggal)
                ->whereIn('no_payroll', $noRegistrasiPegawai)
                ->get();

            // Mengumpulkan data tgl_libur berdasarkan tanggal
            $tglLibur = TglLibur::whereIn('tgl_libur', $absen_tanggal)
                ->pluck('tgl_libur')
                ->toArray();

            // dd($tglLibur);
            $pegawaiData = [];
            $shift3_total = 0;
            $shift2_total = 0;
            $uml2_total = 0;
            $uml1_total = 0;
            foreach ($pegawai as $peg) {
                $pegawaiAbsenData = [];

                foreach ($absen as $absenData) {
                    if ($absenData->no_reg === $peg->no_payroll) {
                        $tanggal = $absenData->tanggal;
                        if (!isset($pegawaiAbsenData[$tanggal])) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        } else {
                            // Timpa jika timestamp data saat ini lebih besar
                            if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                                $pegawaiAbsenData[$tanggal] = $absenData;
                            }
                        }
                    }
                }

                $pegawaiAbsen = collect($pegawaiAbsenData)
                    ->sortBy('tanggal')
                    ->values();

                // Tambahkan tanggal yang tidak ada dalam database ke dalam koleksi absen
                $missing_tanggal = array_diff($daftar_tanggal, $pegawaiAbsen->pluck('tanggal')->toArray());

                $onoff_tg_k = onoff_tg::whereIn('tgl_off', $missing_tanggal)
                    ->orWhereIn('tgl_on', $missing_tanggal)
                    ->get();
                foreach ($missing_tanggal as $tanggal) {
                    $tanggalObj = Carbon::parse($tanggal);

                    $newAbsen = [
                        'tanggal' => $tanggalObj,
                        'masuk' => '',
                        'keluar' => '',
                        'lembur' => '',
                        'ket' => '', // Tetap kosong secara default
                    ];

                    $onoffk = $onoff_tg_k->firstWhere('tgl_off', $tanggal) ?? $onoff_tg_k->firstWhere('tgl_on', $tanggal);

                    foreach ($noRegistrasiPegawai as &$registrasi) {
                        $registrasi = ltrim($registrasi, '0');
                        // Jika setelah ltrim, string kosong, atur nilainya menjadi '0'
                        if ($registrasi === '') {
                            $registrasi = '0';
                        } elseif ($registrasi === '0') {
                            $registrasi = '00' . $registrasi; // Menambahkan satu digit '0' di awal
                        }
                    }

                    $noRegistrasiPegawaitanpa0 = $noRegistrasiPegawai[0];

                    $absenDataL = absen_d::whereIn('tgl_absen', [$tanggal])
                        ->whereRaw("TRIM(LEADING '0' FROM no_reg) = ?", [$noRegistrasiPegawaitanpa0])
                        ->first();

                    $tglLiburL = TglLibur::whereIn('tgl_libur', [$tanggal])
                        ->pluck('tgl_libur')
                        ->toArray();
                    // Setel 'ket' berdasarkan prioritas
                    if ($absenDataL) {
                        $newAbsen['ket'] = $absenDataL->jns_absen;
                    } elseif (in_array($tanggal, $tglLiburL)) {
                        $newAbsen['ket'] = 'LN';
                    } else {
                        $newAbsen['ket'] = $newAbsen['ket'] ?: ($onoffk ? ($onoffk->tgl_off == $tanggal ? 'off' : 'on') : ($tanggalObj->isSaturday() ? 'Sabtu' : ($tanggalObj->isSunday() ? 'Minggu' : 'M')));
                    }

                    // dd($tglLibur);

                    $pegawaiAbsen->push($newAbsen);
                }

                // Urutkan kembali koleksi absen berdasarkan tanggal
                $pegawaiAbsen = $pegawaiAbsen->sortBy('tanggal');

                $jumlah_hari = 0;
                $uml1 = 0; // Jumlah hari biasa atau hari libur dengan kondisi on
                $uml2 = 0; // Jumlah hari biasa atau hari libur dengan kondisi off
                $shift2 = 0;
                $shift3 = 0;
                $total_lembur = 0; // Inisialisasi variabel total_lembur di awal

                foreach ($pegawaiAbsen as &$item) {
                    $tanggal = $item['tanggal'];

                    $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                    if ($onoff) {
                        if ($onoff->tgl_off == $tanggal) {
                            $item['ket'] = 'off';
                        } elseif ($onoff->tgl_on == $tanggal) {
                            $item['ket'] = 'on';
                        }
                    } else {
                        if (in_array($tanggal, $tglLibur)) {
                            $item['ket'] = 'LN';
                        } else {
                            $item['ket'] = $item['ket'] ?: '';
                        }
                    }

                    if (!empty($item['masuk']) && !empty($item['keluar'])) {
                        $jumlah_hari++;
                    }

                    $absenData = $absen_d
                        ->where('tgl_absen', $tanggal)
                        ->where('no_reg', $peg->no_payroll)
                        ->first();

                    if ($absenData) {
                        $item['ket'] = $absenData->jns_absen;
                    }

                    $masuk = Carbon::parse($item['masuk']);
                    $keluar = Carbon::parse($item['keluar']);

                    $lemburData = $lembur
                        ->where('ot_dat', $tanggal)
                        ->where('no_payroll', $peg->no_payroll)
                        ->first();

                    // Menghitung lembur
                    if ($lemburData) {
                        $start = Carbon::parse($lemburData->ot_hrb);
                        $end = Carbon::parse($lemburData->ot_hre);

                        $startq = Carbon::parse($lemburData->ot_hrb)->format('H:i');
                        $endq = Carbon::parse($lemburData->ot_hre)->format('H:i');

                        $item['start'] = $startq;
                        $item['end'] = $endq;

                        // dd($masuk->hour);
                        $start_convert = $start->hour * 60 + $start->minute; // Konversi ke menit
                        $end_convert = $end->hour * 60 + $end->minute; // Konversi ke menit
                        $masuk_convert = $masuk->hour * 60 + $masuk->minute; // Konversi ke menit
                        $keluar_convert = $keluar->hour * 60 + $keluar->minute; // Konversi ke menit

                        if ($start_convert >= $end_convert) {
                            $end_convert = $end_convert + 1440;
                        } else {
                            $menit_lembur = $end_convert - $start_convert;
                        }
                        if ($masuk_convert >= $keluar_convert) {
                            $keluar_convert = $keluar_convert + 1440;
                        }

                        // periksa jam masuk dan jam mulai lembur
                        if ($masuk_convert >= $start_convert) {
                            $start = $masuk_convert; // Mulai hitung lembur dari waktu masuk
                        } else {
                            $start = $start_convert;
                        }

                        // Periksa apakah lembur berakhir setelah waktu keluar
                        if ($end_convert >= $keluar_convert) {
                            $end = $keluar_convert; // Akhiri hitungan lembur pada waktu keluar
                        } else {
                            $end = $end_convert;
                        }

                        $lembur_a = $end - $start;
                        $lemburHours = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value
                        $lembur_hours_asli = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value

                        $absen_tanggal = Carbon::parse($tanggal);

                        $lembur_asli = $lembur_hours_asli;

                        // Menghitung lembur berdasarkan kondisi
                        // dd($masuk_convert);
                        if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] != $item['keluar'])) {
                            [$startHour, $startMinute] = explode(':', $item['start']);
                            [$endHour, $endMinute] = explode(':', $item['end']);

                            $startTimeInMinutes = $startHour * 60 + $startMinute;
                            $endTimeInMinutes = $endHour * 60 + $endMinute;

                            if ($endTimeInMinutes < $startTimeInMinutes) {
                                $lemburHours = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                            } else {
                                $lemburHours = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                            }
                            // dd($startTimeInMinutes);
                        }
                        if ($lemburHours > 5) {
                            $lemburHours -= 0.5;
                        } elseif (!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && !in_array($peg->bagian, ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA']) && (($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960)) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320)))) {
                            $lemburHours -= 0.5;
                        }


                        $jam_biasa = 8; // Jumlah jam kerja biasa dalam sehari

                        if ($lemburHours > $jam_biasa) {
                            $lembur_double = min($lemburHours, $jam_biasa) * 2; // Hitung lembur 2x untuk 8 jam pertama
                            $lembur_triple = max($lemburHours - $jam_biasa, 0) * 3; // Hitung lembur 3x untuk sisa jam lembur
    
                            $lemburHours = $lembur_double + $lembur_triple; // Jumlahkan lembur 2x dan 3x
                        } else {
                        if (empty($item['masuk']) || empty($item['keluar'])) {
                            $lemburHours = 0;
                        } elseif (($absen_tanggal->isSaturday() && $item['ket'] == 'on') || ($absen_tanggal->isSunday() && $item['ket'] == 'on')) {
                            $lemburHours *= 2;
                            $lemburHours -= 0.5;
                        } elseif ($item['ket'] == 'LN' || $item['ket'] == 'off' || ($absen_tanggal->isSaturday() || $absen_tanggal->isSunday())) {
                            $lemburHours *= 2;
                        } else {
                            $lemburHours *= 2;
                            $lemburHours -= 0.5;
                        }}

                        if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] != $item['keluar'])) {
                            [$startHour, $startMinute] = explode(':', $item['start']);
                            [$endHour, $endMinute] = explode(':', $item['end']);

                            $startTimeInMinutes = $startHour * 60 + $startMinute;
                            $endTimeInMinutes = $endHour * 60 + $endMinute;

                            if ($endTimeInMinutes < $startTimeInMinutes) {
                                $lembur_hours_asli = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                            } else {
                                $lembur_hours_asli = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                            }
                            // dd($startTimeInMinutes);
                        }

                        if (empty($item['masuk']) || empty($item['keluar'])) {
                            $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                        } elseif ($lembur_hours_asli > 5) {
                            $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                        } elseif (!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && !in_array($peg->bagian, ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA']) && (($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960)) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320)))) {
                            $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                        }

                        // dd($lembur_hours_asli);

                        if ($lemburHours > 0) {
                            $item['lembur'] = $lembur_hours_asli;
                            $item['lembur_total'] = $lemburHours;
                            $total_lembur += $lemburHours;
                        }

                        // dd($total_lembur);
                    }

                    $tgl = Carbon::parse($tanggal);
                    $golongan = trim($peg->golongan);

                    // Hitung jumlah hari kerja (tidak termasuk hari libur)
                    if (($item['lembur'] >= 1 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null])) || $item['ket'] == 'on') {
                        $masuk_time = Carbon::parse($item['masuk']);
                        $keluar_time = Carbon::parse($item['keluar']);

                        if ($masuk_time->hour < 12 && $keluar_time->between(Carbon::parse('17:45'), Carbon::parse('18:30'))) {
                            // Periksa apakah hari ini bukan Sabtu atau Minggu
                            if (!$tgl->isWeekend()) {
                                $uml1++;
                                $uml1_total++;
                            }
                        }
                    }

                    // $lemburHours = 0; // inisialisasi variabel
                    $golongan = trim($peg->golongan);

                    // dd($tgl);

                    // Hitung jumlah hari uml2
                    if ($item['lembur'] >= 1 && $lembur_asli < 6 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null, '']) && (($tgl->isWeekend() || in_array($tgl->dayOfWeek, [0, 6])) && $item['ket'] != 'on')) {
                        $uml2++;
                        $uml2_total++;
                    }

                    if (!empty($item['masuk']) && !empty($item['keluar'])) {
                        $masuk_time = Carbon::parse($item['masuk']);
                        $keluar_time = Carbon::parse($item['keluar']);

                        if (($masuk_time->isBetween('18:00', '23:59') && $keluar_time->isBetween('05:00', '11:00')) || ($masuk_time->isBetween('13:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                            $shift3++;
                            $shift3_total++;
                        }
                    }

                    if (!empty($item['masuk']) && !empty($item['keluar'])) {
                        $masuk_time = Carbon::parse($item['masuk']);
                        $keluar_time = Carbon::parse($item['keluar']);

                        if (($masuk_time->isBetween('06:00', '17:00') && $keluar_time->isBetween('21:00', '23:59')) || ($masuk_time->isBetween('12:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                            $shift2++;
                            $shift2_total++;
                        }
                    }
                }

                $daftar_tanggal_absen = $pegawaiAbsen->pluck('tanggal')->toArray();

                foreach ($tdkabsen as $tdk) {
                    $tanggal = $tdk->ta_tgl;
                    $status = $tdk->status;

                    foreach ($pegawaiAbsen as &$item) {
                        $itemTanggal = Carbon::parse($item['tanggal']);

                        if ($itemTanggal->format('Y-m-d') === $tanggal) {
                            $item['ket'] = $status;
                            break;
                        }
                    }
                }

                $pegawaiData[] = [
                    'pegawai' => $peg,
                    'absen' => $pegawaiAbsen,
                    'jumlah_hari' => $jumlah_hari,
                    'total_lembur' => $total_lembur,
                    'uml1' => $uml1,
                    'uml2' => $uml2,
                    'shift2' => $shift2,
                    'shift3' => $shift3,
                ];
            }

            return view('../hr/dashboard/reportabsen/list', [
                'shift3_total' => $shift3_total,
                'shift2_total' => $shift2_total,
                'uml2_total' => $uml2_total,
                'uml1_total' => $uml1_total,
                'pegawaiData' => $pegawaiData,
                'tgl_awal' => $taw,
                'tgl_akhir' => $tak,
                'no_payroll' => $nik,
            ]);
        }
    }

    public function list_print(Request $request)
    {
        set_time_limit(500);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);

        $taw = Carbon::parse($request->tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($request->tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $nik = $request->no_payroll;

        $pegawaiQuery = Pegawai::where(function ($query) {
            $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->orderBy('no_payroll', 'asc');

        if ($nik) {
            $pegawaiQuery->where('no_payroll', $nik);
        }

        $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

        $pegawaiData = [];

        // Mengumpulkan semua nomor registrasi pegawai
        $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

        // Mengambil data absen pegawai
        $absen = Presensi::whereIn('no_reg', $noRegistrasiPegawai)
            ->whereIn('tanggal', $daftar_tanggal)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mengumpulkan semua tanggal absen
        $absen_tanggal = $absen->pluck('tanggal')->toArray();

        // Mengumpulkan data onoff_tg berdasarkan tanggal
        $onoff_tg = onoff_tg::whereIn('tgl_off', $absen_tanggal)
            ->orWhereIn('tgl_on', $absen_tanggal)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model pertama tanpa absen_h
        $absen_d = absen_d::whereIn('tgl_absen', $absen_tanggal)
            ->whereIn('no_reg', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model kedua dengan absen_h
        // $absen_d = absen_d::join('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
        // ->whereIn('absen_ds.tgl_absen', $absen_tanggal)
        // ->get();

        // Mengumpulkan data tdkabsen berdasarkan nomor registrasi pegawai dan tanggal absen
        $tdkabsen = Tdkabsen::whereIn('no_payroll', $noRegistrasiPegawai)
            ->whereIn('ta_tgl', $absen_tanggal)
            ->get();

        // Mengumpulkan data lembur berdasarkan tanggal dan nomor registrasi pegawai
        $lembur = overtime::whereIn('ot_dat', $absen_tanggal)
            ->whereIn('no_payroll', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data tgl_libur berdasarkan tanggal
        $tglLibur = TglLibur::whereIn('tgl_libur', $absen_tanggal)
            ->pluck('tgl_libur')
            ->toArray();

        $pegawaiData = [];
        $shift3_total = 0;
        $shift2_total = 0;
        $uml2_total = 0;
        $uml1_total = 0;
        foreach ($pegawai as $peg) {
            $pegawaiAbsenData = [];

            foreach ($absen as $absenData) {
                if ($absenData->no_reg === $peg->no_payroll) {
                    $tanggal = $absenData->tanggal;
                    if (!isset($pegawaiAbsenData[$tanggal])) {
                        $pegawaiAbsenData[$tanggal] = $absenData;
                    } else {
                        // Timpa jika timestamp data saat ini lebih besar
                        if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        }
                    }
                }
            }

            $pegawaiAbsen = collect($pegawaiAbsenData)
                ->sortBy('tanggal')
                ->values();

            // Tambahkan tanggal yang tidak ada dalam database ke dalam koleksi absen
            $missing_tanggal = array_diff($daftar_tanggal, $pegawaiAbsen->pluck('tanggal')->toArray());

            foreach ($missing_tanggal as $tanggal) {
                $tanggalObj = Carbon::parse($tanggal);

                $newAbsen = [
                    'tanggal' => $tanggalObj,
                    'masuk' => '',
                    'keluar' => '',
                    'lembur' => '',
                    'ket' => $tanggalObj->isSaturday() ? 'Sabtu' : ($tanggalObj->isSunday() ? 'Minggu' : ''),
                ];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $newAbsen['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $newAbsen['ket'] = 'on';
                    }
                }

                if (in_array($tanggal, $tglLibur)) {
                    $newAbsen['ket'] = 'LN';
                } else {
                    $newAbsen['ket'] = $newAbsen['ket'] ?: '';
                }

                $pegawaiAbsen->push($newAbsen);
            }

            // Urutkan kembali koleksi absen berdasarkan tanggal
            $pegawaiAbsen = $pegawaiAbsen->sortBy('tanggal');

            $jumlah_hari = 0;
            $uml1 = 0; // Jumlah hari biasa atau hari libur dengan kondisi on
            $uml2 = 0; // Jumlah hari biasa atau hari libur dengan kondisi off
            $shift2 = 0;
            $shift3 = 0;
            $total_lembur = 0; // Inisialisasi variabel total_lembur di awal

            foreach ($pegawaiAbsen as &$item) {
                $tanggal = $item['tanggal'];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $item['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $item['ket'] = 'on';
                    }
                } else {
                    if (in_array($tanggal, $tglLibur)) {
                        $item['ket'] = 'LN';
                    } else {
                        $item['ket'] = $item['ket'] ?: '';
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $jumlah_hari++;
                }

                $absenData = $absen_d
                    ->where('tgl_absen', $tanggal)
                    ->where('no_reg', $peg->no_payroll)
                    ->first();

                if ($absenData) {
                    $item['ket'] = $absenData->jns_absen;
                }

                $masuk = Carbon::parse(trim($item['masuk']));
                $keluar = Carbon::parse(trim($item['keluar']));

                $lemburData = $lembur
                    ->where('ot_dat', $tanggal)
                    ->where('no_payroll', $peg->no_payroll)
                    ->first();

                // Menghitung lembur
                if ($lemburData) {
                    $start = Carbon::parse($lemburData->ot_hrb);
                    $end = Carbon::parse($lemburData->ot_hre);

                    $start_convert = $start->hour * 60 + $start->minute; // Konversi ke menit
                    $end_convert = $end->hour * 60 + $end->minute; // Konversi ke menit
                    $masuk_convert = $masuk->hour * 60 + $masuk->minute; // Konversi ke menit
                    $keluar_convert = $keluar->hour * 60 + $keluar->minute; // Konversi ke menit

                    if ($start_convert >= $end_convert) {
                        $end_convert = $end_convert + 1440;
                    } else {
                        $menit_lembur = $end_convert - $start_convert;
                    }
                    if ($masuk_convert >= $keluar_convert) {
                        $keluar_convert = $keluar_convert + 1440;
                    }

                    // periksa jam masuk dan jam mulai lembur
                    if ($masuk_convert >= $start_convert) {
                        $start = $masuk_convert; // Mulai hitung lembur dari waktu masuk
                    } else {
                        $start = $start_convert;
                    }

                    // Periksa apakah lembur berakhir setelah waktu keluar
                    if ($end_convert >= $keluar_convert) {
                        $end = $keluar_convert; // Akhiri hitungan lembur pada waktu keluar
                    } else {
                        $end = $end_convert;
                    }

                    $lembur_a = $end - $start;
                    $lemburHours = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value
                    $lembur_hours_asli = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value

                    if ($lemburHours > 5) {
                        $lemburHours -= 0.5;
                    }
                    $lembur_asli = $lembur_hours_asli;

                    // Menghitung lembur berdasarkan kondisi
                    if ($item['ket'] == 'on' && (date('N', strtotime($tanggalObj)) == 6 || date('N', strtotime($tanggalObj)) == 7)) {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    } elseif ($item['ket'] == 'LN' || $item['ket'] == 'off') {
                        $lemburHours *= 2;
                    } else {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    }

                    $item['lembur'] = $lembur_asli;
                    $item['total_lembur'] = $lemburHours; // Menyimpan total lembur pada item absen
                    $total_lembur += $lemburHours;
                }

                $tgl = Carbon::parse($tanggal);
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari kerja (tidak termasuk hari libur)
                if (($item['lembur'] >= 1 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', '', null, ' '])) || $item['ket'] == 'on') {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if ($masuk_time->hour < 12 && $keluar_time->between(Carbon::parse('17:45'), Carbon::parse('18:30'))) {
                        // Periksa apakah hari ini bukan Sabtu atau Minggu
                        if (!$tgl->isWeekend()) {
                            $uml1++;
                            $uml1_total++;
                        }
                    }
                }
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari uml2
                if ($item['lembur'] >= 1 && $lembur_asli < 6 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null, '']) && (($tgl->isWeekend() || in_array($tgl->dayOfWeek, [0, 6])) && $item['ket'] != 'on')) {
                    $uml2++;
                    $uml2_total++;
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('18:00', '23:59') && $keluar_time->isBetween('05:00', '11:00')) || ($masuk_time->isBetween('13:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift3++;
                        $shift3_total++;
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('06:00', '17:00') && $keluar_time->isBetween('21:00', '23:59')) || ($masuk_time->isBetween('12:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift2++;
                        $shift2_total++;
                    }
                }
            }

            $daftar_tanggal_absen = $pegawaiAbsen->pluck('tanggal')->toArray();

            foreach ($tdkabsen as $tdk) {
                $tanggal = $tdk->ta_tgl;
                $status = $tdk->status;

                foreach ($pegawaiAbsen as &$item) {
                    $itemTanggal = Carbon::parse($item['tanggal']);

                    if ($itemTanggal->format('Y-m-d') === $tanggal) {
                        $item['ket'] = $status;
                        break;
                    }
                }
            }

            $pegawaiData[] = [
                'pegawai' => $peg,
                'absen' => $pegawaiAbsen,
                'jumlah_hari' => $jumlah_hari,
                'total_lembur' => $total_lembur,
                'uml1' => $uml1,
                'uml2' => $uml2,
                'shift2' => $shift2,
                'shift3' => $shift3,
            ];
        }
        $pdf = Pdf::loadview('/hr/dashboard/reportabsen/list_print', ['shift3' => $shift3, 'shift2' => $shift2, 'uml2' => $uml2, 'uml1' => $uml1, 'pegawaiData' => $pegawaiData, 'tgl_awal' => $taw, 'tgl_akhir' => $tak, 'total_lembur' => $total_lembur]);
        return $pdf->setPaper('a4', 'potrait')->stream('uraianlembur.pdf');
    }
    // jhvbnmjhvbnjhbvbn
    public function uraianlembur(Request $request)
    {
        set_time_limit(500);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);

        $taw = Carbon::parse($request->tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($request->tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $nik = $request->no_payroll;

        $pegawaiQuery = Pegawai::where(function ($query) {
            $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->orderBy('no_payroll', 'asc');

        if ($nik) {
            $pegawaiQuery->where('no_payroll', $nik);
        }

        $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

        $pegawaiData = [];

        // Mengumpulkan semua nomor registrasi pegawai
        $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

        // Mengambil data absen pegawai
        $absen = Presensi::whereIn('no_reg', $noRegistrasiPegawai)
            ->whereIn('tanggal', $daftar_tanggal)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mengumpulkan semua tanggal absen
        $absen_tanggal = $absen->pluck('tanggal')->toArray();

        // Mengumpulkan data onoff_tg berdasarkan tanggal
        $onoff_tg = onoff_tg::whereIn('tgl_off', $absen_tanggal)
            ->orWhereIn('tgl_on', $absen_tanggal)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model pertama tanpa absen_h
        $absen_d = absen_d::whereIn('tgl_absen', $absen_tanggal)
            ->whereIn('no_reg', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model kedua dengan absen_h
        // $absen_d = absen_d::join('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
        // ->whereIn('absen_ds.tgl_absen', $absen_tanggal)
        // ->get();

        // Mengumpulkan data tdkabsen berdasarkan nomor registrasi pegawai dan tanggal absen
        $tdkabsen = Tdkabsen::whereIn('no_payroll', $noRegistrasiPegawai)
            ->whereIn('ta_tgl', $absen_tanggal)
            ->get();

        // Mengumpulkan data lembur berdasarkan tanggal dan nomor registrasi pegawai
        $lembur = overtime::whereIn('ot_dat', $absen_tanggal)
            ->whereIn('no_payroll', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data tgl_libur berdasarkan tanggal
        $tglLibur = TglLibur::whereIn('tgl_libur', $absen_tanggal)
            ->pluck('tgl_libur')
            ->toArray();

        $pegawaiData = [];
        $shift3_total = 0;
        $shift2_total = 0;
        $uml2_total = 0;
        $uml1_total = 0;
        foreach ($pegawai as $peg) {
            $pegawaiAbsenData = [];

            foreach ($absen as $absenData) {
                if ($absenData->no_reg === $peg->no_payroll) {
                    $tanggal = $absenData->tanggal;
                    if (!isset($pegawaiAbsenData[$tanggal])) {
                        $pegawaiAbsenData[$tanggal] = $absenData;
                    } else {
                        // Timpa jika timestamp data saat ini lebih besar
                        if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        }
                    }
                }
            }

            $pegawaiAbsen = collect($pegawaiAbsenData)
                ->sortBy('tanggal')
                ->values();
            $pegawaiAbsenData = [];

            foreach ($absen as $absenData) {
                if ($absenData->no_reg === $peg->no_payroll) {
                    $tanggal = $absenData->tanggal;
                    if (!isset($pegawaiAbsenData[$tanggal])) {
                        $pegawaiAbsenData[$tanggal] = $absenData;
                    } else {
                        // Timpa jika timestamp data saat ini lebih besar
                        if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        }
                    }
                }
            }

            $pegawaiAbsen = collect($pegawaiAbsenData)
                ->sortBy('tanggal')
                ->values();

            // Tambahkan tanggal yang tidak ada dalam database ke dalam koleksi absen
            $missing_tanggal = array_diff($daftar_tanggal, $pegawaiAbsen->pluck('tanggal')->toArray());

            foreach ($missing_tanggal as $tanggal) {
                $tanggalObj = Carbon::parse($tanggal);

                $newAbsen = [
                    'tanggal' => $tanggalObj,
                    'masuk' => '',
                    'keluar' => '',
                    'lembur' => '',
                    'ket' => $tanggalObj->isSaturday() ? 'Sabtu' : ($tanggalObj->isSunday() ? 'Minggu' : ''),
                ];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $newAbsen['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $newAbsen['ket'] = 'on';
                    }
                }

                if (in_array($tanggal, $tglLibur)) {
                    $newAbsen['ket'] = 'LN';
                } else {
                    $newAbsen['ket'] = $newAbsen['ket'] ?: '';
                }

                $pegawaiAbsen->push($newAbsen);
            }

            // Urutkan kembali koleksi absen berdasarkan tanggal
            $pegawaiAbsen = $pegawaiAbsen->sortBy('tanggal');

            $jumlah_hari = 0;
            $uml1 = 0; // Jumlah hari biasa atau hari libur dengan kondisi on
            $uml2 = 0; // Jumlah hari biasa atau hari libur dengan kondisi off
            $shift2 = 0;
            $shift3 = 0;
            $total_lembur = 0; // Inisialisasi variabel total_lembur di awal

            foreach ($pegawaiAbsen as &$item) {
                $tanggal = $item['tanggal'];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $item['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $item['ket'] = 'on';
                    }
                } else {
                    if (in_array($tanggal, $tglLibur)) {
                        $item['ket'] = 'LN';
                    } else {
                        $item['ket'] = $item['ket'] ?: '';
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $jumlah_hari++;
                }

                $absenData = $absen_d
                    ->where('tgl_absen', $tanggal)
                    ->where('no_reg', $peg->no_payroll)
                    ->first();

                if ($absenData) {
                    $item['ket'] = $absenData->jns_absen;
                }

                $masuk = Carbon::parse(trim($item['masuk']));
                $keluar = Carbon::parse(trim($item['keluar']));

                $lemburData = $lembur
                    ->where('ot_dat', $tanggal)
                    ->where('no_payroll', $peg->no_payroll)
                    ->first();

                // Menghitung lembur
                if ($lemburData) {
                    $start = Carbon::parse($lemburData->ot_hrb);
                    $end = Carbon::parse($lemburData->ot_hre);

                    $startq = Carbon::parse($lemburData->ot_hrb)->format('H:i');
                    $endq = Carbon::parse($lemburData->ot_hre)->format('H:i');

                    $item['start'] = $startq;
                    $item['end'] = $endq;

                    // dd($masuk->hour);
                    $start_convert = $start->hour * 60 + $start->minute; // Konversi ke menit
                    $end_convert = $end->hour * 60 + $end->minute; // Konversi ke menit
                    $masuk_convert = $masuk->hour * 60 + $masuk->minute; // Konversi ke menit
                    $keluar_convert = $keluar->hour * 60 + $keluar->minute; // Konversi ke menit

                    if ($start_convert >= $end_convert) {
                        $end_convert = $end_convert + 1440;
                    } else {
                        $menit_lembur = $end_convert - $start_convert;
                    }
                    if ($masuk_convert >= $keluar_convert) {
                        $keluar_convert = $keluar_convert + 1440;
                    }

                    // periksa jam masuk dan jam mulai lembur
                    if ($masuk_convert >= $start_convert) {
                        $start = $masuk_convert; // Mulai hitung lembur dari waktu masuk
                    } else {
                        $start = $start_convert;
                    }

                    // Periksa apakah lembur berakhir setelah waktu keluar
                    if ($end_convert >= $keluar_convert) {
                        $end = $keluar_convert; // Akhiri hitungan lembur pada waktu keluar
                    } else {
                        $end = $end_convert;
                    }

                    $lembur_a = $end - $start;
                    $lemburHours = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value
                    $lembur_hours_asli = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value

                    $absen_tanggal = Carbon::parse($tanggal);

                    $lembur_asli = $lembur_hours_asli;

                    // Menghitung lembur berdasarkan kondisi
                    // dd($masuk_convert);
                    if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] != $item['keluar'])) {
                        [$startHour, $startMinute] = explode(':', $item['start']);
                        [$endHour, $endMinute] = explode(':', $item['end']);

                        $startTimeInMinutes = $startHour * 60 + $startMinute;
                        $endTimeInMinutes = $endHour * 60 + $endMinute;

                        if ($endTimeInMinutes < $startTimeInMinutes) {
                            $lemburHours = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                        } else {
                            $lemburHours = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                        }
                        // dd($startTimeInMinutes);
                    }
                    if ($lemburHours > 5) {
                        $lemburHours -= 0.5;
                    } elseif (!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && !in_array($peg->bagian, ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA']) && (($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960)) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320)))) {
                        $lemburHours -= 0.5;
                    }

                    $jam_biasa = 8; // Jumlah jam kerja biasa dalam sehari

                    if ($lemburHours > $jam_biasa) {
                        $lembur_double = min($lemburHours, $jam_biasa) * 2; // Hitung lembur 2x untuk 8 jam pertama
                        $lembur_triple = max($lemburHours - $jam_biasa, 0) * 3; // Hitung lembur 3x untuk sisa jam lembur

                        $lemburHours = $lembur_double + $lembur_triple; // Jumlahkan lembur 2x dan 3x
                    } else {
                        if (empty($item['masuk']) || empty($item['keluar'])) {
                            $lemburHours = 0;
                        } elseif (($absen_tanggal->isSaturday() && $item['ket'] == 'on') || ($absen_tanggal->isSunday() && $item['ket'] == 'on')) {
                            $lemburHours *= 2;
                            $lemburHours -= 0.5;
                        } elseif ($item['ket'] == 'LN' || $item['ket'] == 'off' || ($absen_tanggal->isSaturday() || $absen_tanggal->isSunday())) {
                            $lemburHours *= 2;
                        } else {
                            $lemburHours *= 2;
                            $lemburHours -= 0.5;
                        }
                    }
                    // dd($lemburHours);

                    if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] != $item['keluar']) ) {
                        [$startHour, $startMinute] = explode(':', $item['start']);
                        [$endHour, $endMinute] = explode(':', $item['end']);

                        $startTimeInMinutes = $startHour * 60 + $startMinute;
                        $endTimeInMinutes = $endHour * 60 + $endMinute;

                        if ($endTimeInMinutes < $startTimeInMinutes) {
                            $lembur_hours_asli = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                        } else {
                            $lembur_hours_asli = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                        }
                        // dd($startTimeInMinutes);
                    }

                    if (empty($item['masuk']) || empty($item['keluar'])) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    } elseif ($lembur_hours_asli > 5) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    } elseif (!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && !in_array($peg->bagian, ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA']) && (($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960)) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320)))) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    }

                    if ($lemburHours > 0) {
                        $item['lembur'] = $lembur_hours_asli;
                        $item['lembur_total'] = $lemburHours;
                        $total_lembur += $lemburHours;
                    }
                }

                $tgl = Carbon::parse($tanggal);
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari kerja (tidak termasuk hari libur)
                if (($item['lembur'] >= 1 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ''])) || $item['ket'] == 'on') {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if ($item['lembur'] && $masuk_time->hour < 12 && $keluar_time->between(Carbon::parse('17:45'), Carbon::parse('18:30'))) {
                        // Periksa apakah hari ini bukan Sabtu atau Minggu
                        if (!$tgl->isWeekend()) {
                            $uml1++;
                            $uml1_total++;
                        }
                    }
                }
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari uml2
                if ($item['lembur'] >= 1 && $lembur_asli < 6 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null, '']) && (($tgl->isWeekend() || in_array($tgl->dayOfWeek, [0, 6])) && $item['ket'] != 'on')) {
                    $uml2++;
                    $uml2_total++;
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('18:00', '23:59') && $keluar_time->isBetween('05:00', '11:00')) || ($masuk_time->isBetween('13:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift3++;
                        $shift3_total++;
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('06:00', '17:00') && $keluar_time->isBetween('21:00', '23:59')) || ($masuk_time->isBetween('12:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift2++;
                        $shift2_total++;
                    }
                }
            }

            $daftar_tanggal_absen = $pegawaiAbsen->pluck('tanggal')->toArray();

            foreach ($tdkabsen as $tdk) {
                $tanggal = $tdk->ta_tgl;
                $status = $tdk->status;

                foreach ($pegawaiAbsen as &$item) {
                    $itemTanggal = Carbon::parse($item['tanggal']);

                    if ($itemTanggal->format('Y-m-d') === $tanggal) {
                        $item['ket'] = $status;
                        break;
                    }
                }
            }

            $pegawaiData[] = [
                'pegawai' => $peg,
                'absen' => $pegawaiAbsen,
                'jumlah_hari' => $jumlah_hari,
                'total_lembur' => $total_lembur,
                'uml1' => $uml1,
                'uml2' => $uml2,
                'shift2' => $shift2,
                'shift3' => $shift3,
            ];
        }

        return view('../hr/dashboard/reportabsen/uraianlembur', ['pegawaiData' => $pegawaiData, 'tgl_awal' => $taw, 'tgl_akhir' => $tak, 'total_lembur' => $total_lembur]);
    }

    // kjhcvbjhuyguhbjiugyhb
    public function rekapgaji(Request $request)
    {
        set_time_limit(500);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);

        $taw = Carbon::parse($request->tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($request->tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $nik = $request->no_payroll;

        $pegawaiQuery = Pegawai::where(function ($query) {
            $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->whereNotIn('jns_peg', ['SATPAM', 'KEBERSIHAN'])
            ->orderBy('no_payroll', 'asc');

        if ($nik) {
            $pegawaiQuery->where('no_payroll', $nik);
        }

        $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

        $pegawaiData = [];

        // Mengumpulkan semua nomor registrasi pegawai
        $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

        // Mengambil data absen pegawai
        $absen = Presensi::whereIn('no_reg', $noRegistrasiPegawai)
            ->whereIn('tanggal', $daftar_tanggal)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mengumpulkan semua tanggal absen
        $absen_tanggal = $absen->pluck('tanggal')->toArray();

        // Mengumpulkan data onoff_tg berdasarkan tanggal
        $onoff_tg = onoff_tg::whereIn('tgl_off', $absen_tanggal)
            ->orWhereIn('tgl_on', $absen_tanggal)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model pertama tanpa absen_h
        $absen_d = absen_d::whereIn('tgl_absen', $absen_tanggal)
            ->whereIn('no_reg', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model kedua dengan absen_h
        // $absen_d = absen_d::join('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
        // ->whereIn('absen_ds.tgl_absen', $absen_tanggal)
        // ->get();

        // Mengumpulkan data tdkabsen berdasarkan nomor registrasi pegawai dan tanggal absen
        $tdkabsen = Tdkabsen::whereIn('no_payroll', $noRegistrasiPegawai)
            ->whereIn('ta_tgl', $absen_tanggal)
            ->get();

        // Mengumpulkan data lembur berdasarkan tanggal dan nomor registrasi pegawai
        $lembur = overtime::whereIn('ot_dat', $absen_tanggal)
            ->whereIn('no_payroll', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data tgl_libur berdasarkan tanggal
        $tglLibur = TglLibur::whereIn('tgl_libur', $absen_tanggal)
            ->pluck('tgl_libur')
            ->toArray();

        $pegawaiData = [];
        $shift3_total = 0;
        $shift2_total = 0;
        $uml2_total = 0;
        $uml1_total = 0;
        foreach ($pegawai as $peg) {
            $pegawaiAbsenData = [];

            foreach ($absen as $absenData) {
                if ($absenData->no_reg === $peg->no_payroll) {
                    $tanggal = $absenData->tanggal;
                    if (!isset($pegawaiAbsenData[$tanggal])) {
                        $pegawaiAbsenData[$tanggal] = $absenData;
                    } else {
                        // Timpa jika timestamp data saat ini lebih besar
                        if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        }
                    }
                }
            }

            $pegawaiAbsen = collect($pegawaiAbsenData)
                ->sortBy('tanggal')
                ->values();

            // Tambahkan tanggal yang tidak ada dalam database ke dalam koleksi absen
            $missing_tanggal = array_diff($daftar_tanggal, $pegawaiAbsen->pluck('tanggal')->toArray());

            foreach ($missing_tanggal as $tanggal) {
                $tanggalObj = Carbon::parse($tanggal);

                $newAbsen = [
                    'tanggal' => $tanggalObj,
                    'masuk' => '',
                    'keluar' => '',
                    'lembur' => '',
                    'ket' => $tanggalObj->isSaturday() ? 'Sabtu' : ($tanggalObj->isSunday() ? 'Minggu' : ''),
                ];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $newAbsen['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $newAbsen['ket'] = 'on';
                    }
                }

                if (in_array($tanggal, $tglLibur)) {
                    $newAbsen['ket'] = 'LN';
                } else {
                    $newAbsen['ket'] = $newAbsen['ket'] ?: '';
                }

                $pegawaiAbsen->push($newAbsen);
            }

            // Urutkan kembali koleksi absen berdasarkan tanggal
            $pegawaiAbsen = $pegawaiAbsen->sortBy('tanggal');

            $jumlah_hari = 0;
            $uml1 = 0; // Jumlah hari biasa atau hari libur dengan kondisi on
            $uml2 = 0; // Jumlah hari biasa atau hari libur dengan kondisi off
            $shift2 = 0;
            $shift3 = 0;
            $total_lembur = 0; // Inisialisasi variabel total_lembur di awal

            foreach ($pegawaiAbsen as &$item) {
                $tanggal = $item['tanggal'];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $item['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $item['ket'] = 'on';
                    }
                } else {
                    if (in_array($tanggal, $tglLibur)) {
                        $item['ket'] = 'LN';
                    } else {
                        $item['ket'] = $item['ket'] ?: '';
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $jumlah_hari++;
                }

                $absenData = $absen_d
                    ->where('tgl_absen', $tanggal)
                    ->where('no_reg', $peg->no_payroll)
                    ->first();

                if ($absenData) {
                    $item['ket'] = $absenData->jns_absen;
                }

                $masuk = Carbon::parse(trim($item['masuk']));
                $keluar = Carbon::parse(trim($item['keluar']));

                $lemburData = $lembur
                    ->where('ot_dat', $tanggal)
                    ->where('no_payroll', $peg->no_payroll)
                    ->first();

                // Menghitung lembur
                if (($lemburData) && ($lemburData->ot_hrb != $lemburData->ot_hre)) {
                    $start = Carbon::parse($lemburData->ot_hrb);
                    $end = Carbon::parse($lemburData->ot_hre);

                    $startq = Carbon::parse($lemburData->ot_hrb)->format('H:i');
                    $endq = Carbon::parse($lemburData->ot_hre)->format('H:i');

                    $item['start'] = $startq;
                    $item['end'] = $endq;

                    $start_convert = $start->hour * 60 + $start->minute; // Konversi ke menit
                    $end_convert = $end->hour * 60 + $end->minute; // Konversi ke menit
                    $masuk_convert = $masuk->hour * 60 + $masuk->minute; // Konversi ke menit
                    $keluar_convert = $keluar->hour * 60 + $keluar->minute; // Konversi ke menit

                    if ($start_convert >= $end_convert) {
                        $end_convert = $end_convert + 1440;
                    } else {
                        $menit_lembur = $end_convert - $start_convert;
                    }
                    if ($masuk_convert >= $keluar_convert) {
                        $keluar_convert = $keluar_convert + 1440;
                    }

                    // periksa jam masuk dan jam mulai lembur
                    if ($masuk_convert >= $start_convert) {
                        $start = $masuk_convert; // Mulai hitung lembur dari waktu masuk
                    } else {
                        $start = $start_convert;
                    }

                    // Periksa apakah lembur berakhir setelah waktu keluar
                    if ($end_convert >= $keluar_convert) {
                        $end = $keluar_convert; // Akhiri hitungan lembur pada waktu keluar
                    } else {
                        $end = $end_convert;
                    }

                    $lembur_a = $end - $start;
                    $lemburHours = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value
                    $lembur_hours_asli = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value

                    $absen_tanggal = Carbon::parse($tanggal);

                    $lembur_asli = $lembur_hours_asli;

                    // Menghitung lembur berdasarkan kondisi
                    // dd($masuk_convert);
                    if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] >= $item['keluar'])) {
                        [$startHour, $startMinute] = explode(':', $item['start']);
                        [$endHour, $endMinute] = explode(':', $item['end']);

                        $startTimeInMinutes = $startHour * 60 + $startMinute;
                        $endTimeInMinutes = $endHour * 60 + $endMinute;

                        if ($endTimeInMinutes < $startTimeInMinutes) {
                            $lemburHours = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                        } else {
                            $lemburHours = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                        }
                        // dd($startTimeInMinutes);
                    }
                    if ($lemburHours > 5) {
                        $lemburHours -= 0.5;
                    } elseif (!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && !in_array($peg->bagian, ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA']) && (($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960)) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320)))) {
                        $lemburHours -= 0.5;
                    }

                    if (empty($item['masuk']) || empty($item['keluar'])) {
                        $lemburHours = 0;
                    } elseif (($absen_tanggal->isSaturday() && $item['ket'] == 'on') || ($absen_tanggal->isSunday() && $item['ket'] == 'on')) {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    } elseif ($item['ket'] == 'LN' || $item['ket'] == 'off' || ($absen_tanggal->isSaturday() || $absen_tanggal->isSunday())) {
                        $lemburHours *= 2;
                    } else {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    }

                    if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] >= $item['keluar'])) {
                        [$startHour, $startMinute] = explode(':', $item['start']);
                        [$endHour, $endMinute] = explode(':', $item['end']);

                        $startTimeInMinutes = $startHour * 60 + $startMinute;
                        $endTimeInMinutes = $endHour * 60 + $endMinute;

                        if ($endTimeInMinutes < $startTimeInMinutes) {
                            $lembur_hours_asli = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                        } else {
                            $lembur_hours_asli = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                        }
                        // dd($startTimeInMinutes);
                    }

                    if (empty($item['masuk']) || empty($item['keluar'])) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    } elseif ($lembur_hours_asli > 5) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    } elseif (!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && !in_array($peg->bagian, ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA']) && (($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960)) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320)))) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    }

                    // dd($lembur_hours_asli);

                    if ($lemburHours > 0) {
                        $item['lembur'] = $lembur_hours_asli;
                        $item['lembur_total'] = $lemburHours;
                        $total_lembur += $lemburHours;
                    }

                    // dd($total_lembur);
                }

                $tgl = Carbon::parse($tanggal);
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari kerja (tidak termasuk hari libur)
                if (($item['lembur'] >= 1 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null])) || $item['ket'] == 'on') {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if ($masuk_time->hour < 12 && $keluar_time->between(Carbon::parse('17:45'), Carbon::parse('18:30'))) {
                        // Periksa apakah hari ini bukan Sabtu atau Minggu
                        if (!$tgl->isWeekend()) {
                            $uml1++;
                            $uml1_total++;
                        }
                    }
                }

                // $lemburHours = 0; // inisialisasi variabel
                $golongan = trim($peg->golongan);

                // dd($tgl);

                // Hitung jumlah hari uml2
                if ($item['lembur'] >= 1 && $lembur_asli < 6 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null, '']) && (($tgl->isWeekend() || in_array($tgl->dayOfWeek, [0, 6])) && $item['ket'] != 'on')) {
                    $uml2++;
                    $uml2_total++;
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('18:00', '23:59') && $keluar_time->isBetween('05:00', '11:00')) || ($masuk_time->isBetween('13:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift3++;
                        $shift3_total++;
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('06:00', '17:00') && $keluar_time->isBetween('21:00', '23:59')) || ($masuk_time->isBetween('12:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift2++;
                        $shift2_total++;
                    }
                }
            }

            $daftar_tanggal_absen = $pegawaiAbsen->pluck('tanggal')->toArray();

            foreach ($tdkabsen as $tdk) {
                $tanggal = $tdk->ta_tgl;
                $status = $tdk->status;

                foreach ($pegawaiAbsen as &$item) {
                    $itemTanggal = Carbon::parse($item['tanggal']);

                    if ($itemTanggal->format('Y-m-d') === $tanggal) {
                        $item['ket'] = $status;
                        break;
                    }
                }
            }

            $pegawaiData[] = [
                'pegawai' => $peg,
                'absen' => $pegawaiAbsen,
                'jumlah_hari' => $jumlah_hari,
                'total_lembur' => $total_lembur,
                'uml1' => $uml1,
                'uml2' => $uml2,
                'shift2' => $shift2,
                'shift3' => $shift3,
            ];
        }

        return view('../hr/dashboard/reportabsen/rekapgaji', [
            'shift3' => $shift3,
            'shift2' => $shift2,
            'uml2' => $uml2,
            'uml1' => $uml1,
            'pegawaiData' => $pegawaiData,
            'tgl_awal' => $taw,
            'tgl_akhir' => $tak,
            'total_lembur' => $total_lembur,
        ]);
    }

    public function uraianlembur_print(Request $request)
    {
        set_time_limit(500);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);

        $taw = Carbon::parse($request->tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($request->tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $nik = $request->no_payroll;

        $pegawaiQuery = Pegawai::where(function ($query) {
            $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->orderBy('no_payroll', 'asc');

        if ($nik) {
            $pegawaiQuery->where('no_payroll', $nik);
        }

        $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

        $pegawaiData = [];

        // Mengumpulkan semua nomor registrasi pegawai
        $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

        // Mengambil data absen pegawai
        $absen = Presensi::whereIn('no_reg', $noRegistrasiPegawai)
            ->whereIn('tanggal', $daftar_tanggal)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mengumpulkan semua tanggal absen
        $absen_tanggal = $absen->pluck('tanggal')->toArray();

        // Mengumpulkan data onoff_tg berdasarkan tanggal
        $onoff_tg = onoff_tg::whereIn('tgl_off', $absen_tanggal)
            ->orWhereIn('tgl_on', $absen_tanggal)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model pertama tanpa absen_h
        $absen_d = absen_d::whereIn('tgl_absen', $absen_tanggal)
            ->whereIn('no_reg', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model kedua dengan absen_h
        // $absen_d = absen_d::join('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
        // ->whereIn('absen_ds.tgl_absen', $absen_tanggal)
        // ->get();

        // Mengumpulkan data tdkabsen berdasarkan nomor registrasi pegawai dan tanggal absen
        $tdkabsen = Tdkabsen::whereIn('no_payroll', $noRegistrasiPegawai)
            ->whereIn('ta_tgl', $absen_tanggal)
            ->get();

        // Mengumpulkan data lembur berdasarkan tanggal dan nomor registrasi pegawai
        $lembur = overtime::whereIn('ot_dat', $absen_tanggal)
            ->whereIn('no_payroll', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data tgl_libur berdasarkan tanggal
        $tglLibur = TglLibur::whereIn('tgl_libur', $absen_tanggal)
            ->pluck('tgl_libur')
            ->toArray();

        $pegawaiData = [];
        $shift3_total = 0;
        $shift2_total = 0;
        $uml2_total = 0;
        $uml1_total = 0;
        foreach ($pegawai as $peg) {
            $pegawaiAbsenData = [];

            foreach ($absen as $absenData) {
                if ($absenData->no_reg === $peg->no_payroll) {
                    $tanggal = $absenData->tanggal;
                    if (!isset($pegawaiAbsenData[$tanggal])) {
                        $pegawaiAbsenData[$tanggal] = $absenData;
                    } else {
                        // Timpa jika timestamp data saat ini lebih besar
                        if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        }
                    }
                }
            }

            $pegawaiAbsen = collect($pegawaiAbsenData)
                ->sortBy('tanggal')
                ->values();

            // Tambahkan tanggal yang tidak ada dalam database ke dalam koleksi absen
            $missing_tanggal = array_diff($daftar_tanggal, $pegawaiAbsen->pluck('tanggal')->toArray());

            foreach ($missing_tanggal as $tanggal) {
                $tanggalObj = Carbon::parse($tanggal);

                $newAbsen = [
                    'tanggal' => $tanggalObj,
                    'masuk' => '',
                    'keluar' => '',
                    'lembur' => '',
                    'ket' => $tanggalObj->isSaturday() ? 'Sabtu' : ($tanggalObj->isSunday() ? 'Minggu' : ''),
                ];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $newAbsen['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $newAbsen['ket'] = 'on';
                    }
                }

                if (in_array($tanggal, $tglLibur)) {
                    $newAbsen['ket'] = 'LN';
                } else {
                    $newAbsen['ket'] = $newAbsen['ket'] ?: '';
                }

                $pegawaiAbsen->push($newAbsen);
            }

            // Urutkan kembali koleksi absen berdasarkan tanggal
            $pegawaiAbsen = $pegawaiAbsen->sortBy('tanggal');

            $jumlah_hari = 0;
            $uml1 = 0; // Jumlah hari biasa atau hari libur dengan kondisi on
            $uml2 = 0; // Jumlah hari biasa atau hari libur dengan kondisi off
            $shift2 = 0;
            $shift3 = 0;
            $total_lembur = 0; // Inisialisasi variabel total_lembur di awal

            foreach ($pegawaiAbsen as &$item) {
                $tanggal = $item['tanggal'];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $item['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $item['ket'] = 'on';
                    }
                } else {
                    if (in_array($tanggal, $tglLibur)) {
                        $item['ket'] = 'LN';
                    } else {
                        $item['ket'] = $item['ket'] ?: '';
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $jumlah_hari++;
                }

                $absenData = $absen_d
                    ->where('tgl_absen', $tanggal)
                    ->where('no_reg', $peg->no_payroll)
                    ->first();

                if ($absenData) {
                    $item['ket'] = $absenData->jns_absen;
                }

                $masuk = Carbon::parse(trim($item['masuk']));
                $keluar = Carbon::parse(trim($item['keluar']));

                $lemburData = $lembur
                    ->where('ot_dat', $tanggal)
                    ->where('no_payroll', $peg->no_payroll)
                    ->first();

                // Menghitung lembur
                if ($lemburData) {
                    $start = Carbon::parse($lemburData->ot_hrb);
                    $end = Carbon::parse($lemburData->ot_hre);

                    $startq = Carbon::parse($lemburData->ot_hrb)->format('H:i');
                    $endq = Carbon::parse($lemburData->ot_hre)->format('H:i');

                    $item['start'] = $startq;
                    $item['end'] = $endq;

                    $start_convert = $start->hour * 60 + $start->minute; // Konversi ke menit
                    $end_convert = $end->hour * 60 + $end->minute; // Konversi ke menit
                    $masuk_convert = $masuk->hour * 60 + $masuk->minute; // Konversi ke menit
                    $keluar_convert = $keluar->hour * 60 + $keluar->minute; // Konversi ke menit

                    if ($start_convert >= $end_convert) {
                        $end_convert = $end_convert + 1440;
                    } else {
                        $menit_lembur = $end_convert - $start_convert;
                    }
                    if ($masuk_convert >= $keluar_convert) {
                        $keluar_convert = $keluar_convert + 1440;
                    }

                    // periksa jam masuk dan jam mulai lembur
                    if ($masuk_convert >= $start_convert) {
                        $start = $masuk_convert; // Mulai hitung lembur dari waktu masuk
                    } else {
                        $start = $start_convert;
                    }

                    // Periksa apakah lembur berakhir setelah waktu keluar
                    if ($end_convert >= $keluar_convert) {
                        $end = $keluar_convert; // Akhiri hitungan lembur pada waktu keluar
                    } else {
                        $end = $end_convert;
                    }

                    $lembur_a = $end - $start;
                    $lemburHours = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value
                    $lembur_hours_asli = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value

                    if ($lemburHours > 5) {
                        $lemburHours -= 0.5;
                    }
                    $lembur_asli = $lembur_hours_asli;

                    // Menghitung lembur berdasarkan kondisi
                    if ($item['ket'] == 'on' && (date('N', strtotime($tanggalObj)) == 6 || date('N', strtotime($tanggalObj)) == 7)) {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    } elseif ($item['ket'] == 'LN' || $item['ket'] == 'off') {
                        $lemburHours *= 2;
                    } else {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    }

                    $item['lembur'] = $lembur_hours_asli;
                    $item['lembur_total'] = $lemburHours;
                    $total_lembur += $lemburHours;
                }

                $tgl = Carbon::parse($tanggal);
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari kerja (tidak termasuk hari libur)
                if (($item['lembur'] >= 1 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ''])) || $item['ket'] == 'on') {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if ($masuk_time->hour < 12 && $keluar_time->between(Carbon::parse('17:45'), Carbon::parse('18:30'))) {
                        // Periksa apakah hari ini bukan Sabtu atau Minggu
                        if (!$tgl->isWeekend()) {
                            $uml1++;
                            $uml1_total++;
                        }
                    }
                }
                $golongan = trim($peg->golongan);
                // Hitung jumlah hari uml2
                if ($item['lembur'] >= 1 && $lembur_asli < 6 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null, '']) && (($tgl->isWeekend() || in_array($tgl->dayOfWeek, [0, 6])) && $item['ket'] != 'on')) {
                    $uml2++;
                    $uml2_total++;
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('18:00', '23:59') && $keluar_time->isBetween('05:00', '11:00')) || ($masuk_time->isBetween('13:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift3++;
                        $shift3_total++;
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('06:00', '17:00') && $keluar_time->isBetween('21:00', '23:59')) || ($masuk_time->isBetween('12:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift2++;
                        $shift2_total++;
                    }
                }
            }

            $daftar_tanggal_absen = $pegawaiAbsen->pluck('tanggal')->toArray();

            foreach ($tdkabsen as $tdk) {
                $tanggal = $tdk->ta_tgl;
                $status = $tdk->status;

                foreach ($pegawaiAbsen as &$item) {
                    $itemTanggal = Carbon::parse($item['tanggal']);

                    if ($itemTanggal->format('Y-m-d') === $tanggal) {
                        $item['ket'] = $status;
                        break;
                    }
                }
            }

            $pegawaiData[] = [
                'pegawai' => $peg,
                'absen' => $pegawaiAbsen,
                'jumlah_hari' => $jumlah_hari,
                'total_lembur' => $total_lembur,
                'uml1' => $uml1,
                'uml2' => $uml2,
                'shift2' => $shift2,
                'shift3' => $shift3,
            ];
        }

        $pdf = Pdf::loadview('/hr/dashboard/reportabsen/uraianlembur_print', [
            'shift3_total' => $shift3_total,
            'shift2_total' => $shift2_total,
            'uml2_total' => $uml2_total,
            'uml1_total' => $uml1_total,
            'pegawaiData' => $pegawaiData,
            'tgl_awal' => $taw,
            'tgl_akhir' => $tak,
        ]);
        return $pdf->setPaper('a4', 'potrait')->stream('uraianlembur.pdf');
    }

    public function rekapgaji_print(Request $request)
    {
        set_time_limit(500);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);

        $taw = Carbon::parse($request->tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($request->tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $nik = $request->no_payroll;

        $pegawaiQuery = Pegawai::where(function ($query) {
            $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->whereNotIn('jns_peg', ['SATPAM', 'KEBERSIHAN'])
            ->orderBy('no_payroll', 'asc');

        if ($nik) {
            $pegawaiQuery->where('no_payroll', $nik);
        }

        $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

        $pegawaiData = [];

        // Mengumpulkan semua nomor registrasi pegawai
        $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

        // Mengambil data absen pegawai
        $absen = Presensi::whereIn('no_reg', $noRegistrasiPegawai)
            ->whereIn('tanggal', $daftar_tanggal)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mengumpulkan semua tanggal absen
        $absen_tanggal = $absen->pluck('tanggal')->toArray();

        // Mengumpulkan data onoff_tg berdasarkan tanggal
        $onoff_tg = onoff_tg::whereIn('tgl_off', $absen_tanggal)
            ->orWhereIn('tgl_on', $absen_tanggal)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model pertama tanpa absen_h
        $absen_d = absen_d::whereIn('tgl_absen', $absen_tanggal)
            ->whereIn('no_reg', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model kedua dengan absen_h
        // $absen_d = absen_d::join('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
        // ->whereIn('absen_ds.tgl_absen', $absen_tanggal)
        // ->get();

        // Mengumpulkan data tdkabsen berdasarkan nomor registrasi pegawai dan tanggal absen
        $tdkabsen = Tdkabsen::whereIn('no_payroll', $noRegistrasiPegawai)
            ->whereIn('ta_tgl', $absen_tanggal)
            ->get();

        // Mengumpulkan data lembur berdasarkan tanggal dan nomor registrasi pegawai
        $lembur = overtime::whereIn('ot_dat', $absen_tanggal)
            ->whereIn('no_payroll', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data tgl_libur berdasarkan tanggal
        $tglLibur = TglLibur::whereIn('tgl_libur', $absen_tanggal)
            ->pluck('tgl_libur')
            ->toArray();

        $pegawaiData = [];
        $shift3_total = 0;
        $shift2_total = 0;
        $uml2_total = 0;
        $uml1_total = 0;
        foreach ($pegawai as $peg) {
            $pegawaiAbsenData = [];

            foreach ($absen as $absenData) {
                if ($absenData->no_reg === $peg->no_payroll) {
                    $tanggal = $absenData->tanggal;
                    if (!isset($pegawaiAbsenData[$tanggal])) {
                        $pegawaiAbsenData[$tanggal] = $absenData;
                    } else {
                        // Timpa jika timestamp data saat ini lebih besar
                        if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        }
                    }
                }
            }

            $pegawaiAbsen = collect($pegawaiAbsenData)
                ->sortBy('tanggal')
                ->values();

            // Tambahkan tanggal yang tidak ada dalam database ke dalam koleksi absen
            $missing_tanggal = array_diff($daftar_tanggal, $pegawaiAbsen->pluck('tanggal')->toArray());

            foreach ($missing_tanggal as $tanggal) {
                $tanggalObj = Carbon::parse($tanggal);

                $newAbsen = [
                    'tanggal' => $tanggalObj,
                    'masuk' => '',
                    'keluar' => '',
                    'lembur' => '',
                    'ket' => $tanggalObj->isSaturday() ? 'Sabtu' : ($tanggalObj->isSunday() ? 'Minggu' : ''),
                ];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $newAbsen['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $newAbsen['ket'] = 'on';
                    }
                }

                if (in_array($tanggal, $tglLibur)) {
                    $newAbsen['ket'] = 'LN';
                } else {
                    $newAbsen['ket'] = $newAbsen['ket'] ?: '';
                }

                $pegawaiAbsen->push($newAbsen);
            }

            // Urutkan kembali koleksi absen berdasarkan tanggal
            $pegawaiAbsen = $pegawaiAbsen->sortBy('tanggal');

            $jumlah_hari = 0;
            $uml1 = 0; // Jumlah hari biasa atau hari libur dengan kondisi on
            $uml2 = 0; // Jumlah hari biasa atau hari libur dengan kondisi off
            $shift2 = 0;
            $shift3 = 0;
            $total_lembur = 0; // Inisialisasi variabel total_lembur di awal

            foreach ($pegawaiAbsen as &$item) {
                $tanggal = $item['tanggal'];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $item['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $item['ket'] = 'on';
                    }
                } else {
                    if (in_array($tanggal, $tglLibur)) {
                        $item['ket'] = 'LN';
                    } else {
                        $item['ket'] = $item['ket'] ?: '';
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $jumlah_hari++;
                }

                $absenData = $absen_d
                    ->where('tgl_absen', $tanggal)
                    ->where('no_reg', $peg->no_payroll)
                    ->first();

                if ($absenData) {
                    $item['ket'] = $absenData->jns_absen;
                }

                $masuk = Carbon::parse(trim($item['masuk']));
                $keluar = Carbon::parse(trim($item['keluar']));

                $lemburData = $lembur
                    ->where('ot_dat', $tanggal)
                    ->where('no_payroll', $peg->no_payroll)
                    ->first();

                // Menghitung lembur
                if ($lemburData) {
                    $start = Carbon::parse($lemburData->ot_hrb);
                    $end = Carbon::parse($lemburData->ot_hre);

                    $start_convert = $start->hour * 60 + $start->minute; // Konversi ke menit
                    $end_convert = $end->hour * 60 + $end->minute; // Konversi ke menit
                    $masuk_convert = $masuk->hour * 60 + $masuk->minute; // Konversi ke menit
                    $keluar_convert = $keluar->hour * 60 + $keluar->minute; // Konversi ke menit

                    if ($start_convert >= $end_convert) {
                        $end_convert = $end_convert + 1440;
                    } else {
                        $menit_lembur = $end_convert - $start_convert;
                    }
                    if ($masuk_convert >= $keluar_convert) {
                        $keluar_convert = $keluar_convert + 1440;
                    }

                    // periksa jam masuk dan jam mulai lembur
                    if ($masuk_convert >= $start_convert) {
                        $start = $masuk_convert; // Mulai hitung lembur dari waktu masuk
                    } else {
                        $start = $start_convert;
                    }

                    // Periksa apakah lembur berakhir setelah waktu keluar
                    if ($end_convert >= $keluar_convert) {
                        $end = $keluar_convert; // Akhiri hitungan lembur pada waktu keluar
                    } else {
                        $end = $end_convert;
                    }

                    $lembur_a = $end - $start;
                    $lemburHours = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value
                    $lembur_hours_asli = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value

                    if ($lemburHours > 5) {
                        $lemburHours -= 0.5;
                    }
                    $lembur_asli = $lembur_hours_asli;

                    // Menghitung lembur berdasarkan kondisi
                    if ($item['ket'] == 'on' && (date('N', strtotime($tanggalObj)) == 6 || date('N', strtotime($tanggalObj)) == 7)) {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    } elseif ($item['ket'] == 'LN' || $item['ket'] == 'off') {
                        $lemburHours *= 2;
                    } else {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    }

                    $item['lembur'] = $lembur_asli;
                    $item['total_lembur'] = $lemburHours; // Menyimpan total lembur pada item absen
                    $total_lembur += $lemburHours;
                }

                $tgl = Carbon::parse($tanggal);
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari kerja (tidak termasuk hari libur)
                if (($item['lembur'] >= 1 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ''])) || $item['ket'] == 'on') {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if ($masuk_time->hour < 12 && $keluar_time->between(Carbon::parse('17:45'), Carbon::parse('18:30'))) {
                        // Periksa apakah hari ini bukan Sabtu atau Minggu
                        if (!$tgl->isWeekend()) {
                            $uml1++;
                            $uml1_total++;
                        }
                    }
                }
                $golongan = trim($peg->golongan);
                // Hitung jumlah hari uml2
                if ($item['lembur'] >= 1 && $lembur_asli < 6 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null, '']) && (($tgl->isWeekend() || in_array($tgl->dayOfWeek, [0, 6])) && $item['ket'] != 'on')) {
                    $uml2++;
                    $uml2_total++;
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('18:00', '23:59') && $keluar_time->isBetween('05:00', '11:00')) || ($masuk_time->isBetween('13:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift3++;
                        $shift3_total++;
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('06:00', '17:00') && $keluar_time->isBetween('21:00', '23:59')) || ($masuk_time->isBetween('12:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift2++;
                        $shift2_total++;
                    }
                }
            }

            $daftar_tanggal_absen = $pegawaiAbsen->pluck('tanggal')->toArray();

            foreach ($tdkabsen as $tdk) {
                $tanggal = $tdk->ta_tgl;
                $status = $tdk->status;

                foreach ($pegawaiAbsen as &$item) {
                    $itemTanggal = Carbon::parse($item['tanggal']);

                    if ($itemTanggal->format('Y-m-d') === $tanggal) {
                        $item['ket'] = $status;
                        break;
                    }
                }
            }

            $pegawaiData[] = [
                'pegawai' => $peg,
                'absen' => $pegawaiAbsen,
                'jumlah_hari' => $jumlah_hari,
                'total_lembur' => $total_lembur,
                'uml1' => $uml1,
                'uml2' => $uml2,
                'shift2' => $shift2,
                'shift3' => $shift3,
            ];
        }

        $pdf = Pdf::loadview('/hr/dashboard/reportabsen/rekapgaji_print', [
            'shift3_total' => $shift3_total,
            'shift2_total' => $shift2_total,
            'uml2_total' => $uml2_total,
            'uml1_total' => $uml1_total,
            'pegawaiData' => $pegawaiData,
            'tgl_awal' => $taw,
            'tgl_akhir' => $tak,
        ]);
        return $pdf->setPaper('a4', 'potrait')->stream('rekapgaji.pdf');
    }
    public function rekapgaji_excel(Request $request)
    {
        set_time_limit(500);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);

        $taw = Carbon::parse($request->tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($request->tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $nik = $request->no_payroll;

        $tgl_akhir_bulan_dari_tgl_awal = $tgl_awal->copy()->endOfMonth();

        $pegawaiQuery = Pegawai::where(function ($query) use ($tgl_akhir_bulan_dari_tgl_awal) {
            $query
                ->where(function ($subquery) use ($tgl_akhir_bulan_dari_tgl_awal) {
                    $subquery->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
                })
                ->orWhere(function ($subquery) use ($tgl_akhir_bulan_dari_tgl_awal) {
                    $subquery->where('tgl_keluar', '>', $tgl_akhir_bulan_dari_tgl_awal);
                });
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->whereNotIn('jns_peg', ['SATPAM', 'KEBERSIHAN'])
            ->orderBy('no_payroll', 'asc');

        if ($nik) {
            $pegawaiQuery->where('no_payroll', $nik);
        }

        $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

        $pegawaiData = [];

        // Mengumpulkan semua nomor registrasi pegawai
        $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

        // Mengambil data absen pegawai
        $absen = Presensi::whereIn('no_reg', $noRegistrasiPegawai)
            ->whereIn('tanggal', $daftar_tanggal)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mengumpulkan semua tanggal absen
        $absen_tanggal = $absen->pluck('tanggal')->toArray();

        // Mengumpulkan data onoff_tg berdasarkan tanggal
        $onoff_tg = onoff_tg::whereIn('tgl_off', $absen_tanggal)
            ->orWhereIn('tgl_on', $absen_tanggal)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model pertama tanpa absen_h
        $absen_d = absen_d::whereIn('tgl_absen', $absen_tanggal)
            ->whereIn('no_reg', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data absen_d berdasarkan tanggal dan nomor registrasi pegawai model kedua dengan absen_h
        // $absen_d = absen_d::join('absen_hs', 'absen_ds.int_absen', '=', 'absen_hs.int_absen')
        // ->whereIn('absen_ds.tgl_absen', $absen_tanggal)
        // ->get();

        // Mengumpulkan data tdkabsen berdasarkan nomor registrasi pegawai dan tanggal absen
        $tdkabsen = Tdkabsen::whereIn('no_payroll', $noRegistrasiPegawai)
            ->whereIn('ta_tgl', $absen_tanggal)
            ->get();

        // Mengumpulkan data lembur berdasarkan tanggal dan nomor registrasi pegawai
        $lembur = overtime::whereIn('ot_dat', $absen_tanggal)
            ->whereIn('no_payroll', $noRegistrasiPegawai)
            ->get();

        // Mengumpulkan data tgl_libur berdasarkan tanggal
        $tglLibur = TglLibur::whereIn('tgl_libur', $absen_tanggal)
            ->pluck('tgl_libur')
            ->toArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $shift3_total = 0;
        $shift2_total = 0;
        $uml2_total = 0;
        $uml1_total = 0;
        $pegawaiData = [];

        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'DATA REKAP GAJI');
        $sheet
            ->getStyle('A1')
            ->getFont()
            ->setBold(true);
        $sheet
            ->getStyle('A1')
            ->getFont()
            ->setSize(16);
        $sheet
            ->getStyle('A1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Periode: ' . $taw . ' - ' . $tak);
        $sheet
            ->getStyle('A2')
            ->getFont()
            ->setBold(true);
        $sheet
            ->getStyle('A2')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $columnHeaders = ['No', 'No Payroll', 'Nama Pegawai', 'Jumlah Hari', 'Total Lembur', 'UML1', 'UML2', 'Shift 2', 'Shift 3', 'Snack Shift 2'];

        // Set column headers
        $row = 3;
        $no = 1;
        $column = 1;
        foreach ($columnHeaders as $header) {
            $sheet->setCellValueByColumnAndRow($column, $row, $header);
            $sheet
                ->getStyleByColumnAndRow($column, $row)
                ->getFont()
                ->setBold(true);
            $column++;
        }

        // Increment row variable after setting headers
        $row++;

        foreach ($pegawai as $peg) {
            $pegawaiAbsenData = [];

            foreach ($absen as $absenData) {
                if ($absenData->no_reg === $peg->no_payroll) {
                    $tanggal = $absenData->tanggal;
                    if (!isset($pegawaiAbsenData[$tanggal])) {
                        $pegawaiAbsenData[$tanggal] = $absenData;
                    } else {
                        // Timpa jika timestamp data saat ini lebih besar
                        if ($absenData->timestamp > $pegawaiAbsenData[$tanggal]->timestamp) {
                            $pegawaiAbsenData[$tanggal] = $absenData;
                        }
                    }
                }
            }

            $pegawaiAbsen = collect($pegawaiAbsenData)
                ->sortBy('tanggal')
                ->values();

            // Tambahkan tanggal yang tidak ada dalam database ke dalam koleksi absen
            $missing_tanggal = array_diff($daftar_tanggal, $pegawaiAbsen->pluck('tanggal')->toArray());

            foreach ($missing_tanggal as $tanggal) {
                $tanggalObj = Carbon::parse($tanggal);

                $newAbsen = [
                    'tanggal' => $tanggalObj,
                    'masuk' => '',
                    'keluar' => '',
                    'lembur' => '',
                    'ket' => $tanggalObj->isSaturday() ? 'Sabtu' : ($tanggalObj->isSunday() ? 'Minggu' : ''),
                ];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $newAbsen['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $newAbsen['ket'] = 'on';
                    }
                }

                if (in_array($tanggal, $tglLibur)) {
                    $newAbsen['ket'] = 'LN';
                } else {
                    $newAbsen['ket'] = $newAbsen['ket'] ?: '';
                }

                $pegawaiAbsen->push($newAbsen);
            }

            // Urutkan kembali koleksi absen berdasarkan tanggal
            $pegawaiAbsen = $pegawaiAbsen->sortBy('tanggal');

            $jumlah_hari = 0;
            $uml1 = 0; // Jumlah hari biasa atau hari libur dengan kondisi on
            $uml2 = 0; // Jumlah hari biasa atau hari libur dengan kondisi off
            $shift2 = 0;
            $shift3 = 0;
            $total_lembur = 0; // Inisialisasi variabel total_lembur di awal

            // dd(  $pegawaiAbsen);
            foreach ($pegawaiAbsen as &$item) {
                $tanggal = $item['tanggal'];

                $onoff = $onoff_tg->firstWhere('tgl_off', $tanggal) ?? $onoff_tg->firstWhere('tgl_on', $tanggal);

                if ($onoff) {
                    if ($onoff->tgl_off == $tanggal) {
                        $item['ket'] = 'off';
                    } elseif ($onoff->tgl_on == $tanggal) {
                        $item['ket'] = 'on';
                    }
                } else {
                    if (in_array($tanggal, $tglLibur)) {
                        $item['ket'] = 'LN';
                    } else {
                        $item['ket'] = $item['ket'] ?: '';
                    }
                }

                $golongan = trim($peg->golongan);
                if (!empty($item['masuk']) && !empty($item['keluar']) && (in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', '']) || is_null($peg->golongan)) && $peg->no_payroll < 570) {
                    $jumlah_hari++;
                }

                $absenData = $absen_d
                    ->where('tgl_absen', $tanggal)
                    ->where('no_reg', $peg->no_payroll)
                    ->first();

                if ($absenData) {
                    $item['ket'] = $absenData->jns_absen;
                }

                $masuk = Carbon::parse(trim($item['masuk']));
                $keluar = Carbon::parse(trim($item['keluar']));

                $lemburData = $lembur
                    ->where('ot_dat', $tanggal)
                    ->where('no_payroll', $peg->no_payroll)
                    ->first();

                // Menghitung lembur
                if ($lemburData) {
                    $start = Carbon::parse($lemburData->ot_hrb);
                    $end = Carbon::parse($lemburData->ot_hre);
                    $startq = $start->format('H:i');
                    $endq = $end->format('H:i');

                    $item['start'] = $startq;
                    $item['end'] = $endq;

                    $start_convert = $start->hour * 60 + $start->minute; // Konversi ke menit
                    $end_convert = $end->hour * 60 + $end->minute; // Konversi ke menit
                    $masuk_convert = $masuk->hour * 60 + $masuk->minute; // Konversi ke menit
                    $keluar_convert = $keluar->hour * 60 + $keluar->minute; // Konversi ke menit

                    if ($start_convert >= $end_convert) {
                        $end_convert = $end_convert + 1440;
                    } else {
                        $menit_lembur = $end_convert - $start_convert;
                    }
                    if ($masuk_convert >= $keluar_convert) {
                        $keluar_convert = $keluar_convert + 1440;
                    }

                    // periksa jam masuk dan jam mulai lembur
                    if ($masuk_convert >= $start_convert) {
                        $start = $masuk_convert; // Mulai hitung lembur dari waktu masuk
                    } else {
                        $start = $start_convert;
                    }

                    // Periksa apakah lembur berakhir setelah waktu keluar
                    if ($end_convert >= $keluar_convert) {
                        $end = $keluar_convert; // Akhiri hitungan lembur pada waktu keluar
                    } else {
                        $end = $end_convert;
                    }

                    $lembur_a = $end - $start;
                    $lemburHours = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value
                    $lembur_hours_asli = round(($lembur_a / 60) * 2) / 2; // Convert minutes to hours and round to the nearest 0.5 value

                    $absen_tanggal = Carbon::parse($tanggal);

                    $lembur_asli = $lembur_hours_asli;

                    // Menghitung lembur berdasarkan kondisi
                    // dd($masuk_convert);
                    if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] != $item['keluar']) ) {
                        [$startHour, $startMinute] = explode(':', $item['start']);
                        [$endHour, $endMinute] = explode(':', $item['end']);

                        $startTimeInMinutes = $startHour * 60 + $startMinute;
                        $endTimeInMinutes = $endHour * 60 + $endMinute;

                        if ($endTimeInMinutes < $startTimeInMinutes) {
                            $lemburHours = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                        } else {
                            $lemburHours = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                        }
                        // dd($startTimeInMinutes);
                    }
                    if ($lemburHours > 5) {
                        $lemburHours -= 0.5;
                    } elseif ((!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && $peg->bagian != ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA'] && ($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960))) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320))) {
                        $lemburHours -= 0.5;
                    }

                    if (empty($item['masuk']) || empty($item['keluar'])) {
                        $lemburHours = 0;
                    } elseif (($absen_tanggal->isSaturday() && $item['ket'] == 'on') || ($absen_tanggal->isSunday() && $item['ket'] == 'on')) {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    } elseif ($item['ket'] == 'LN' || $item['ket'] == 'off' || ($absen_tanggal->isSaturday() || $absen_tanggal->isSunday())) {
                        $lemburHours *= 2;
                    } else {
                        $lemburHours *= 2;
                        $lemburHours -= 0.5;
                    }

                    if (($item['masuk'] >= $item['keluar']) && ($item['masuk'] != $item['keluar'])) {
                        [$startHour, $startMinute] = explode(':', $item['start']);
                        [$endHour, $endMinute] = explode(':', $item['end']);

                        $startTimeInMinutes = $startHour * 60 + $startMinute;
                        $endTimeInMinutes = $endHour * 60 + $endMinute;

                        if ($endTimeInMinutes < $startTimeInMinutes) {
                            $lembur_hours_asli = ($endTimeInMinutes + 1440 - $startTimeInMinutes) / 60;
                        } else {
                            $lembur_hours_asli = ($endTimeInMinutes - $startTimeInMinutes) / 60;
                        }
                        // dd($startTimeInMinutes);
                    }

                    if (empty($item['masuk']) || empty($item['keluar'])) {
                        $lembur_hours_asli = '.'; // Kurangi setengah jam
                    } elseif ($lembur_hours_asli > 5) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    } elseif ((!$absen_tanggal->isSaturday() && !$absen_tanggal->isSunday() && ($peg->bagian != ['ACCOUNTING', 'MARKETING', 'KEUANGAN', 'EDP', 'EXPEDISI', 'HR_OFFICER', 'GA', 'PERSONALIA']) & ($start_convert >= 480 && $start_convert <= 720 && ($end_convert >= 780 && $end_convert <= 960))) || ($start_convert >= 930 && $start_convert <= 1050 && ($end_convert >= 1110 && $end_convert <= 1320))) {
                        $lembur_hours_asli -= 0.5; // Kurangi setengah jam
                    }

                    if ($lemburHours > 0) {
                        $item['lembur'] = $lembur_hours_asli;
                        $item['lembur_total'] = $lemburHours;
                        $total_lembur += $lemburHours;
                    }
                }
                $tgl = Carbon::parse($tanggal);
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari kerja (tidak termasuk hari libur)
                if (($item['lembur'] >= 1 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ''])) || $item['ket'] == 'on') {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if ($masuk_time->hour < 12 && $keluar_time->between(Carbon::parse('17:45'), Carbon::parse('18:30'))) {
                        // Periksa apakah hari ini bukan Sabtu atau Minggu
                        if (!$tgl->isWeekend()) {
                            $uml1++;
                            $uml1_total++;
                        }
                    }
                }

                // Hitung jumlah hari uml2
                $golongan = trim($peg->golongan);

                // Hitung jumlah hari uml2
                if ($item['lembur'] >= 1 && $lembur_asli < 6 && in_array($golongan, ['A', 'B', 'C', 'C1', 'C2', 'D', 'E', ' ', null, '']) && (($tgl->isWeekend() || in_array($tgl->dayOfWeek, [0, 6])) && $item['ket'] != 'on')) {
                    $uml2++;
                    $uml2_total++;
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('18:00', '23:59') && $keluar_time->isBetween('05:00', '11:00')) || ($masuk_time->isBetween('13:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift3++;
                        $shift3_total++;
                    }
                }

                if (!empty($item['masuk']) && !empty($item['keluar'])) {
                    $masuk_time = Carbon::parse($item['masuk']);
                    $keluar_time = Carbon::parse($item['keluar']);

                    if (($masuk_time->isBetween('06:00', '17:00') && $keluar_time->isBetween('21:00', '23:59')) || ($masuk_time->isBetween('12:00', '17:00') && $keluar_time->isBetween('05:00', '11:00'))) {
                        $shift2++;
                        $shift2_total++;
                    }
                }
            }

            $daftar_tanggal_absen = $pegawaiAbsen->pluck('tanggal')->toArray();

            foreach ($tdkabsen as $tdk) {
                $tanggal = $tdk->ta_tgl;
                $status = $tdk->status;

                foreach ($pegawaiAbsen as &$item) {
                    $itemTanggal = Carbon::parse($item['tanggal']);

                    if ($itemTanggal->format('Y-m-d') === $tanggal) {
                        $item['ket'] = $status;
                        break;
                    }
                }
            }
            // dd($pegawaiAbsen);
            $pegawaiData[] = [
                'pegawai' => $peg,
                'absen' => $pegawaiAbsen,
                'jumlah_hari' => $jumlah_hari,
                'total_lembur' => $total_lembur,
                'uml1' => $uml1,
                'uml2' => $uml2,
                'shift2' => $shift2,
                'shift3' => $shift3,
                'snack_shift2' => $shift2,
            ];

            // Set data pada sheet Excel
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $peg->no_payroll);
            $sheet->setCellValue('C' . $row, $peg->nama_asli);
            $sheet->setCellValue('D' . $row, $jumlah_hari);
            $sheet->setCellValue('E' . $row, $total_lembur);
            $sheet->setCellValue('F' . $row, $uml1);
            $sheet->setCellValue('G' . $row, $uml2);
            $sheet->setCellValue('H' . $row, $shift2);
            $sheet->setCellValue('I' . $row, $shift3);
            $sheet->setCellValue('J' . $row, $shift2);

            // Loop melalui semua kolom dan setAutoSize(true)
            $highestColumn = $sheet->getHighestColumn();
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Menambahkan garis tepi pada seluruh tabel
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'], // Warna garis dalam format RGB (hitam)
                    ],
                ],
            ];

            // Menggunakan "range" untuk mendapatkan rentang seluruh tabel
            $range = 'A1:' . $highestColumn . $sheet->getHighestRow();
            $sheet->getStyle($range)->applyFromArray($styleArray);

            // Mengatur alignment (penyelarasan) kolom ke tengah
            $alignment = $sheet->getStyle('A1:' . $highestColumn . $sheet->getHighestRow())->getAlignment();
            $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Mengatur alignment (penyelarasan) kolom 'C' ke kiri
            $alignmentC = $sheet->getStyle('C1:C' . $sheet->getHighestRow())->getAlignment();
            $alignmentC->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $row++;
            $no++;
        }

        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $periode = $taw . ' to ' . $tak; // Assuming $aw and $tak contain the period values

        $sheet
            ->getStyle('A1:' . $lastColumn . '3')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);

        // Add the current date and time to the file name
        $currentDateTime = date('Y-m-d_H-i-s'); // Format: YYYY-MM-DD_HH-mm-ss
        $filename = 'rekapgaji_' . $periode . '_' . $currentDateTime . '.xlsx'; // Include the period and current date/time in the file name

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit();
    }
}
