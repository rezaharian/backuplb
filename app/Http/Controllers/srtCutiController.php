<?php

namespace App\Http\Controllers;

use App\Models\absen_d;
use App\Models\absen_h;
use App\Models\ct_besar;
use App\Models\onoff_tg;
use App\Models\pegawai;
use App\Models\Presensi;
use App\Models\Pt_gaji;
use App\Models\srtcuti;
use App\Models\TglLibur;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class srtCutiController extends Controller
{
    public function list()
    {
        $sc = srtcuti::orderBy('ct_tgl', 'desc')
            ->limit(200)
            ->get();
        return view('../hr/dashboard/srtcuti/index', compact('sc'));
    }

    public function create()
    {
        $tahun_sekarang = Carbon::now()->format('y');
        $bulan_sekarang = Carbon::now()->format('m');

        $tanggal_terakhir = DB::table('srtcutis')
            ->orderBy('id', 'desc')
            ->pluck('created_at')
            ->first();

        $tahun_terakhir = Carbon::parse($tanggal_terakhir)->format('y');
        $bulan_terakhir = Carbon::parse($tanggal_terakhir)->format('m');

        if ($tahun_sekarang != $tahun_terakhir || $bulan_sekarang != $bulan_terakhir) {
            $nomor_terakhir = 1;
        } else {
            $nomor_terakhir =
                (int) substr(
                    DB::table('srtcutis')
                        ->orderBy('id', 'desc')
                        ->pluck('ct_nom')
                        ->first(),
                    -3,
                ) + 1;
        }

        $nomor_format = sprintf('%03d', $nomor_terakhir);
        $kode_unik = $tahun_sekarang . $bulan_sekarang . $nomor_format;

        return view('../hr/dashboard/srtcuti/create', compact('kode_unik'));
    }

    public function store(Request $request)
    {
        $ct_tgl = $request->ct_tgl;
        $noPayroll = $request->ct_reg;
        $pegawai = pegawai::where('no_payroll', $noPayroll)->first();
        $absen = absen_h::where('no_payroll', $noPayroll)->first();

        $timestamp = strtotime($ct_tgl);
        $tahun = date('Y', $timestamp);
        $bulanAkhir = date('m', $timestamp);
        $bulanAwal = 1;

        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $rangeBulan = [];

        for ($i = $bulanAwal; $i <= $bulanAkhir; $i++) {
            $rangeBulan[] = $bulanIndonesia[$i];
        }

        // dd($rangeBulan);

        // -----------------------------------------------------------------------------------------------------------------------------------------------------------
        $peg = pegawai::where('no_payroll', $noPayroll)->first();

        $absen_d_query = absen_d::where('int_peg', $absen->int_peg)
            ->where('thn_absen', $tahun)
            ->whereIn('bln_absen', $rangeBulan);

        $IC = $absen_d_query->where('jns_absen', 'IC')->count();
        $H = $absen_d_query->whereIn('jns_absen', ['H1', 'H2'])->count();
        $SK = $absen_d_query->where('jns_absen', 'SK')->count();
        $SD = $absen_d_query->where('jns_absen', 'SD')->count();
        $I = $absen_d_query->where('jns_absen', 'I')->count();
        $IPC = $absen_d_query->where('jns_absen', 'IPC')->count();

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
        // SCB BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB
        $mskrj = Carbon::parse($peg->tgl_masuk); // Mengonversi tanggal masuk ke objek Carbon
        $sdbulan = Carbon::now(); // Objek Carbon untuk tanggal yang diinginkan

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

        // dd($SCB);
        // return view('../hr/dashboard/report/reportcuti/list', compact('bulanAwal', 'bulanAkhir', 'tahun', 'peg', 'H', 'SK', 'SD', 'I', 'IPC', 'IC', 'M', 'lmbtx', 'lmbtjm', 'ipax', 'ipajam', 'dl', 'icb', 'SCTB', 'SCB'));
        //  batassssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss----------------------------------------------------------------------------------------------------------------------------------------------------------

        // -----------------------------------------------------------------------------------------------------------------------------------------------------------

        $srtcuti = srtcuti::create([
            'ct_nom' => $request->ct_nom,
            'ct_tgl' => $request->ct_tgl,
            'ct_unt' => $pegawai->bagian,
            'ct_nam' => $pegawai->nama_asli,
            'ct_reg' => $request->ct_reg,
            'ct_jml' => $request->ct_jml,
            'ct_dr1' => $request->ct_dr1,
            'ct_sd1' => $request->ct_sd1,
            'ct_dr2' => $request->ct_dr2,
            'ct_sd2' => $request->ct_sd2,
            'ct_not' => $request->ct_not,
            'setuju' => $request->setuju,
            'ket_atas' => $request->ket_atas,
            'ket_pers' => $request->ket_pers,
            'skt' => $SD,
            'ijn' => $I,
            'cti' => $IC,
            'tlb' => $lmbtx,
            'mkr' => $M,
            'ipa' => $ipax,
            'scl' => 0,
            'scs' => $SCTB,
            'scb' => $SCB,
            'pemohon' => $pegawai->nama_asli,
            'ct_jbt' => $pegawai->ct_jbt,
        ]);

        return redirect()
            ->route('hr.srtcuti.list')
            ->with('success', 'Subject has been Created.');
    }

    public function edit($id)
    {
        $cuti = srtcuti::where('id', $id)->first();

        return view('../hr/dashboard/srtcuti/edit', compact('cuti'));
    }
    public function update(Request $request, $id)
    {
        $srtcuti = srtcuti::find($id);
        $noPayroll = $request->ct_reg;
        $pegawai = pegawai::where('no_payroll', $noPayroll)->first();

        $srtcuti->ct_nom = $request->ct_nom;
        $srtcuti->ct_tgl = $request->ct_tgl;
        $srtcuti->ct_unt = $pegawai->bagian;
        $srtcuti->ct_nam = $pegawai->nama_asli;
        $srtcuti->ct_jbt = $pegawai->jabatan;
        $srtcuti->ct_reg = $noPayroll;
        $srtcuti->ct_jml = $request->ct_jml;
        $srtcuti->ct_dr1 = $request->ct_dr1;
        $srtcuti->ct_sd1 = $request->ct_sd1;
        $srtcuti->ct_dr2 = $request->ct_dr2;
        $srtcuti->ct_sd2 = $request->ct_sd2;
        $srtcuti->ct_not = $request->ct_not;
        $srtcuti->setuju = $request->setuju;
        $srtcuti->ket_atas = $request->ket_atas;
        $srtcuti->ket_pers = $request->ket_pers;

        $srtcuti->save();

        // Redirect ke halaman list srtcuti dengan pesan sukses
        return redirect()
            ->route('hr.srtcuti.list')
            ->with('success', 'Subject has been updated.');
    }

    public function delete($id)
    {
        $sc = srtcuti::findOrFail($id);
        $sc->delete();
        return redirect()
            ->route('hr.srtcuti.list')
            ->with('success', 'Data Berhasil di Hapus');
    }

    public function print($id)
    {
        $cuti = srtcuti::where('id', $id)->first();

        $pdf = Pdf::loadview('../hr/dashboard/srtcuti/print', compact('cuti'));
        return $pdf->setPaper('a4', 'potrait')->stream('Cuti Print.pdf');
    }
}
