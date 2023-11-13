<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\Presensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class hrReportLkkController extends Controller
{
    public function index()
    {
        return view('../hr/dashboard/reportlkk/index');
    }

    public function list(Request $request)
    {
        set_time_limit(500);
        $tanggal = $request->input('tanggal');
        $shift = $request->input('shift');
        $jenis = $request->input('jenis');

        if ($shift == 1) {
            $masuk = ['04:00', '12:00'];
            $keluar = ['14:00', '20:00'];
        } elseif ($shift == 2) {
            $masuk = ['14:00', '20:00'];
            $keluar = ['18:30', '23:59'];
        } elseif ($shift == 3) {
            $masuk = ['27:00', '67:59'];
            $keluar = ['06:00', '08:30'];
        }

        if ($jenis == 'rekap') {
            $peg = pegawai::orderBy('no_payroll', 'desc')
                ->where('jns_peg', '!=', 'SATPAM')
                ->where('jns_peg', '!=', 'KEBERSIHAN')
                ->whereNull('tgl_keluar')
                ->pluck('no_payroll');

            $data = Presensi::whereIn('presensis.no_reg', $peg)
                ->join('pegawais', 'presensis.no_reg', '=', 'pegawais.no_payroll')
                ->where(function ($query) use ($masuk, $keluar) {
                    $query->whereBetween('masuk', $masuk)->orWhereBetween('keluar', $keluar);
                })
                ->where('tanggal', $tanggal)
                ->select('pegawais.bagian', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('pegawais.bagian')
                ->get();
            $total = $data->sum('jumlah');

            return view('../hr/dashboard/reportlkk/list', compact('tanggal', 'data', 'jenis', 'shift', 'total'));
        } else {
            $peg = pegawai::orderBy('no_payroll', 'desc')
                ->where('jns_peg', '!=', 'SATPAM')
                ->where('jns_peg', '!=', 'KEBERSIHAN')
                ->whereNull('tgl_keluar')
                ->pluck('no_payroll');

            $data = Presensi::whereIn('presensis.no_reg', $peg)
                ->join('pegawais', 'presensis.no_reg', '=', 'pegawais.no_payroll')
                ->where(function ($query) use ($masuk, $keluar) {
                    $query->whereBetween('masuk', $masuk)->orWhereBetween('keluar', $keluar);
                })
                ->where('tanggal', $tanggal)
                ->orderBy('bagian')
                ->get();

            return view('../hr/dashboard/reportlkk/listdetail', compact('tanggal', 'data', 'jenis', 'shift'));
        }
    }
    public function print(Request $request)
    {
        set_time_limit(500);
        $tanggal = $request->input('tanggal');
        $shift = $request->input('shift');
        $jenis = $request->input('jenis');

        if ($shift == 1) {
            $masuk = ['04:00', '12:00'];
            $keluar = ['14:00', '20:00'];
        } elseif ($shift == 2) {
            $masuk = ['14:00', '20:00'];
            $keluar = ['18:30', '23:59'];
        } elseif ($shift == 3) {
            $masuk = ['27:00', '67:59'];
            $keluar = ['06:00', '08:30'];
        }

        if ($jenis == 'rekap') {
            $peg = pegawai::orderBy('no_payroll', 'desc')
                ->where('jns_peg', '!=', 'SATPAM')
                ->where('jns_peg', '!=', 'KEBERSIHAN')
                ->whereNull('tgl_keluar')
                ->pluck('no_payroll');

            $data = Presensi::whereIn('presensis.no_reg', $peg)
                ->join('pegawais', 'presensis.no_reg', '=', 'pegawais.no_payroll')
                ->where(function ($query) use ($masuk, $keluar) {
                    $query->whereBetween('masuk', $masuk)->orWhereBetween('keluar', $keluar);
                })
                ->where('tanggal', $tanggal)
                ->select('pegawais.bagian', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('pegawais.bagian')
                ->get();
            $total = $data->sum('jumlah');

            $pdf = Pdf::loadview('../hr/dashboard/reportlkk/print', compact('tanggal', 'data', 'jenis', 'shift', 'total'));
            return $pdf->setPaper('a4', 'portrait')->stream('laporanlkk.pdf');
        } else {
            $peg = pegawai::orderBy('no_payroll', 'desc')
                ->where('jns_peg', '!=', 'SATPAM')
                ->where('jns_peg', '!=', 'KEBERSIHAN')
                ->whereNull('tgl_keluar')
                ->pluck('no_payroll');

            $data = Presensi::whereIn('presensis.no_reg', $peg)
                ->join('pegawais', 'presensis.no_reg', '=', 'pegawais.no_payroll')
                ->where(function ($query) use ($masuk, $keluar) {
                    $query->whereBetween('masuk', $masuk)->orWhereBetween('keluar', $keluar);
                })
                ->where('tanggal', $tanggal)
                ->orderBy('bagian')
                ->get();

            $pdf = Pdf::loadview('../hr/dashboard/reportlkk/printdetail', compact('tanggal', 'data', 'jenis', 'shift'));
            return $pdf->setPaper('a4', 'portrait')->stream('laporanlkkdetail.pdf');
        }
    }
}