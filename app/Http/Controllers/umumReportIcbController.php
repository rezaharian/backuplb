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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class umumReportIcbController extends Controller
{
    public function index()
    {
        $tahun_sekarang = Carbon::now()->year;
        $tahun = [];

        for ($i = 0; $i < 14; $i++) {
            $tahun[] = $tahun_sekarang - $i;
        }

        return view('../umum/dashboard/report/reporticb/index', compact('tahun'));
    }

    public function list(Request $request)
    {
        set_time_limit(500);
        $tahun = $request->input('tahun');
        $noPayroll = $request->input('no_payroll');
        $absen = absen_h::where('no_payroll', $noPayroll)->first();
        $peg = pegawai::where('no_payroll', $noPayroll)->first();
        // MENGATUR WAKTU AWAL SMAPAI AKHIR DARI REQUEST SESUAI TAHUN
        $tgl_awal = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
        $tgl_akhir = Carbon::createFromDate($tahun, 12, 31)->endOfYear();
        $taw = Carbon::parse($tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $pegawaiQuery = pegawai::where(function ($query) {
            $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->orderBy('no_payroll', 'asc');

        if ($noPayroll) {
            $pegawaiQuery->where('no_payroll', $noPayroll);
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

        $pegawaiData = [];

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

            // CARI YG ADA KET NYA
            $absenDataL = absen_d::whereIn('tgl_absen', $daftar_tanggal)
                ->join('absen_hs', 'absen_hs.int_absen', '=', 'absen_ds.int_absen')
                ->where('absen_hs.no_payroll', $noPayroll)
                ->whereIn('jns_absen', ['IPC', 'ITU', 'ICB', 'IC'])
                ->select('tgl_absen', 'jns_absen')
                ->get()
                ->toArray();

            // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // CARI MANGKIRNYA
            $tgl_ket = absen_d::whereIn('tgl_absen', $daftar_tanggal)
                ->join('absen_hs', 'absen_hs.int_absen', '=', 'absen_ds.int_absen')
                ->where('absen_hs.no_payroll', $noPayroll)
                ->whereNotNull('jns_absen')
                ->pluck('tgl_absen')
                ->toArray();

            $tgl_lbr = TglLibur::whereIn('tgl_libur', $daftar_tanggal)
                ->pluck('tgl_libur')
                ->toArray();


                
                $onof = onoff_tg::whereIn('tgl_off', $missing_tanggal)
                ->pluck('tgl_off')
                ->toArray();

            $today = date('Y-m-d'); // Mendapatkan tanggal hari ini
            $tanggal_tidak_masuk = [];

            foreach ($missing_tanggal as $tgl) {
                // Periksa apakah tanggal bukan hari Sabtu, Minggu, dan kurang dari atau sama dengan hari ini
                if (date('N', strtotime($tgl)) != 6 && date('N', strtotime($tgl)) != 7 && strtotime($tgl) <= strtotime($today)) {
                    // Periksa apakah tanggal bukan tanggal libur ($tgl_ket dan $tgl_lbr)
                    if (!in_array($tgl, $tgl_ket) && !in_array($tgl, $tgl_lbr) && !in_array($tgl, $onof)) {
                        // Tambahkan tanggal ke array $tanggal_tidak_masuk dengan label "Tidak Masuk"
                        $tanggal_tidak_masuk[] = [
                            'tgl_absen' => $tgl,
                            'jns_absen' => 'Mangkir',
                        ];
                    }
                }
            }

            // GABUNGIN YG ADA KET NYA DAN YG MANGKIR
            $gabungData = array_merge($absenDataL, $tanggal_tidak_masuk);
            usort($gabungData, function($a, $b) {
                return strtotime($a['tgl_absen']) - strtotime($b['tgl_absen']);
            });

            // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }

        return view('../umum/dashboard/report/reporticb/list', compact('gabungData', 'tahun','peg'));
    }
    public function print(Request $request)
    {
        set_time_limit(500);
        $tahun = $request->input('tahun');
        $noPayroll = $request->input('no_payroll');
        $absen = absen_h::where('no_payroll', $noPayroll)->first();
        $peg = pegawai::where('no_payroll', $noPayroll)->first();
        // MENGATUR WAKTU AWAL SMAPAI AKHIR DARI REQUEST SESUAI TAHUN
        $tgl_awal = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
        $tgl_akhir = Carbon::createFromDate($tahun, 12, 31)->endOfYear();
        $taw = Carbon::parse($tgl_awal)->format('d-m-Y');
        $tak = Carbon::parse($tgl_akhir)->format('d-m-Y');

        $daftar_tanggal = [];
        $jumlah_hari = $tgl_akhir->diffInDays($tgl_awal);

        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $daftar_tanggal[] = $tgl_awal
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');
        }

        $pegawaiQuery = pegawai::where(function ($query) {
            $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
        })
            ->where('bagian', '!=', 'DIREKSI')
            ->orderBy('no_payroll', 'asc');

        if ($noPayroll) {
            $pegawaiQuery->where('no_payroll', $noPayroll);
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

        $pegawaiData = [];

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

            // CARI YG ADA KET NYA
            $absenDataL = absen_d::whereIn('tgl_absen', $daftar_tanggal)
                ->join('absen_hs', 'absen_hs.int_absen', '=', 'absen_ds.int_absen')
                ->where('absen_hs.no_payroll', $noPayroll)
                ->whereIn('jns_absen', ['IPC', 'ITU', 'ICB', 'IC'])
                ->select('tgl_absen', 'jns_absen')
                ->get()
                ->toArray();

            // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // CARI MANGKIRNYA
            $tgl_ket = absen_d::whereIn('tgl_absen', $daftar_tanggal)
                ->join('absen_hs', 'absen_hs.int_absen', '=', 'absen_ds.int_absen')
                ->where('absen_hs.no_payroll', $noPayroll)
                ->whereNotNull('jns_absen')
                ->pluck('tgl_absen')
                ->toArray();

            $tgl_lbr = TglLibur::whereIn('tgl_libur', $daftar_tanggal)
                ->pluck('tgl_libur')
                ->toArray();

                
                $onof = onoff_tg::whereIn('tgl_off', $missing_tanggal)
                ->pluck('tgl_off')
                ->toArray();

            $today = date('Y-m-d'); // Mendapatkan tanggal hari ini
            $tanggal_tidak_masuk = [];

            foreach ($missing_tanggal as $tgl) {
                // Periksa apakah tanggal bukan hari Sabtu, Minggu, dan kurang dari atau sama dengan hari ini
                if (date('N', strtotime($tgl)) != 6 && date('N', strtotime($tgl)) != 7 && strtotime($tgl) <= strtotime($today)) {
                    // Periksa apakah tanggal bukan tanggal libur ($tgl_ket dan $tgl_lbr)
                    if (!in_array($tgl, $tgl_ket) && !in_array($tgl, $tgl_lbr) && !in_array($tgl, $onof)) {
                        // Tambahkan tanggal ke array $tanggal_tidak_masuk dengan label "Tidak Masuk"
                        $tanggal_tidak_masuk[] = [
                            'tgl_absen' => $tgl,
                            'jns_absen' => 'Mangkir',
                        ];
                    }
                }
            }

            // GABUNGIN YG ADA KET NYA DAN YG MANGKIR
            $gabungData = array_merge($absenDataL, $tanggal_tidak_masuk);
            usort($gabungData, function($a, $b) {
                return strtotime($a['tgl_absen']) - strtotime($b['tgl_absen']);
            });

            // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }

        $pdf = Pdf::loadview('../umum/dashboard/report/reporticb/print', compact('gabungData', 'tahun','peg'));
        return $pdf->setPaper('a4', 'potrait')->stream('laporanICB.pdf');

    }


}
