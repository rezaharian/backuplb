<?php

namespace App\Http\Controllers;

use App\Models\peg_d;
use App\Models\pegawai;
use App\Models\Presensi;
use App\Models\Presesnsi;
use App\Models\Tdkabsen;
use App\Models\TglLibur;
use App\Models\Timework;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TdkAbsenController extends Controller
{
    public function list()
    {
        $tdkabsen = Tdkabsen::select('id', 'ta_cod', DB::raw("DATE_FORMAT(tdkabsens.ta_tgl, '%d-%m-%Y') AS ta_tgl"), 'no_payroll', 'nama_asli', 'pdsaat', 'masuk', 'pulang', 'gkcod', 'status')
            ->orderBy('id', 'desc')
            ->get();

        $kodeakhir = Tdkabsen::select('ta_cod')
            ->orderby('id', 'desc')
            ->first();

        $thn_sa = Carbon::now()->format('y');
        $bln_sa = Carbon::now()->format('m');
        $na = $kodeakhir->ta_cod ?? $thn_sa . $bln_sa . sprintf('%03s', 0);

        $thna_s = substr($na, 0, 2);
        $thn_s = Carbon::now()->format('y');
        $bln_s = Carbon::now()->format('m');
        $urut_p = substr($na, 4, 3);
        $urut_p++;
        if ($thna_s != $thn_s) {
            $nmr_d = 001;
        } else {
            $nmr_d = $urut_p;
        }
        $kode = $thn_s . $bln_s . sprintf('%03s', $nmr_d);

        return view('hr.dashboard.tdkabsen.list', compact('tdkabsen', 'kode'));
    }

    public function create(Request $request)
    {
        $msk = date('G', strtotime($request->masuk)) * 60 + date('i', strtotime($request->masuk));
        $klr = date('G', strtotime($request->keluar)) * 60 + date('i', strtotime($request->keluar));

        $exists = Presensi::where('no_reg', $request->no_payroll)
            ->where('tanggal', $request->ta_tgl)
            ->exists();

        if ($exists) {
            $presensi = Presensi::where('no_reg', $request->no_payroll)
                ->where('tanggal', $request->ta_tgl)
                ->first();

            if (($request->has('masuk') && !empty($request->masuk) && !empty($presensi->masuk)) || ($request->has('keluar') && !empty($request->keluar) && !empty($presensi->keluar))) {
                return redirect()
                    ->route('hr.tdkabsen.list')
                    ->with('error', 'Data Presensi Sudah Ada');
            }
        }

        $tdkabsen = Tdkabsen::where('ta_tgl', $request->ta_tgl)
            ->where('no_payroll', $request->no_payroll)
            ->first();
        if ($tdkabsen) {
            // Data sudah ada di database
            return redirect()
                ->back()
                ->with('error', 'Data Tidak Absen sudah tersedia  ');
        } else {
            // Data belum ada di database, bisa dilakukan create

            $peg = pegawai::where('no_payroll', $request->no_payroll)->first();
            $time = Timework::where('tw_cod', $peg->gkcod)->first();

            if ($time->tw_qty == 0) {
                $m = $time->tw_ins;
                $k = $time->tw_out;

                if ($msk > $time->vins01 && $klr < $time->vout02) {
                    $stt = 'MT - PA';
                } elseif ($msk <= $time->vins01 && $klr >= $time->vout02) {
                    $stt = 'MN - PN';
                } elseif ($msk <= $time->vins01 && $klr < $time->vout02) {
                    $stt = 'MN - PA';
                } elseif ($msk > $time->vins01 && $klr >= $time->vout02) {
                    $stt = 'MT - PN';
                } else {
                    $stt = 'UNKNOW';
                }
            }
            if ($time->tw_qty != 0) {
                $time1 = Timework::where('tw_cod', $peg->gkcod)
                    ->where(function ($query) use ($msk, $klr, &$stt) {
                        $query
                            ->where(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins01', '>=', $msk)->where('vout02', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins01', '>=', $msk)->where('vout01', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins02', '>=', $msk)->where('vout02', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins02', '>=', $msk)->where('vout01', '<=', $klr);
                            });
                    })
                    ->first();

                if ($msk > $time1->vins01 && $klr < $time1->vout02) {
                    $stt = 'MT - PA';
                } elseif ($msk <= $time1->vins01 && $klr >= $time1->vout02) {
                    $stt = 'MN - PN';
                } elseif ($msk <= $time1->vins01 && $klr < $time1->vout02) {
                    $stt = 'MN - PA';
                } elseif ($msk > $time1->vins01 && $klr >= $time1->vout02) {
                    $stt = 'MT - PN';
                } else {
                    $stt = 'UNKNOW';
                }
            }
            $tdkabsen = Tdkabsen::create([
                'ta_cod' => $request->ta_cod,
                'gkcod' => $peg->gkcod,
                'ta_tgl' => $request->ta_tgl,
                'no_payroll' => $request->no_payroll,
                'nama_asli' => $request->nama_asli,
                'pdsaat' => $request->pdsaat,
                'masuk' => $request->masuk,
                'pulang' => $request->keluar,
                'status' => $stt,
            ]);
        }

        $presensi = Presensi::where('no_reg', $request->no_payroll)
            ->where('tanggal', $request->ta_tgl)
            ->first();

        if (!$presensi) {
            $peg = pegawai::where('no_payroll', $request->no_payroll)->first();
            $time = Timework::where('tw_cod', $peg->gkcod)->first();

            if ($time->tw_qty == 0) {
                $m = $time->tw_ins;
                $k = $time->tw_out;

                if ($msk > $time->vins01 && $klr < $time->vout02) {
                    $stt = 'MT - PA';
                } elseif ($msk <= $time->vins01 && $klr >= $time->vout02) {
                    $stt = 'MN - PN';
                } elseif ($msk <= $time->vins01 && $klr < $time->vout02) {
                    $stt = 'MN - PA';
                } elseif ($msk > $time->vins01 && $klr >= $time->vout02) {
                    $stt = 'MT - PN';
                } else {
                    $stt = 'UNKNOW';
                }
            }
            if ($time->tw_qty != 0) {
                $time1 = Timework::where('tw_cod', $peg->gkcod)
                    ->where(function ($query) use ($msk, $klr, &$stt) {
                        $query
                            ->where(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins01', '>=', $msk)->where('vout02', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins01', '>=', $msk)->where('vout01', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins02', '>=', $msk)->where('vout02', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins02', '>=', $msk)->where('vout01', '<=', $klr);
                            });
                    })
                    ->first();

                if ($msk > $time1->vins01 && $klr < $time1->vout02) {
                    $stt = 'MT - PA';
                } elseif ($msk <= $time1->vins01 && $klr >= $time1->vout02) {
                    $stt = 'MN - PN';
                } elseif ($msk <= $time1->vins01 && $klr < $time1->vout02) {
                    $stt = 'MN - PA';
                } elseif ($msk > $time1->vins01 && $klr >= $time1->vout02) {
                    $stt = 'MT - PN';
                } else {
                    $stt = 'UNKNOW';
                }

                $m = $time1->tw_ins;
                $k = $time1->tw_out;
            }

            $pre = Presensi::create([
                'no_reg' => $request->no_payroll,
                'tanggal' => $request->ta_tgl,
                'no_payroll' => $request->no_payroll,
                'masuk' => $request->masuk,
                'keluar' => $request->keluar,
                'norm_m' => $m,
                'norm_k' => $k,
                'lama' => '0',
                'lembur' => '0',
                'gkcod' => $peg->gkcod,
            ]);

            $presensi = $pre;
        }
        $peg = pegawai::where('no_payroll', $request->no_payroll)->first();
        $time1 = Timework::where('tw_cod', $peg->gkcod)
            ->where(function ($query) use ($msk, $klr, &$stt) {
                $query
                    ->where(function ($query) use ($msk, $klr, &$stt) {
                        $query->where('vins01', '>=', $msk)->where('vout02', '<=', $klr);
                    })
                    ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                        $query->where('vins01', '>=', $msk)->where('vout01', '<=', $klr);
                    })
                    ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                        $query->where('vins02', '>=', $msk)->where('vout02', '<=', $klr);
                    })
                    ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                        $query->where('vins02', '>=', $msk)->where('vout01', '<=', $klr);
                    });
            })
            ->first();

        if ($msk > $time1->vins01 && $klr < $time1->vout02) {
            $stt = 'MT - PA';
        } elseif ($msk <= $time1->vins01 && $klr >= $time1->vout02) {
            $stt = 'MN - PN';
        } elseif ($msk <= $time1->vins01 && $klr < $time1->vout02) {
            $stt = 'MN - PA';
        } elseif ($msk > $time1->vins01 && $klr >= $time1->vout02) {
            $stt = 'MT - PN';
        } else {
            $stt = 'UNKNOW';
        }

        $m = $time1->tw_ins;
        $k = $time1->tw_out;

        if (!is_null($request->masuk)) {
            $presensi->masuk = $request->masuk;
            $presensi->norm_m = $m;
            $presensi->norm_k = $k;
        }

        if (!is_null($request->keluar)) {
            $presensi->keluar = $request->keluar;
            $presensi->norm_m = $m;
            $presensi->norm_k = $k;
        }

        if (!$exists) {
            // hanya update norm_m dan norm_k jika data presensi baru dibuat
            $peg = pegawai::where('no_payroll', $request->no_payroll)->first();
            $time = Timework::where('tw_cod', $peg->gkcod)->first();

            // dd($time);
            if ($time->tw_qty == 0) {
                $m = $time->tw_ins;
                $k = $time->tw_out;
                if ($msk > $time->vins01 && $klr < $time->vout02) {
                    $stt = 'MT - PA';
                } elseif ($msk <= $time->vins01 && $klr >= $time->vout02) {
                    $stt = 'MN - PN';
                } elseif ($msk <= $time->vins01 && $klr < $time->vout02) {
                    $stt = 'MN - PA';
                } elseif ($msk > $time->vins01 && $klr >= $time->vout02) {
                    $stt = 'MT - PN';
                } else {
                    $stt = 'UNKNOW';
                }
            }
            if ($time->tw_qty != 0) {
                $time1 = Timework::where('tw_cod', $peg->gkcod)
                    ->where(function ($query) use ($msk, $klr, &$stt) {
                        $query
                            ->where(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins01', '>=', $msk)->where('vout02', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins01', '>=', $msk)->where('vout01', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins02', '>=', $msk)->where('vout02', '<=', $klr);
                            })
                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                $query->where('vins02', '>=', $msk)->where('vout01', '<=', $klr);
                            });
                    })
                    ->first();

                if ($msk > $time1->vins01 && $klr < $time1->vout02) {
                    $stt = 'MT - PA';
                } elseif ($msk <= $time1->vins01 && $klr >= $time1->vout02) {
                    $stt = 'MN - PN';
                } elseif ($msk <= $time1->vins01 && $klr < $time1->vout02) {
                    $stt = 'MN - PA';
                } elseif ($msk > $time1->vins01 && $klr >= $time1->vout02) {
                    $stt = 'MT - PN';
                } else {
                    $stt = 'UNKNOW';
                }
                $m = $time1->tw_ins;
                $k = $time1->tw_out;
            }

            $presensi->norm_m = $m;
            $presensi->norm_k = $k;
        }

        $presensi->save();

        return redirect()
            ->route('hr.tdkabsen.list')
            ->with('success', 'New Data has been added.');
    }

    public function edit($id, Request $request)
    {
        $tdkabsen = Tdkabsen::findorfail($id);

        return view('hr/dashboard/tdkabsen/edit', compact('tdkabsen'));
    }

    public function update(Request $request, $id)
    {
        $msk = date('G', strtotime($request->masuk)) * 60 + date('i', strtotime($request->masuk));
        $klr = date('G', strtotime($request->pulang)) * 60 + date('i', strtotime($request->pulang));

        $tdkabsen = Tdkabsen::findOrFail($id);

        $tdkabsen->ta_tgl = $request->ta_tgl;
        $tdkabsen->no_payroll = $request->no_payroll;
        $tdkabsen->nama_asli = $request->nama_asli;
        $tdkabsen->pdsaat = $request->pdsaat;
        $tdkabsen->masuk = $request->masuk;
        $tdkabsen->pulang = $request->pulang;
        $tdkabsen->save();

        $presensi = Presensi::where('no_reg', $tdkabsen->no_payroll)
            ->where('tanggal', $tdkabsen->ta_tgl)
            ->first();

        $peg = pegawai::where('no_payroll', $request->no_payroll)->first();
        $time = Timework::where('tw_cod', $peg->gkcod)->first();

        if ($time->tw_qty == 0) {
            $m = $time->tw_ins;
            $k = $time->tw_out;
            if ($msk > $time->vins01 && $klr < $time->vout02) {
                $stt = 'MT - PA';
            } elseif ($msk <= $time->vins01 && $klr >= $time->vout02) {
                $stt = 'MN - PN';
            } elseif ($msk <= $time->vins01 && $klr < $time->vout02) {
                $stt = 'MN - PA';
            } elseif ($msk > $time->vins01 && $klr >= $time->vout02) {
                $stt = 'MT - PN';
            } else {
                $stt = 'UNKNOW';
            }
        }
        if ($time->tw_qty != 0) {
            $time1 = Timework::where('tw_cod', $peg->gkcod)
                ->where(function ($query) use ($msk, $klr, &$stt) {
                    $query
                        ->where(function ($query) use ($msk, $klr, &$stt) {
                            $query->where('vins01', '>=', $msk)->where('vout02', '<=', $klr);
                        })
                        ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                            $query->where('vins01', '>=', $msk)->where('vout01', '<=', $klr);
                        })
                        ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                            $query->where('vins02', '>=', $msk)->where('vout02', '<=', $klr);
                        })
                        ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                            $query->where('vins02', '>=', $msk)->where('vout01', '<=', $klr);
                        });
                })
                ->first();

            if ($msk > $time1->vins01 && $klr < $time1->vout02) {
                $stt = 'MT - PA';
            } elseif ($msk <= $time1->vins01 && $klr >= $time1->vout02) {
                $stt = 'MN - PN';
            } elseif ($msk <= $time1->vins01 && $klr < $time1->vout02) {
                $stt = 'MN - PA';
            } elseif ($msk > $time1->vins01 && $klr >= $time1->vout02) {
                $stt = 'MT - PN';
            } else {
                $stt = 'UNKNOW';
            }

            if ($time1) {
                // tambahkan pengecekan ini
                $m = $time1->tw_ins;
                $k = $time1->tw_out;
            }
        }

        $tdkabsen->ta_tgl = $request->ta_tgl;
        $tdkabsen->no_payroll = $request->no_payroll;
        $tdkabsen->nama_asli = $request->nama_asli;
        $tdkabsen->pdsaat = $request->pdsaat;
        $tdkabsen->masuk = $request->masuk;
        $tdkabsen->pulang = $request->pulang;
        $tdkabsen->status = $stt;
        $tdkabsen->save();

        if (!$presensi) {
            $presensi = new Presensi();
            $presensi->tanggal = $request->ta_tgl;
            $presensi->no_reg = $request->no_payroll;
            $presensi->norm_m = $m;
            $presensi->norm_k = $k;
            $presensi->masuk = $request->masuk;
            $presensi->keluar = $request->pulang;
            $presensi->lama = '0';
            $presensi->lembur = '0';
            $presensi->gkcod = $peg->gkcod;
        }

        $presensi->tanggal = $request->ta_tgl;
        $presensi->no_reg = $request->no_payroll;
        $presensi->norm_m = $m;
        $presensi->norm_k = $k;
        if (!empty($request->masuk)) {
            $presensi->masuk = $request->masuk;
        }
        if (!empty($request->pulang)) {
            $presensi->keluar = $request->pulang;
        }
        $presensi->lama = '0';
        $presensi->lembur = '0';
        $presensi->gkcod = $peg->gkcod;
        $presensi->save();

        return redirect()
            ->route('hr.tdkabsen.list')
            ->with('success', 'Data has been Update.');
    }

    public function delete($id)
    {
        $tdkabsen = Tdkabsen::findOrFail($id);
        $tdkabsen->delete();

        $presensi = Presensi::where('no_reg', $tdkabsen->no_payroll)
            ->where('tanggal', $tdkabsen->ta_tgl)
            ->first();

        if ($presensi) {
            $presensi->delete();
        }

        return redirect()
            ->route('hr.tdkabsen.list')
            ->with('success', 'Data Berhasil di Hapus');
    }

    public function autocompleted_tdkabsen(Request $request)
    {
        $pegawai = [];
        if ($request->has('q')) {
            $search = $request->q;
            $employees = Pegawai::where(function($query) use ($search) {
                $query->where('no_payroll', 'like', "%$search%")
                      ->orWhere('nama_asli', 'like', "%$search%");
            })
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->where('bagian', '!=', 'DIREKSI')
            ->whereNull('tgl_keluar')
            ->get();
            
        }
        return response()->json($pegawai);
    }
}
