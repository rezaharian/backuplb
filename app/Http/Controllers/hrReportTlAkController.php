<?php

namespace App\Http\Controllers;

use App\Models\absen_d;
use App\Models\onoff_tg;
use App\Models\pegawai;
use App\Models\Presensi;
use App\Models\TglLibur;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class hrReportTlAkController extends Controller
{
    public function index()
    {
        return view('../hr/dashboard/reporttlak/index');
    }

    public function list(Request $request)
    {
        set_time_limit(500);
        $jenis = $request->input('jenis');

        $tgl_awal = $request->input('awal');
        $tgl_akhir = $request->input('akhir');
        $taw = Carbon::parse($tgl_awal)->format('Y-m-d');
        $tak = Carbon::parse($tgl_akhir)->format('Y-m-d');

        $peg = pegawai::orderBy('no_payroll', 'desc')
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->whereNull('tgl_keluar')
            ->pluck('no_payroll');

        if ($jenis == 'absen tidak lengkap') {
            $jns = 'LAPORAN ABSEN TIDAK LENGKAP';
            $absentlak = Presensi::whereBetween('tanggal', [$taw, $tak])
                ->join('pegawais', 'pegawais.no_payroll', '=', 'presensis.no_reg')
                ->whereIn('presensis.no_reg', $peg)
                ->where(function ($query) {
                    $query->whereRaw('masuk IS NULL OR masuk = ""')->orWhereRaw('keluar IS NULL OR keluar = ""');
                })
                ->get();
        } elseif ($jenis == 'absen kembar') {
            $jns = 'LAPORAN ABSEN KEMBAR';
            $absentlak = Presensi::whereBetween('tanggal', [$taw, $tak])
                ->join('pegawais', 'pegawais.no_payroll', '=', 'presensis.no_reg')
                ->whereIn('presensis.no_reg', $peg)
                ->where(function ($query) {
                    $query
                        ->WhereRaw('masuk = keluar')
                        ->whereRaw('masuk IS NOT NULL AND masuk <> ""')
                        ->WhereRaw('keluar IS NOT NULL AND keluar <> ""');
                })
                ->get();
        } else {
            $jns = 'LAPORAN KARYAWAN TIDKA MASUK HARIAN';

            $tanggalAwal = Carbon::parse($taw);
            $tanggalAkhir = Carbon::parse($tak);

            // tanggal dari awal sampai akhir
            $selisihHari = $tanggalAkhir->diffInDays($tanggalAwal);
            $semuaTanggal = [];
            for ($i = 0; $i <= $selisihHari; $i++) {
                $semuaTanggal[] = $tanggalAwal
                    ->copy()
                    ->addDays($i)
                    ->toDateString();
            }

            $abs_onof = onoff_tg::whereIn('tgl_off', $semuaTanggal)
                ->orWhereIn('tgl_on', $semuaTanggal)
                ->pluck('tgl_on', 'tgl_off')
                ->toArray();
            $abs_lbr = TglLibur::whereIn('tgl_libur', $semuaTanggal)
                ->pluck('tgl_libur')
                ->toArray();

            $tanggalfix = [];
            foreach ($semuaTanggal as $tanggal) {
                // Periksa apakah tanggal tidak ada di abs_pre, abs_d, abs_lbr, abs_onof
                if (!in_array($tanggal, $abs_lbr) && !in_array($tanggal, $abs_onof)) {
                    // Periksa apakah tanggal bukan Sabtu atau Minggu (6 atau 7)
                    $hari = date('N', strtotime($tanggal));
                    if ($hari >= 1 && $hari <= 5) {
                        // 1-5 adalah hari kerja (Senin-Jumat)
                        $tanggalfix[] = $tanggal;
                    }
                }
            }

            // dd($tanggalfix);

            $pegawaiQuery = pegawai::where(function ($query) {
                $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
            })
                ->where('bagian', '!=', 'KEBERIHAN')
                ->where('bagian', '!=', 'SATPAM')
                ->where('bagian', '!=', 'RUMAH TANGGA')
                ->where('bagian', '!=', '')
                ->where('bagian', '!=', 'DIREKSI')
                ->orderBy('no_payroll', 'asc');

            $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

            // Mengumpulkan semua nomor registrasi pegawai
            $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

            // Mengambil data absen pegawai
            $absentlak = Presensi::whereIn('presensis.no_reg', $noRegistrasiPegawai)
                ->join('pegawais', 'pegawais.no_payroll', '=', 'presensis.no_reg')
                ->whereIn('tanggal', $semuaTanggal)

                ->where(function ($query) {
                    $query
                        ->where(function ($subQuery) {
                            $subQuery->whereRaw('(masuk IS NULL OR masuk = "")')->orWhereRaw('(keluar IS NULL OR keluar = "")');
                        })
                        ->orWhere(function ($subQuery) {
                            $subQuery
                                ->whereRaw('masuk = keluar')
                                ->whereRaw('masuk IS NOT NULL AND masuk <> ""')
                                ->whereRaw('keluar IS NOT NULL AND keluar <> ""');
                        });
                })
                ->get();

            $absenDataL = absen_d::whereIn('absen_ds.tgl_absen', $semuaTanggal)
                ->join('absen_hs', 'absen_hs.int_absen', '=', 'absen_ds.int_absen')
                ->join('pegawais', 'pegawais.no_payroll', '=', 'absen_hs.no_payroll')
                ->select('absen_ds.tgl_absen', 'absen_ds.jns_absen', 'absen_hs.no_payroll', 'absen_ds.dsc_absen', 'pegawais.nama_asli', 'pegawais.bagian')
                ->whereIn('absen_ds.tgl_absen', $semuaTanggal)
                ->whereIn('absen_hs.no_payroll', $noRegistrasiPegawai)
                ->get()
                ->toArray();

            $gabungData = [];

            // Menggabungkan data absen dari tabel Presensi
            foreach ($absentlak as $absensi) {
                $dscAbsen = !empty($absensi->masuk) && !empty($absensi->keluar) && $absensi->masuk == $absensi->keluar ? 'ABSENSI KEMBAR' : 'ABSENSI TIDAK LENGKAP';

                $gabungData[] = [
                    'tanggal' => $absensi->tanggal,
                    'jns_absen' => $absensi->jns_absen, // Tidak ada informasi jenis absen dari tabel Presensi
                    'no_payroll' => $absensi->no_reg,
                    'dsc_absen' => $dscAbsen, // Tidak ada deskripsi absen dari tabel Presensi
                    'nama_asli' => $absensi->nama_asli,
                    'bagian' => $absensi->bagian, // Tidak ada informasi bagian dari tabel Presensi
                    'masuk' => $absensi->masuk,
                    'keluar' => $absensi->keluar,
                    // Kolom lain dari tabel Presensi
                ];
            }

            // Menggabungkan data absen dari tabel Absen_d
            foreach ($absenDataL as $absen_d) {
                $gabungData[] = [
                    'tanggal' => $absen_d['tgl_absen'],
                    'jns_absen' => $absen_d['dsc_absen'],
                    'no_payroll' => $absen_d['no_payroll'],
                    'dsc_absen' => $absen_d['dsc_absen'],
                    'nama_asli' => $absen_d['nama_asli'],
                    'bagian' => $absen_d['bagian'],
                    'masuk' => null, // Tidak ada informasi masuk dari tabel Absen_d
                    'keluar' => null, // Tidak ada informasi keluar dari tabel Absen_d
                    // Kolom lain dari tabel Absen_d
                ];
            }

            // Merge and sort data based on 'nama_asli'
            usort($gabungData, function ($a, $b) {
                return strcmp($a['bagian'], $b['bagian']);
            });

            // Loop untuk setiap pegawai
            foreach ($pegawai as $karyawan) {
                $noPayroll = $karyawan->no_payroll;

                // Loop untuk setiap tanggal dalam $tanggalfix
                foreach ($tanggalfix as $tanggal) {
                    $tanggalDitemukan = false;

                    // Cek apakah tanggal ada di $gabungData
                    foreach ($gabungData as $data) {
                        if ($data['no_payroll'] == $noPayroll && $data['tanggal'] == $tanggal) {
                            $tanggalDitemukan = true;
                            break;
                        }
                    }

                    // Jika tanggal tidak ditemukan di $gabungData, cek apakah ada di tabel Presensi
                    if (!$tanggalDitemukan) {
                        // Cek apakah tanggal ada di tabel Presensi
                        $presensiAda = Presensi::where('no_reg', $noPayroll)
                            ->where('tanggal', $tanggal)
                            ->exists();

                        // Jika tidak ada di tabel Presensi, tambahkan data dengan keterangan "mangkir"
                        if (!$presensiAda) {
                            $gabungData[] = [
                                'tanggal' => $tanggal,
                                'jns_absen' => 'MANGKIR',
                                'no_payroll' => $noPayroll,
                                'dsc_absen' => 'MANGKIR (TIDAK MASUK)',
                                'nama_asli' => $karyawan->nama_asli,
                                'bagian' => $karyawan->bagian,
                                'masuk' => null,
                                'keluar' => null,
                            ];
                        }
                    }
                }
            }

            // Sortir kembali $gabungData setelah penambahan tanggal mangkir
            usort($gabungData, function ($a, $b) {
                // Mengurutkan berdasarkan bagian
                $result = strcmp($a['bagian'], $b['bagian']);

                // Jika bagian sama, urutkan berdasarkan no_payroll
                if ($result === 0) {
                    $result = strcmp($a['nama_asli'], $b['nama_asli']);
                }
                // Jika no_payroll juga sama, urutkan berdasarkan tanggal
                if ($result === 0) {
                    $result = strcmp($a['tanggal'], $b['tanggal']);
                }

                return $result;
            });

            // Now $gabungData is merged, sorted based on 'nama_asli', and 'jns_absen' is filled with "tidak ada ket" if it is null or empty

            $absentlak = $gabungData;

            // dd($gabungData);
        }

        return view('../hr/dashboard/reporttlak/list', compact('tgl_awal', 'tgl_akhir', 'absentlak', 'jns', 'jenis'));
    }
    public function print(Request $request)
    {
        set_time_limit(500);
        $jenis = $request->input('jenis');

        $tgl_awal = $request->input('awal');
        $tgl_akhir = $request->input('akhir');
        $taw = Carbon::parse($tgl_awal)->format('Y-m-d');
        $tak = Carbon::parse($tgl_akhir)->format('Y-m-d');

        $peg = Pegawai::orderBy('no_payroll', 'desc')
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->whereNull('tgl_keluar')
            ->pluck('no_payroll');

        if ($jenis == 'absen tidak lengkap') {
            $jns = 'LAPORAN ABSEN TIDAK LENGKAP';
            $absentlak = Presensi::whereBetween('tanggal', [$taw, $tak])
                ->join('pegawais', 'pegawais.no_payroll', '=', 'presensis.no_reg')
                ->whereIn('presensis.no_reg', $peg)
                ->where(function ($query) {
                    $query->whereRaw('masuk IS NULL OR masuk = ""')->orWhereRaw('keluar IS NULL OR keluar = ""');
                })
                ->get();
        } elseif ($jenis == 'absen kembar') {
            $jns = 'LAPORAN ABSEN KEMBAR';
            $absentlak = Presensi::whereBetween('tanggal', [$taw, $tak])
                ->join('pegawais', 'pegawais.no_payroll', '=', 'presensis.no_reg')
                ->whereIn('presensis.no_reg', $peg)
                ->where(function ($query) {
                    $query
                        ->WhereRaw('masuk = keluar')
                        ->whereRaw('masuk IS NOT NULL AND masuk <> ""')
                        ->WhereRaw('keluar IS NOT NULL AND keluar <> ""');
                })
                ->get();
        } else {
            $jns = 'LAPORAN KARYAWAN TIDKA MASUK HARIAN';

            $tanggalAwal = Carbon::parse($taw);
            $tanggalAkhir = Carbon::parse($tak);

            // tanggal dari awal sampai akhir
            $selisihHari = $tanggalAkhir->diffInDays($tanggalAwal);
            $semuaTanggal = [];
            for ($i = 0; $i <= $selisihHari; $i++) {
                $semuaTanggal[] = $tanggalAwal
                    ->copy()
                    ->addDays($i)
                    ->toDateString();
            }

            $abs_onof = onoff_tg::whereIn('tgl_off', $semuaTanggal)
                ->orWhereIn('tgl_on', $semuaTanggal)
                ->pluck('tgl_on', 'tgl_off')
                ->toArray();
            $abs_lbr = TglLibur::whereIn('tgl_libur', $semuaTanggal)
                ->pluck('tgl_libur')
                ->toArray();

            $tanggalfix = [];
            foreach ($semuaTanggal as $tanggal) {
                // Periksa apakah tanggal tidak ada di abs_pre, abs_d, abs_lbr, abs_onof
                if (!in_array($tanggal, $abs_lbr) && !in_array($tanggal, $abs_onof)) {
                    // Periksa apakah tanggal bukan Sabtu atau Minggu (6 atau 7)
                    $hari = date('N', strtotime($tanggal));
                    if ($hari >= 1 && $hari <= 5) {
                        // 1-5 adalah hari kerja (Senin-Jumat)
                        $tanggalfix[] = $tanggal;
                    }
                }
            }

            // dd($tanggalfix);

            $pegawaiQuery = pegawai::where(function ($query) {
                $query->whereNull('tgl_keluar')->orWhere('tgl_keluar', '');
            })
                ->where('bagian', '!=', 'KEBERIHAN')
                ->where('bagian', '!=', 'SATPAM')
                ->where('bagian', '!=', 'RUMAH TANGGA')
                ->where('bagian', '!=', '')
                ->where('bagian', '!=', 'DIREKSI')
                ->orderBy('no_payroll', 'asc');

            $pegawai = $pegawaiQuery->get(); // Mengambil daftar pegawai

            // Mengumpulkan semua nomor registrasi pegawai
            $noRegistrasiPegawai = $pegawai->pluck('no_payroll')->toArray();

            // Mengambil data absen pegawai
            $absentlak = Presensi::whereIn('presensis.no_reg', $noRegistrasiPegawai)
                ->join('pegawais', 'pegawais.no_payroll', '=', 'presensis.no_reg')
                ->whereIn('tanggal', $semuaTanggal)

                ->where(function ($query) {
                    $query
                        ->where(function ($subQuery) {
                            $subQuery->whereRaw('(masuk IS NULL OR masuk = "")')->orWhereRaw('(keluar IS NULL OR keluar = "")');
                        })
                        ->orWhere(function ($subQuery) {
                            $subQuery
                                ->whereRaw('masuk = keluar')
                                ->whereRaw('masuk IS NOT NULL AND masuk <> ""')
                                ->whereRaw('keluar IS NOT NULL AND keluar <> ""');
                        });
                })
                ->get();

            $absenDataL = Absen_d::whereIn('absen_ds.tgl_absen', $semuaTanggal)
                ->join('absen_hs', 'absen_hs.int_absen', '=', 'absen_ds.int_absen')
                ->join('pegawais', 'pegawais.no_payroll', '=', 'absen_hs.no_payroll')
                ->select('absen_ds.tgl_absen', 'absen_ds.jns_absen', 'absen_hs.no_payroll', 'absen_ds.dsc_absen', 'pegawais.nama_asli', 'pegawais.bagian')
                ->whereIn('absen_ds.tgl_absen', $semuaTanggal)
                ->whereIn('absen_hs.no_payroll', $noRegistrasiPegawai)
                ->get()
                ->toArray();

            $gabungData = [];

            // Menggabungkan data absen dari tabel Presensi
            foreach ($absentlak as $absensi) {
                $dscAbsen = !empty($absensi->masuk) && !empty($absensi->keluar) && $absensi->masuk == $absensi->keluar ? 'ABSENSI KEMBAR' : 'ABSENSI TIDAK LENGKAP';

                $gabungData[] = [
                    'tanggal' => $absensi->tanggal,
                    'jns_absen' => $absensi->jns_absen, // Tidak ada informasi jenis absen dari tabel Presensi
                    'no_payroll' => $absensi->no_reg,
                    'dsc_absen' => $dscAbsen, // Tidak ada deskripsi absen dari tabel Presensi
                    'nama_asli' => $absensi->nama_asli,
                    'bagian' => $absensi->bagian, // Tidak ada informasi bagian dari tabel Presensi
                    'masuk' => $absensi->masuk,
                    'keluar' => $absensi->keluar,
                    // Kolom lain dari tabel Presensi
                ];
            }

            // Menggabungkan data absen dari tabel Absen_d
            foreach ($absenDataL as $absen_d) {
                $gabungData[] = [
                    'tanggal' => $absen_d['tgl_absen'],
                    'jns_absen' => $absen_d['dsc_absen'],
                    'no_payroll' => $absen_d['no_payroll'],
                    'dsc_absen' => $absen_d['dsc_absen'],
                    'nama_asli' => $absen_d['nama_asli'],
                    'bagian' => $absen_d['bagian'],
                    'masuk' => null, // Tidak ada informasi masuk dari tabel Absen_d
                    'keluar' => null, // Tidak ada informasi keluar dari tabel Absen_d
                    // Kolom lain dari tabel Absen_d
                ];
            }

            // Merge and sort data based on 'nama_asli'
            usort($gabungData, function ($a, $b) {
                return strcmp($a['bagian'], $b['bagian']);
            });

            // Loop untuk setiap pegawai
            foreach ($pegawai as $karyawan) {
                $noPayroll = $karyawan->no_payroll;

                // Loop untuk setiap tanggal dalam $tanggalfix
                foreach ($tanggalfix as $tanggal) {
                    $tanggalDitemukan = false;

                    // Cek apakah tanggal ada di $gabungData
                    foreach ($gabungData as $data) {
                        if ($data['no_payroll'] == $noPayroll && $data['tanggal'] == $tanggal) {
                            $tanggalDitemukan = true;
                            break;
                        }
                    }

                    // Jika tanggal tidak ditemukan di $gabungData, cek apakah ada di tabel Presensi
                    if (!$tanggalDitemukan) {
                        // Cek apakah tanggal ada di tabel Presensi
                        $presensiAda = Presensi::where('no_reg', $noPayroll)
                            ->where('tanggal', $tanggal)
                            ->exists();

                        // Jika tidak ada di tabel Presensi, tambahkan data dengan keterangan "mangkir"
                        if (!$presensiAda) {
                            $gabungData[] = [
                                'tanggal' => $tanggal,
                                'jns_absen' => 'MANGKIR',
                                'no_payroll' => $noPayroll,
                                'dsc_absen' => 'MANGKIR (TIDAK MASUK)',
                                'nama_asli' => $karyawan->nama_asli,
                                'bagian' => $karyawan->bagian,
                                'masuk' => null,
                                'keluar' => null,
                            ];
                        }
                    }
                }
            }

            // Sortir kembali $gabungData setelah penambahan tanggal mangkir
            usort($gabungData, function ($a, $b) {
                // Mengurutkan berdasarkan bagian
                $result = strcmp($a['bagian'], $b['bagian']);

                // Jika bagian sama, urutkan berdasarkan no_payroll
                if ($result === 0) {
                    $result = strcmp($a['nama_asli'], $b['nama_asli']);
                }
                // Jika no_payroll juga sama, urutkan berdasarkan tanggal
                if ($result === 0) {
                    $result = strcmp($a['tanggal'], $b['tanggal']);
                }

                return $result;
            });

            // Now $gabungData is merged, sorted based on 'nama_asli', and 'jns_absen' is filled with "tidak ada ket" if it is null or empty

            $absentlak = $gabungData;
        }

        $pdf = Pdf::loadview('../hr/dashboard/reporttlak/print', compact('tgl_awal', 'tgl_akhir', 'absentlak', 'jns', 'jenis'));
        return $pdf->setPaper('a4', 'portrait')->stream('laporanTiAk.pdf');
    }
}