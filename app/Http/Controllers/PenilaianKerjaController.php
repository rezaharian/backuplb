<?php

namespace App\Http\Controllers;

use App\Models\absen_d;
use App\Models\absen_h;
use App\Models\bagian;
use App\Models\ct_besar;
use App\Models\Kompeten;
use App\Models\onoff_tg;
use App\Models\peg_d;
use App\Models\pegawai;
use App\Models\Pkinerja;
use App\Models\Pkinerja_b;
use App\Models\Pkinerja_c;
use App\Models\Presensi;
use App\Models\Pt_gaji;
use App\Models\TglLibur;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenilaianKerjaController extends Controller
{
    public function index()
    {
        $pkinerja = Pkinerja::join('pkinerja_bs', 'pkinerjas.kode', '=', 'pkinerja_bs.kode')
            ->groupBy('pkinerjas.id', 'pkinerja_bs.kode', 'pkinerjas.kode', 'pkinerjas.no_payroll', 'pkinerjas.nama', 'pkinerjas.bagian', 'pkinerjas.periode', 'pkinerjas.review', 'pkinerjas.total_score')
            ->orderBy('pkinerjas.id', 'desc')
            ->selectRaw('pkinerjas.id, pkinerjas.kode, pkinerjas.no_payroll, pkinerjas.nama, pkinerjas.bagian, pkinerjas.periode, pkinerjas.review, pkinerjas.total_score, SUM(pkinerja_bs.nilai1) as total_nilai1, SUM(pkinerja_bs.nilai2) as total_nilai2, SUM(pkinerja_bs.nilai3) as total_nilai3')
            ->get();

        $jumlahsemua = pegawai::orderBy('id', 'desc')
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->where('bagian', '!=', 'direksi')
            ->where('bagian', '!=', 'direksi')
            ->where('jabatan', '!=', 'DIREKSI')
            ->where('jabatan', '!=', 'DIREKSI')
            ->whereNull('tgl_keluar')
            ->count();
        $jumlahygsudah = $pkinerja->count();

        // dd($pkinerja->toArray());

        return view('hr.dashboard.penilaiankerja.index', compact('pkinerja', 'jumlahygsudah', 'jumlahsemua'));
    }

    public function create()
    {
        $peg = pegawai::orderBy('no_payroll', 'asc')
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->where('bagian', '!=', 'direksi')
            ->where('bagian', '!=', 'direksi')
            ->where('jabatan', '!=', 'DIREKSI')
            ->where('jabatan', '!=', 'DIREKSI')
            ->whereNull('tgl_keluar')
            ->get();
        $kode = rand() . date('YmdHis');
        $kompeten = Kompeten::where('type', 'B')->get();
        $kompeten2 = bagian::get();

        return view('hr.dashboard.penilaiankerja.create', compact('peg', 'kode', 'kompeten', 'kompeten2'));
    }

    public function kelengkapanpegawai($no_payroll)
    {
        $employee = pegawai::where('no_payroll', $no_payroll)->first();
        return response()->json($employee);
    }
    public function kelengkapandataperbagianhr($bagian)
    {
        $kom = Kompeten::where('bagian', $bagian)->get(); // Menggunakan get() untuk mengambil semua data yang sesuai
        $penjelasan = $kom->pluck('penjelasan'); // Mengambil semua penjelasan dan mengubahnya menjadi array
        return response()->json(['penjelasan' => $penjelasan]);
    }

    public function perhitunganAbsen(request $request)
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
        $SCB = ct_besar::where('no_payroll', $noPayroll)
            ->where('tahun', $tahunSebelumnya)
            ->value('sisa_cb');
    }

    public function store(Request $request)
    {
        // dd($request->toArray());
        // Validasi data yang dikirimkan melalui formulir
        $validatedData = $request->validate([
            'kode' => 'required',
            'no_payroll' => 'required',
            'nama' => 'required',
            'bagian' => 'required',
            'jabatan' => '',
            'review' => 'required',
            // 'mkr' => 'required',
            // 'mkr_value' => 'required',
            // 'ijin' => 'required',
            // 'ijin_value' => 'required',
            // 'sakit' => 'required',
            // 'sakit_value' => 'required',
            // 'mdt' => 'required',
            // 'mdt_value' => 'required',
            'sp' => '',
            'sp_value' => '',
            'perf_faktor' => 'required|array', // Hapus '*' dari 'perf_faktor'
            'nilai1' => 'required|array', // Hapus '*' dari 'nilai1'
            'nilai2' => 'required|array', // Hapus '*' dari 'nilai2'
            'nilai3' => 'required|array', // Hapus '*' dari 'nilai3'
            'perf_faktor.*' => 'required', // Tambahkan '*' kembali di sini
            'nilai1.*' => 'required', // Tambahkan '*' kembali di sini
            'nilai2.*' => 'required', // Tambahkan '*' kembali di sini
            'nilai3.*' => 'required', // Tambahkan '*' kembali di sini
            'major_job' => '|array', // Hapus '*' dari 'perf_faktor'
            'major_job.*' => '', // Tambahkan '*' kembali di sini
            'perform_ach' => '|array', // Hapus '*' dari 'perf_faktor'
            'perform_ach.*' => '', // Tambahkan '*' kembali di sini
            'nilai1c' => '|array', // Hapus '*' dari 'perf_faktor'
            'nilai1c.*' => '', // Tambahkan '*' kembali di sini
            'nilai2c' => '|array', // Hapus '*' dari 'perf_faktor'
            'nilai2c.*' => '', // Tambahkan '*' kembali di sini

            //
            '' => '',
            'score_c' => '',
            'remark' => '',
            'awal' => '',
            'akhir' => '',
        ]);

        $pkinerja = Pkinerja::where('no_payroll', $validatedData['no_payroll'])->first();

        if ($pkinerja) {
            // Periksa apakah hari ini belum melewati periode akhir
            $akhir = $validatedData['akhir']; // Menggunakan Carbon untuk mendapatkan tanggal dan waktu saat ini

            // Memisahkan tanggal akhir dari teks periode
            $periodeParts = explode(' - ', $pkinerja->periode);
            $periodeAkhir = trim($periodeParts[1]); // Mengambil tanggal akhir dan menghapus spasi ekstra

            // Mengonversi tanggal akhir ke objek Carbon
            $periodeAkhirCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $periodeAkhir);

            if ($akhir <= $periodeAkhirCarbon) {
                // Redirect ke halaman edit dengan no_payroll yang sudah ada

                $peg = pegawai::where('no_payroll', $pkinerja->no_payroll)->first();
                $pkinerja_b = Pkinerja_b::where('kode', $pkinerja->kode)->get();
                $kode = rand() . date('YmdHis');

                $kompeten2 = bagian::get();

                $kompeten = Kompeten::where('bagian', $peg->departemen)->get();
                // dd($kompeten);

                return redirect()
                    ->route('hr.penilaiankerja.edit', ['id' => $pkinerja->id])
                    ->with([
                        'danger' => 'Data sudah ada, silahkan edit yang sudah dibuat berikut ini .',
                        'pkinerja' => $pkinerja,
                        'peg' => $peg,
                        'pkinerja_b' => $pkinerja_b,
                        'kompeten' => $kompeten,
                        'kode' => $kode,
                        'kompeten2' => $kompeten2,
                    ]);
            }
        }

        $nilai1j = $validatedData['nilai1'][0];
        $nilai2j = $validatedData['nilai2'][0];
        $nilai3j = $validatedData['nilai3'][0];

        $ns = ($nilai1j > 0) + ($nilai2j > 0) + ($nilai3j > 0);

        // dd($ns);

        $periode = peg_d::where('no_payroll', $validatedData['no_payroll'])
            ->orderBy('id', 'desc')
            ->first();
        // DD($periode);

        if ($periode) {
            $periodejadi = $periode->Perpanjang . ' - ' . $periode->berakhir;
        } else {
            $startOfYear = Carbon::now()
                ->startOfYear()
                ->toDateString();
            $endOfYear = Carbon::now()
                ->endOfYear()
                ->toDateString();
            $periodejadi = $startOfYear . ' - ' . $endOfYear;
        }
        $periodejadi = (string) $periodejadi;

        // Simpan data ke database

        // Mencari Jumlah apla, ijin, late dll

        $absen = absen_h::where('no_payroll', $validatedData['no_payroll'])->first();

        $peg = pegawai::where('no_payroll', $validatedData['no_payroll'])->first();
        // $kontrak = peg_d::where('no_payroll', $validatedData['no_payroll'])->first();
        // $permanen = Carbon::now();
        // $akhir_sekarang = Carbon::now();
        // if ($kontrak) {
        //     $k_awal = $kontrak->Perpanjang;
        //     $k_akhir = $akhir_sekarang;
        // } else {
        //     // Mengatur $k_awal ke tanggal pertama tahun ini
        //     $k_awal = Carbon::now()->startOfYear();
        //     // Mengatur $k_akhir ke tanggal terakhir tahun ini
        //     $k_akhir = $akhir_sekarang;
        // }

        $k_awal = $validatedData['awal'];
        $k_akhir = $validatedData['akhir'];

        // dd($k_awal, $k_akhir);

        $absen_d_query = absen_d::where('int_peg', $absen->int_peg)->whereBetween('tgl_absen', [$k_awal, $k_akhir]);

        $IC = $absen_d_query->where('jns_absen', 'IC')->count();
        $H = $absen_d_query->whereIn('jns_absen', ['H1', 'H2'])->count();
        $SK = $absen_d_query->where('jns_absen', 'SK')->count();
        $SD = $absen_d_query->where('jns_absen', 'SD')->count();
        $I = $absen_d_query->where('jns_absen', 'I')->count();
        $IPC = $absen_d_query->where('jns_absen', 'IPC')->count();

        if ($peg->jns_peg == 'TETAP') {
            $lmbtx = Presensi::where('no_reg', $absen->no_payroll)
                ->whereNotNull('norm_m')
                ->whereNotNull('norm_k')
                ->whereNotNull('masuk')
                ->whereNotNull('keluar')
                ->whereRaw("STR_TO_DATE(masuk, '%H:%i') > DATE_ADD(STR_TO_DATE(norm_m, '%H:%i'), INTERVAL 10 MINUTE)")
                ->where(function ($query) use ($k_awal, $k_akhir) {
                    $query->whereBetween('tanggal', [$k_awal, $k_akhir]);
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
        } else {
            $lmbtx = Presensi::where('no_reg', $absen->no_payroll)
                ->whereNotNull('norm_m')
                ->whereNotNull('norm_k')
                ->whereNotNull('masuk')
                ->whereNotNull('keluar')
                ->whereColumn('masuk', '>', 'norm_m')
                ->where(function ($query) use ($k_awal, $k_akhir) {
                    $query->whereBetween('tanggal', [$k_awal, $k_akhir]);
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
        }

        // Mencari M ============================================================================================================

        $M = 0;
        $tanggalAwalPertama = $k_awal;
        $tanggalAkhirTerakhir = $k_akhir;
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

        $sick = $SK + $SD;
        $ijin = $I;
        $late = $lmbtx;
        $alpha = $M;

        if ($sick == 0) {
            $sick_value = 5;
        } elseif ($sick >= 1 && $sick <= 24) {
            $sick_value = 4;
        } elseif ($sick >= 25 && $sick <= 49) {
            $sick_value = 3;
        } elseif ($sick >= 50 && $sick <= 74) {
            $sick_value = 2;
        } elseif ($sick >= 75) {
            $sick_value = 1;
        } else {
            $sick_value = -1;
        }

        // Rumus untuk $late
        if ($late == 0) {
            $late_value = 5;
        } elseif ($late >= 1 && $late <= 24) {
            $late_value = 4;
        } elseif ($late >= 25 && $late <= 49) {
            $late_value = 3;
        } elseif ($late >= 50 && $late <= 74) {
            $late_value = 2;
        } elseif ($late > 75) {
            $late_value = 1;
        }

        // Rumus untuk $alpha
        if ($alpha == 0) {
            $alpha_value = 5;
        } elseif ($alpha == 1) {
            $alpha_value = 4;
        } elseif ($alpha == 2) {
            $alpha_value = 3;
        } elseif ($alpha == 3) {
            $alpha_value = 2;
        } elseif ($alpha > 3) {
            $alpha_value = 1;
        }

        $ijin_value = 5;

        if ($validatedData['sp_value']) {
            $sp_value = $validatedData['sp_value'];
        } else {
            $sp_value = 5;
        }
        if ($validatedData['sp']) {
            $sp = $validatedData['sp'];
        } else {
            $sp = 0;
        }

        $score_c = $validatedData['score_c'];
        $score_a = (($alpha_value + $ijin_value + $sick_value + $late_value + $sp_value) / 5) * 20;

        $jumlah_nilai1 = array_sum($validatedData['nilai1']);
        $jumlah_nilai2 = array_sum($validatedData['nilai2']);
        $jumlah_nilai3 = array_sum($validatedData['nilai3']);
        $score_b = (($jumlah_nilai1 + $jumlah_nilai2 + $jumlah_nilai3) / $ns / 14) * 20;
        // dd($score_b);

        $total_score = ($score_a + $score_b + $score_c) / 3;
        // dd($sick, $ijin, $late, $alpha);

        $un1 = Auth::user()->name;
        // dd($un1);

        foreach ($validatedData['perf_faktor'] as $index => $perfFaktor) {
            $penjelasan = $request->input('penjelasan')[$index];

            $penilaianKerja = new Pkinerja_b();
            $penilaianKerja->kode = $validatedData['kode'];
            $penilaianKerja->penjelasan = $penjelasan;
            $penilaianKerja->perf_faktor = $perfFaktor;
            $penilaianKerja->nilai1 = $validatedData['nilai1'][$index];
            $penilaianKerja->nilai2 = $validatedData['nilai2'][$index];
            $penilaianKerja->nilai3 = $validatedData['nilai3'][$index];
            $penilaianKerja->un1 = $un1;

            $penilaianKerja->save();
        }
        // Simpan data ke database
        // foreach ($validatedData['major_job'] as $index => $majorJob) {
        //     $perform_ach = $request->input('perform_ach')[$index];

        //     $penilaianKerjac = new Pkinerja_c();
        //     $penilaianKerjac->kode = $validatedData['kode'];
        //     $penilaianKerjac->perform_ach = $perform_ach;
        //     $penilaianKerjac->major_job = $majorJob;
        //     $penilaianKerjac->nilai1 = $validatedData['nilai1c'][$index];
        //     $penilaianKerjac->nilai2 = $validatedData['nilai2c'][$index];

        //     $penilaianKerjac->save();
        // }

        // Masukkan data ke tabel pkinerja (Hanya satu kali)

        $penilaianKerjaP = new Pkinerja();
        $penilaianKerjaP->kode = $validatedData['kode'];
        $penilaianKerjaP->no_payroll = $validatedData['no_payroll'];
        $penilaianKerjaP->nama = $validatedData['nama'];
        $penilaianKerjaP->bagian = $validatedData['bagian'];
        $penilaianKerjaP->jabatan = $validatedData['jabatan'];
        $penilaianKerjaP->review = $validatedData['review'];
        $penilaianKerjaP->mkr = $alpha;
        $penilaianKerjaP->mkr_value = $alpha_value;
        $penilaianKerjaP->ijin = $ijin;
        $penilaianKerjaP->ijin_value = $ijin_value;
        $penilaianKerjaP->sakit = $sick;
        $penilaianKerjaP->sakit_value = $sick_value;
        $penilaianKerjaP->mdt = $late;
        $penilaianKerjaP->mdt_value = $late_value;
        $penilaianKerjaP->sp = $sp;
        $penilaianKerjaP->sp_value = $sp_value;
        $penilaianKerjaP->periode = $periodejadi;
        $penilaianKerjaP->score_c = $score_c;
        $penilaianKerjaP->score_a = $score_a;
        $penilaianKerjaP->score_b = $score_b;
        $penilaianKerjaP->total_score = $total_score;
        $penilaianKerjaP->remark = $validatedData['remark'];
        $penilaianKerjaP->awal = $validatedData['awal'];
        $penilaianKerjaP->akhir = $validatedData['akhir'];

        $penilaianKerjaP->save();

        // Jika Anda ingin melakukan sesuatu setelah penyimpanan berhasil, Anda bisa menambahkan logika di sini

        // Setelah data disimpan, Anda bisa mengarahkan pengguna ke halaman yang sesuai
        return redirect()
            ->route('hr.penilaiankerja.index')
            ->with('success', 'Operasi berhasil dilakukan!');
    }

    public function view($id)
    {
        $pkinerja = Pkinerja::where('id', $id)->first();
        $peg = pegawai::where('no_payroll', $pkinerja->no_payroll)->first();
        $pkinerja_b = Pkinerja_b::where('kode', $pkinerja->kode)->get();

        $penilai = Pkinerja_b::select('un1', 'un2', 'un3')
            ->where('kode', $pkinerja->kode)
            ->first();

        $kompeten = Kompeten::where('bagian', $peg->departemen)->get();
        // dd($kompeten);

        return view('hr.dashboard.penilaiankerja.view', compact('pkinerja', 'peg', 'pkinerja_b', 'kompeten', 'penilai'));
    }
    public function print($id)
    {
        $pkinerja = Pkinerja::where('id', $id)->first();
        $peg = pegawai::where('no_payroll', $pkinerja->no_payroll)->first();
        $pkinerja_b = Pkinerja_b::where('kode', $pkinerja->kode)->get();

        $kompeten = Kompeten::where('bagian', $peg->bagian)
            // ->where('bagian', $peg->departemen)
            // ->where('jabatan', $peg->jabatan)
            ->get();
        // dd($kompeten);
        if ($peg->jns_peg == 'TETAP') {
            $kontrak_period = '-';
        } else {
            $kontrak_period = $pkinerja->periode;
        }

        $pdf = Pdf::loadview('hr.dashboard.penilaiankerja.print', compact('pkinerja', 'peg', 'pkinerja_b', 'kompeten', 'kontrak_period'));
        return $pdf->setPaper('a4', 'potrait')->stream('Penilaian.pdf');
    }

    public function edit($id)
    {
        $pkinerja = Pkinerja::where('id', $id)->first();
        $peg = pegawai::where('no_payroll', $pkinerja->no_payroll)->first();
        $pkinerja_b = Pkinerja_b::where('kode', $pkinerja->kode)->get();

        $kode = $pkinerja->kode;

        $kompeten2 = bagian::get();
        $penilai = Pkinerja_b::where('kode', $pkinerja->kode)->first();

        $kompeten = Kompeten::where('bagian', $peg->bagian)->get();
        // dd($kompeten);

        return view('hr.dashboard.penilaiankerja.edit', compact('pkinerja', 'penilai', 'peg', 'pkinerja_b', 'kompeten', 'kode', 'kompeten2'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->toArray());
        // Validasi data yang dikirim melalui form
        $validatedData = $request->validate([
            'kode' => '',
            'no_payroll' => 'required',
            'nama' => 'required',
            'bagian' => 'required',
            'perf_faktor' => '',
            'penjelasan' => '',
            'kode' => '',
            'nilai1.*' => 'required', // Tambahkan '*' kembali di sini
            'nilai2.*' => 'required', // Tambahkan '*' kembali di sini
            'nilai3.*' => 'required', // Tambahkan '*' kembali di sini
            'major_job' => '|array', // Hapus '*' dari 'perf_faktor'
            'major_job.*' => '', // Tambahkan '*' kembali di sini
            'perform_ach' => '|array', // Hapus '*' dari 'perf_faktor'
            'perform_ach.*' => '', // Tambahkan '*' kembali di sini
            'nilai1c' => '|array', // Hapus '*' dari 'perf_faktor'
            'nilai1c.*' => '', // Tambahkan '*' kembali di sini
            'nilai2c' => '|array', // Hapus '*' dari 'perf_faktor'
            'nilai2c.*' => '', // Tambahkan '*' kembali di sini
            'review' => 'required',
            'mkr' => '',
            'mkr_value' => '',
            'ijin' => '',
            'ijin_value' => '',
            'sakit' => '',
            'sakit_value' => '',
            'mdt' => '',
            'mdt_value' => '',
            'sp' => '',
            'sp_value' => '',
            'remark' => '',
            'score_c' => '',
            'awal' => '',
            'akhir' => '',
            // Tambahkan validasi lain sesuai kebutuhan Anda
        ]);

        // Cari record PenilaianKerja berdasarkan ID
        $penilaianKerja = Pkinerja::find($id);

        if (!$penilaianKerja) {
            // Handle jika record tidak ditemukan
            return redirect()
                ->route('hr.penilaiankerja.index')
                ->with('error', 'Penilaian Kerja tidak ditemukan');
        }

        if ($validatedData['mdt'] == 0) {
            $mdt_v = 5;
        } elseif ($validatedData['mdt'] >= 1 && $validatedData['mdt'] <= 24) {
            $mdt_v = 4;
        } elseif ($validatedData['mdt'] >= 25 && $validatedData['mdt'] <= 49) {
            $mdt_v = 3;
        } elseif ($validatedData['mdt'] >= 50 && $validatedData['mdt'] <= 74) {
            $mdt_v = 2;
        } else {
            $mdt_v = 1;
        }

        if ($validatedData['mkr'] == 0) {
            $mkr_v = 5;
        } elseif ($validatedData['mkr'] == 1) {
            $mkr_v = 4;
        } elseif ($validatedData['mkr'] == 2) {
            $mkr_v = 3;
        } elseif ($validatedData['mkr'] == 3) {
            $mkr_v = 2;
        } else {
            $mkr_v = 1;
        }

        if ($validatedData['ijin'] == 0) {
            $ijin_v = 5;
        } elseif ($validatedData['ijin'] == 1) {
            $ijin_v = 4;
        } elseif ($validatedData['ijin'] == 2) {
            $ijin_v = 3;
        } elseif ($validatedData['ijin'] == 3) {
            $ijin_v = 2;
        } else {
            $ijin_v = 1;
        }

        if ($validatedData['sakit'] == 0) {
            $sick_v = 5;
        } elseif ($validatedData['sakit'] >= 1 && $validatedData['sakit'] <= 24) {
            $sick_v = 4;
        } elseif ($validatedData['sakit'] >= 25 && $validatedData['sakit'] <= 49) {
            $sick_v = 3;
        } elseif ($validatedData['sakit'] >= 50 && $validatedData['sakit'] <= 74) {
            $sick_v = 2;
        } else {
            $sick_v = 1;
        }

        $nilai1j = $validatedData['nilai1'][0];
        $nilai2j = $validatedData['nilai2'][0];
        $nilai3j = $validatedData['nilai3'][0];

        $ns = ($nilai1j > 0) + ($nilai2j > 0) + ($nilai3j > 0);

        $sp_value = $validatedData['sp_value'];
        $score_c = $validatedData['score_c'];
        $score_a = (($mkr_v + $ijin_v + $sick_v + $mdt_v + $sp_value) / 5) * 20;

        $jumlah_nilai1 = array_sum($validatedData['nilai1']);
        $jumlah_nilai2 = array_sum($validatedData['nilai2']);
        $jumlah_nilai3 = array_sum($validatedData['nilai3']);
        $score_b = (($jumlah_nilai1 + $jumlah_nilai2 + $jumlah_nilai3) / $ns / 14) * 20;
        // dd($score_b);

        $total_score = ($score_a + $score_b + $score_c) / 3;

        $penilaianKerja->update([
            'no_payroll' => $request->input('no_payroll'),
            'nama' => $request->input('nama'),
            'bagian' => $request->input('bagian'),
            'review' => $request->input('review'),
            'mkr' => $request->input('mkr'),
            'mkr_value' => $mkr_v,
            'ijin' => $request->input('ijin'),
            'ijin_value' => $ijin_v,
            'sakit' => $request->input('sakit'),
            'sakit_value' => $sick_v,
            'mdt' => $request->input('mdt'),
            'mdt_value' => $mdt_v,
            'sp' => $request->input('sp'),
            'sp_value' => $request->input('sp_value'),
            'remark' => $request->input('remark'),
            'score_a' => $score_a,
            'score_b' => $score_b,
            'score_c' => $score_c,
            'total_score' => $total_score,
            'awal' => $request->input('awal'),
            'akhir' => $request->input('akhir'),
            // Update kolom lain sesuai kebutuhan
        ]);

        $un = Auth::user()->name;

        foreach ($validatedData['perf_faktor'] as $index => $perfFaktor) {
            $penjelasan = $validatedData['penjelasan'][$index];

            $updateData = [
                'nilai1' => $validatedData['nilai1'][$index],
                'nilai2' => $validatedData['nilai2'][$index],
                'nilai3' => $validatedData['nilai3'][$index],
            ];

            // Cek apakah un1 kosong
            $existingRecord = Pkinerja_b::where([
                'perf_faktor' => $perfFaktor,
                'penjelasan' => $penjelasan,
                'kode' => $validatedData['kode'],
            ])->first();

            // if (!$existingRecord->un1) {
            //     $updateData['un1'] = $un;
            // } elseif (!$existingRecord->un2) {
            //     $updateData['un2'] = $un;
            // } elseif (!$existingRecord->un3) {
            //     $updateData['un3'] = $un;
            // }

            // Update record Pkinerja_b
            Pkinerja_b::where([
                'perf_faktor' => $perfFaktor,
                'penjelasan' => $penjelasan,
                'kode' => $validatedData['kode'],
            ])->update($updateData);
        }

        // Redirect kembali ke form dengan pesan sukses
        return redirect()
            ->route('hr.penilaiankerja.index')
            ->with('success', 'Penilaian Kerja berhasil diperbarui');
    }

    public function delete($id)
    {
        // Temukan data Pkinerja berdasarkan ID
        $pkinerja = Pkinerja::findOrFail($id);

        // Temukan data Pkinerja_b berdasarkan kode Pkinerja
        $pkinerja_b = Pkinerja_b::where('kode', $pkinerja->kode)->first();

        // Hapus data Pkinerja_b terlebih dahulu
        if ($pkinerja_b) {
            $pkinerja_b->delete();
        }

        // Hapus data Pkinerja
        $pkinerja->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()
            ->route('hr.penilaiankerja.index')
            ->with('success', 'Data Berhasil di Hapus');
    }
    public function laporan()
    {
        // Menghitung tahun saat ini
        $currentYear = date('Y');
        // Menyiapkan array untuk menyimpan periode
        $periodes = [];

        // Membuat perulangan untuk empat tahun kebelakang
        for ($i = 0; $i < 4; $i++) {
            $year = $currentYear - $i;
            $start_date = $year . '-01-01';
            $end_date = $year . '-12-31';
            // Format periode menjadi 'YYYY-MM-DD - YYYY-MM-DD' dan menambahkannya ke dalam array
            $formatted_period = "$start_date - $end_date";
            $periodes[] = $formatted_period;
        }
        // dd($periodes);
        // Mengirim data periode ke view
        return view('hr.dashboard.penilaiankerjareport.laporan', compact('periodes'));
    }

    public function listLaporan(Request $request)
    {
        $periode = $request->periode;
        [$awal, $akhir] = explode(' - ', $periode);

        if ($request->karyawan == 'semua') {
            $pkinerja = Pkinerja::whereBetween('akhir', [$awal, $akhir])
                ->orderBy('bagian', 'asc')
                ->orderBy('no_payroll', 'asc')
                ->get();
        } elseif ($request->karyawan == 'tetap') {
            $pkinerja = Pkinerja::whereBetween('akhir', [$awal, $akhir])
            ->where('pegawais.jns_peg', 'TETAP')
            ->orderBy('pkinerjas.bagian', 'asc')
            ->orderBy('pkinerjas.no_payroll', 'asc')
            ->join('pegawais', 'pkinerjas.no_payroll', '=', 'pegawais.no_payroll')
            ->get();
        } 
        elseif ($request->karyawan == 'kontrak') {
            $pkinerja = Pkinerja::whereBetween('akhir', [$awal, $akhir])
            ->where('pegawais.jns_peg', 'KONTRAK')
            ->orderBy('pkinerjas.bagian', 'asc')
            ->orderBy('pkinerjas.no_payroll', 'asc')
            ->join('pegawais', 'pkinerjas.no_payroll', '=', 'pegawais.no_payroll')
            ->get();
        }

        if ($request->report_type == 'pdforprint') {
            $pdf = Pdf::loadview('hr.dashboard.penilaiankerjareport.printLaporanPdf', ['pkinerja' => $pkinerja, 'periode' => $periode]);
            return $pdf->setPaper('a4', 'potrait')->stream('penilaian kerja report.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set judul dan periode
            $sheet->setCellValue('A1', 'REPORT PENILAIAN KERJA PT.EXTRUPACK');
            $sheet->setCellValue('A2', 'Periode : ' . $periode);

            // Tambahkan header tabel
            $sheet->setCellValue('A4', 'No');
            $sheet->setCellValue('B4', 'No Payroll');
            $sheet->setCellValue('C4', 'Nama');
            $sheet->setCellValue('D4', 'Bagian');
            $sheet->setCellValue('E4', 'Review');
            $sheet->setCellValue('F4', 'Total Nilai');
            $sheet->setCellValue('G4', 'Remark');

            // Isi data ke dalam tabel
            $row = 5;
            foreach ($pkinerja as $item) {
                $sheet->setCellValue('A' . $row, $row - 4);
                $sheet->setCellValue('B' . $row, $item->no_payroll);
                $sheet->setCellValue('C' . $row, $item->nama);
                $sheet->setCellValue('D' . $row, $item->bagian);
                $sheet->setCellValue('E' . $row, $item->review);
                $sheet->setCellValue('F' . $row, $item->total_score);
                $sheet->setCellValue('G' . $row, $item->remark);
                $row++;
            }

            // Mengatur lebar kolom
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(10);
            $sheet->getColumnDimension('C')->setWidth(10);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(10);
            $sheet->getColumnDimension('F')->setWidth(10);
            $sheet->getColumnDimension('G')->setWidth(50);

            // Mengatur style untuk header tabel
            $headerStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
            ];

            $sheet->getStyle('A4:G4')->applyFromArray($headerStyle);

            // Mengatur nama file Excel yang akan diunduh
            $filename = 'Penilaian_Kerja_EXTRUPACK.xlsx';

            // Menggunakan objek Writer untuk menghasilkan file Excel
            $writer = new Xlsx($spreadsheet);

            // Menyimpan file Excel dan memberikan response untuk diunduh
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');

            exit();
        }
    }
}
