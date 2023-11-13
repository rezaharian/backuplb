<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\Presensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class hrReportIpaMdtController extends Controller
{
    public function index()
    {
        return view('../hr/dashboard/reportipamdt/index');
    }

    public function list(Request $request)
    {
        set_time_limit(500);
        $noPayroll = $request->input('no_payroll');
        $peg = pegawai::where('no_payroll', $noPayroll)->first();
        // MENGATUR WAKTU AWAL SMAPAI AKHIR DARI REQUEST SESUAI TAHUN
        $tgl_awal = $request->input('awal');
        $tgl_akhir = $request->input('akhir');
        $taw = Carbon::parse($tgl_awal)->format('Y-m-d');
        $tak = Carbon::parse($tgl_akhir)->format('Y-m-d');
        $absenDataL = Presensi::selectRaw(
            '*,
        CASE
            WHEN masuk IS NULL THEN "MDT"
            WHEN masuk > norm_m THEN "MDT"
            WHEN keluar IS NULL THEN "IPA"
            WHEN keluar < norm_k THEN "IPA"
            ELSE "Keterangan Lainnya"
        END as keterangan,
        GREATEST(ROUND((CASE
            WHEN masuk IS NOT NULL AND norm_m IS NOT NULL THEN (TIME_TO_SEC(masuk) - TIME_TO_SEC(norm_m)) / 60
            ELSE 0
        END)), 0) as mnt_ipa,
        GREATEST(ROUND((CASE
            WHEN keluar IS NOT NULL AND norm_k IS NOT NULL THEN (TIME_TO_SEC(norm_k) - TIME_TO_SEC(keluar)) / 60
            ELSE 0
        END)), 0) as mnt_dt',
        )
            ->whereBetween('tanggal', [$taw, $tak])
            ->where('no_reg', $noPayroll)
            ->get()
            ->filter(function ($presensi) {
                return ($presensi->keterangan === 'IPA' && $presensi->mnt_ipa !== null) || ($presensi->keterangan === 'MDT' && $presensi->mnt_dt !== null);
            });

        $jumlah_mnt_ipa = $absenDataL->sum('mnt_ipa');
        $jumlah_mnt_dt = $absenDataL->sum('mnt_dt');
        $jumlah_hari_dt = $absenDataL->where('keterangan', 'IPA')->count();
        $jumlah_hari_ipa = $absenDataL->where('keterangan', 'MDT')->count();

        // dd($absenDataL);

        return view('../hr/dashboard/reportipamdt/list', compact('tgl_awal', 'tgl_akhir', 'absenDataL', 'peg', 'jumlah_mnt_ipa', 'jumlah_mnt_dt', 'jumlah_hari_ipa', 'jumlah_hari_dt'));
    }
    public function print(Request $request)
    {
        set_time_limit(500);
        $noPayroll = $request->input('no_payroll');
        $peg = pegawai::where('no_payroll', $noPayroll)->first();
        // MENGATUR WAKTU AWAL SMAPAI AKHIR DARI REQUEST SESUAI TAHUN
        $tgl_awal = $request->input('awal');
        $tgl_akhir = $request->input('akhir');
        $taw = Carbon::parse($tgl_awal)->format('Y-m-d');
        $tak = Carbon::parse($tgl_akhir)->format('Y-m-d');
        $absenDataL = Presensi::selectRaw(
            '*,
        CASE
            WHEN masuk IS NULL THEN "MDT"
            WHEN masuk > norm_m THEN "MDT"
            WHEN keluar IS NULL THEN "IPA"
            WHEN keluar < norm_k THEN "IPA"
            ELSE "Keterangan Lainnya"
        END as keterangan,
        GREATEST(ROUND((CASE
            WHEN masuk IS NOT NULL AND norm_m IS NOT NULL THEN (TIME_TO_SEC(masuk) - TIME_TO_SEC(norm_m)) / 60
            ELSE 0
        END)), 0) as mnt_ipa,
        GREATEST(ROUND((CASE
            WHEN keluar IS NOT NULL AND norm_k IS NOT NULL THEN (TIME_TO_SEC(norm_k) - TIME_TO_SEC(keluar)) / 60
            ELSE 0
        END)), 0) as mnt_dt',
        )
            ->whereBetween('tanggal', [$taw, $tak])
            ->where('no_reg', $noPayroll)
            ->get()
            ->filter(function ($presensi) {
                return ($presensi->keterangan === 'IPA' && $presensi->mnt_ipa !== null) || ($presensi->keterangan === 'MDT' && $presensi->mnt_dt !== null);
            });

        $jumlah_mnt_ipa = $absenDataL->sum('mnt_ipa');
        $jumlah_mnt_dt = $absenDataL->sum('mnt_dt');
        $jumlah_hari_dt = $absenDataL->where('keterangan', 'IPA')->count();
        $jumlah_hari_ipa = $absenDataL->where('keterangan', 'MDT')->count();

        $pdf = Pdf::loadview('../hr/dashboard/reportipamdt/print', compact('tgl_awal', 'tgl_akhir', 'absenDataL', 'peg', 'jumlah_mnt_ipa', 'jumlah_mnt_dt', 'jumlah_hari_ipa', 'jumlah_hari_dt'));
        return $pdf->setPaper('a4', 'potrait')->stream('laporanIpaMdt.pdf');
    }
}
