<?php

namespace App\Http\Controllers;

use App\Models\anak_d;
use App\Models\bagian;
use App\Models\course_d;
use App\Models\exp_d;
use App\Models\peg_d;
use App\Models\pegawai;
use App\Models\pnddkand;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DataPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->cari;

        $data = pegawai::orderBy('no_payroll', 'desc')
            ->where('no_payroll', 'LIKE', "%$search%")
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->whereNull('tgl_keluar')
            ->get();

        $data_satpamkebersihan = pegawai::orderBy('no_payroll', 'desc')
            ->where('no_payroll', 'LIKE', "%$search%")
            ->wherein('jns_peg', ['KEBERSIHAN', 'SATPAM'])
            ->get();

            $data_keluar = pegawai::orderBy('tgl_keluar', 'desc')
            ->where('no_payroll', 'LIKE', "%$search%")
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->whereNotNull('tgl_keluar')
            ->get();


        // dd($data_satpamkebersihan);

        return view('/hr/dashboard/pegawai/index', compact('data', 'data_satpamkebersihan', 'data_keluar'));
    }
    public function detail(Request $request, $id)
    {
        $data = pegawai::where('id', $id)->first();
        $data_keluarga = anak_d::where('no_payroll', $data->no_payroll)->get();
        $data_pendidikan = pnddkand::where('no_payroll', $data->no_payroll)->get();
        $data_pelatihan = course_d::where('no_payroll', $data->no_payroll)->get();
        $data_exp = exp_d::where('no_payroll', $data->no_payroll)->get();
        $data_kontrak = peg_d::where('no_payroll', $data->no_payroll)->get();

        return view('/hr/dashboard/pegawai/detail', compact('data', 'data_keluarga', 'data_pendidikan', 'data_pelatihan', 'data_exp', 'data_kontrak'));
    }
    public function create()
    {
        $dept = bagian::select('departemen')
            ->groupBy('departemen')
            ->get();
        $bag = bagian::select('bagian')
            ->groupBy('bagian')
            ->get();

        $maxnik = pegawai::where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->whereNull('tgl_keluar')
            ->max('no_payroll');
        $nik_c = $maxnik + 1;

        return view('/hr/dashboard/pegawai/create', compact('dept', 'bag', 'nik_c'));
    }
    public function create_satpamkebersihan()
    {
        $dept = bagian::select('departemen')
            ->groupBy('departemen')
            ->get();
        $bag = bagian::select('bagian')
            ->groupBy('bagian')
            ->get();

        $maxnik = pegawai::where('jns_peg', 'SATPAM')
            ->orwhere('jns_peg', 'KEBERSIHAN')
            ->whereNull('tgl_keluar')
            ->max('no_payroll');
        $nik_c = $maxnik + 1;

        return view('/hr/dashboard/pegawai/create', compact('dept', 'bag', 'nik_c'));
    }
    public function store(Request $request)
    {
        // dd($request->toArray());

        $request->validate(
            [
                // 'no_payroll' => 'required|unique:pegawais,no_payroll',
                'foto' => 'file|mimes:jpg,jpeg,png,pdf|max:100000',
                'jns_peg' => 'required',
            ],
            [
                'jns_peg.required' => 'Jenis Pegawai Tidak Boleh Kosong.',
            ],
        );

        if ($files = $request->file('foto')) {
            $destinationPath = 'image/fotos/';
            $foto_profil = $files->getClientOriginalName();
            $files->move($destinationPath, $foto_profil);
            $input['foto'] = "$foto_profil";
        } else {
            $foto_profil = 'No foto';
        }

        // pegawai::create($request->all());
        $pegawai = pegawai::create([
            'no_payroll' => $request->no_payroll,
            'nama_asli' => $request->nama_asli,
            'name' => $request->name,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_keluar' => $request->tgl_keluar,
            'jns_peg' => $request->jns_peg,
            'departemen' => $request->departemen,
            'bagian' => $request->bagian,
            'jabatan' => $request->jabatan,
            'golongan' => $request->golongan,
            'transport' => $request->transport,
            'gkcod' => $request->gkcod,
            'npwp' => $request->npwp,
            'email' => $request->email,
            'bpjs_tk' => $request->bpjs_tk,
            'bpjs_kes0' => $request->bpjs_kes0,
            'faskes' => $request->faskes,
            //
            'alamat' => $request->alamat,
            'temp_lahir' => $request->temp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'kota' => $request->kota,
            'telepon' => $request->telepon,
            'daerahasal' => $request->daerahasal,
            'suami_istr' => $request->suami_istr,
            'sex' => $request->sex,
            'gol_darah' => $request->gol_darah,
            'jml_anak' => $request->jml_anak,
            'agama' => $request->agama,
            'sts_nikah' => $request->sts_nikah,
            'ayah' => $request->ayah,
            'ibu' => $request->ibu,
            'foto' => $foto_profil,
        ]);

        if (!is_null($request->nama)) {
            foreach ($request->nama as $key => $value) {
                anak_d::create([
                    'nama' => $value,
                    'no_payroll' => $request->no_payroll,
                    'kelamin' => $request->kelamin[$key],
                    'tgl_lahir' => $request->tgl_lahir_anak[$key],
                    'pendidikan' => $request->pendidikan[$key],
                ]);
            }
        } else {
        }

        if (!is_null($request->id_pendidikan)) {
            foreach ($request->id_pendidikan as $key => $value) {
                pnddkand::create([
                    'id_pendidikan' => $value,
                    'no_payroll' => $request->no_payroll,
                    'tingkat' => $request->tingkat[$key],
                    'tempat' => $request->tempat[$key],
                    'sekolah' => $request->sekolah[$key],
                    'jurusan' => $request->jurusan[$key],
                    'tahun_izs' => $request->tahun_izs[$key],
                    'keterangan' => $request->keterangan_pendidikan[$key],
                ]);
            }
        } else {
        }
        if (!is_null($request->id_pelatihan)) {
            foreach ($request->id_pelatihan as $key => $value) {
                course_d::create([
                    'id_pelatihan' => $value,
                    'no_payroll' => $request->no_payroll,
                    'course_nam' => $request->course_nam[$key],
                    'tanggal' => $request->tanggal_course[$key],
                    'keterangan' => $request->keterangan_course[$key],
                ]);
            }
        } else {
        }
        if (!is_null($request->id_exp)) {
            foreach ($request->id_exp as $key => $value) {
                exp_d::create([
                    'id_exp' => $value,
                    'no_payroll' => $request->no_payroll,
                    'perusahaan' => $request->perusahaan[$key],
                    'alamat' => $request->alamat_exp[$key],
                    'jabatan' => $request->jabatan_exp[$key],
                    'keterangan' => $request->keterangan_exp[$key],
                ]);
            }
        } else {
        }
        if (!is_null($request->no_kontrak)) {
            foreach ($request->no_kontrak as $key => $value) {
                peg_d::create([
                    'no_kontrak' => $value,
                    'no_payroll' => $request->no_payroll,
                    'perpanjang' => $request->perpanjang[$key],
                    'berakhir' => $request->berakhir[$key],
                ]);
            }
        } else {
        }

        return redirect()
            ->route('datapegawai.index')
            ->with('success', 'New subject has been added.');
    }

    public function delete($id)
    {
        $pegawai = pegawai::findOrFail($id);
        $keluarga = anak_d::where('no_payroll', $pegawai->no_payroll);
        $pendidikan = pnddkand::where('no_payroll', $pegawai->no_payroll);
        $pelatihan = course_d::where('no_payroll', $pegawai->no_payroll);
        $exp = exp_d::where('no_payroll', $pegawai->no_payroll);
        $kontrak = peg_d::where('no_payroll', $pegawai->no_payroll);

        $pegawai->delete();
        $keluarga->delete();
        $pendidikan->delete();
        $pelatihan->delete();
        $exp->delete();
        $kontrak->delete();
        return redirect()
            ->route('datapegawai.index')
            ->with('success', 'Data berhasil dihapus!');
    }
    public function edit($id)
    {
        $dept = bagian::select('departemen')
            ->groupBy('departemen')
            ->get();
        $bag = bagian::select('bagian')
            ->groupBy('bagian')
            ->get();
        $id_pdd =  str::random(10);

        $orang = pegawai::select('no_payroll')->where('id', $id)->first();

        
        
        $keluarga_j = anak_d::where('no_payroll', $orang->no_payroll)->get();
        $pendidikan_j = pnddkand::where('no_payroll', $orang->no_payroll)->get();
        $pelatihan_j = course_d::where('no_payroll', $orang->no_payroll)->get();
        $exp_j = exp_d::where('no_payroll', $orang->no_payroll)->get();
        $kontrak_j = peg_d::where('no_payroll', $orang->no_payroll)->get();

        $jmlh_keluarga = count($keluarga_j);
        $jmlh_pendidikan = count($pendidikan_j);
        $jmlh_pelatihan = count($pelatihan_j);
        $jmlh_exp = count($exp_j);
        $jmlh_kontrak = count($kontrak_j);
        // dd($jmlh_keluarga);

        $pegawai = pegawai::findOrFail($id);
        $keluarga = anak_d::where('no_payroll', $pegawai->no_payroll)->get();
        $pendidikan = pnddkand::where('no_payroll', $pegawai->no_payroll)->get();
        $pelatihan = course_d::where('no_payroll', $pegawai->no_payroll)->get();
        $exp = exp_d::where('no_payroll', $pegawai->no_payroll)->get();
        $kontrak = peg_d::where('no_payroll', $pegawai->no_payroll)->get();

        return view('/hr/dashboard/pegawai/edit', compact('id_pdd','pegawai', 'keluarga', 'pendidikan', 'pelatihan', 'exp', 'kontrak', 'dept', 'bag', 'jmlh_keluarga', 'jmlh_pendidikan', 'jmlh_pelatihan', 'jmlh_exp', 'jmlh_kontrak'));
    }

    public function update($id, Request $request)
    {

        // dd($request->toArray());
        $request->validate(
            [
                // 'train_cod' => 'required|unique:train_hs,train_cod',
                'foto' => 'file|mimes:jpg,jpeg,png,pdf|max:100000',
                'jns_peg' => 'required',
            ],
            [
                'jns_peg.required' => 'Jenis Pegawai Tidak Boleh Kosong.',
            ],
        );

        $pegawai_f = pegawai::where('id', $id)->first();
        if ($files = $request->file('foto')) {
            $destinationPath = 'image/fotos/';
            $foto_profil = date('YmdHis') . '4.' . $files->getClientOriginalName();
            $files->move($destinationPath, $foto_profil);
            $input['foto'] = "$foto_profil";
        } else {
            $foto_profil = $pegawai_f->foto;
        }

        // dd($request->toArray());
        $pegawai = pegawai::where('id', $id)->first();
        // $data_d = train_d::all();

        $pegawai->no_payroll = $request->no_payroll;
        $pegawai->nama_asli = $request->nama_asli;
        $pegawai->name = $request->name;
        $pegawai->tgl_masuk = $request->tgl_masuk;
        $pegawai->tgl_keluar = $request->tgl_keluar;
        $pegawai->jns_peg = $request->jns_peg;
        $pegawai->departemen = $request->departemen;
        $pegawai->bagian = $request->bagian;
        $pegawai->jabatan = $request->jabatan;
        $pegawai->golongan = $request->golongan;
        $pegawai->transport = $request->transport;
        $pegawai->gkcod = $request->gkcod;
        $pegawai->npwp = $request->npwp;
        $pegawai->email = $request->email;
        $pegawai->bpjs_tk = $request->bpjs_tk;
        $pegawai->bpjs_kes0 = $request->bpjs_kes0;
        $pegawai->faskes = $request->faskes;

        $pegawai->alamat = $request->alamat;
        $pegawai->temp_lahir = $request->temp_lahir;
        $pegawai->tgl_lahir = $request->tgl_lahir;
        $pegawai->kota = $request->kota;
        $pegawai->telepon = $request->telepon;
        $pegawai->daerahasal = $request->daerahasal;
        $pegawai->suami_istr = $request->suami_istr;
        $pegawai->sex = $request->sex;
        $pegawai->jml_anak = $request->jml_anak;
        $pegawai->agama = $request->agama;
        $pegawai->sts_nikah = $request->sts_nikah;
        $pegawai->ayah = $request->ayah;
        $pegawai->ibu = $request->ibu;
        $pegawai->foto = $foto_profil;
        $pegawai->save();

        if ($request->nama === null) {
        } else {
            foreach ($request->nama as $key => $value) {
                if (isset($request->item_id_keluarga[$key])) {
                    anak_d::where('id', $request->item_id_keluarga[$key])->update([
                        'nama' => $value,
                        'kelamin' => $request->kelamin[$key],
                        'tgl_lahir' => $request->tgl_lahir_anak[$key],
                        'pendidikan' => $request->pendidikan[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                } else {
                    anak_d::create([
                        'nama' => $value,
                        'kelamin' => $request->kelamin[$key],
                        'tgl_lahir' => $request->tgl_lahir_anak[$key],
                        'pendidikan' => $request->pendidikan[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                }
            }
        }
        if ($request->id_pdd === null) {
        } else {
            foreach ($request->id_pdd as $key => $value) {
                if (isset($request->item_id_pendidikan[$key])) {
                    pnddkand::where('id', $request->item_id_pendidikan[$key])->update([
                      
                        'id_pdd' => $value,
                        'tingkat' => $request->tingkat[$key],
                        'sekolah' => $request->sekolah[$key],
                        'tempat' => $request->tempat[$key],
                        'jurusan' => $request->jurusan[$key],
                        'tahun_izs' => $request->tahun_izs[$key],
                        'keterangan' => $request->keterangan_pendidikan[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                } else {
                    pnddkand::create([
                        'id_pdd' => $value,
                        'tingkat' => $request->tingkat[$key],
                        'sekolah' => $request->sekolah[$key],
                        'tempat' => $request->tempat[$key],
                        'jurusan' => $request->jurusan[$key],
                        'tahun_izs' => $request->tahun_izs[$key],
                        'keterangan' => $request->keterangan_pendidikan[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                }
            }
        }
        if ($request->course_nam === null) {
        } else {
            foreach ($request->course_nam as $key => $value) {
                if (isset($request->item_id_pelatihan[$key])) {
                    course_d::where('id', $request->item_id_pelatihan[$key])->update([
                        'course_nam' => $value,
                        'tanggal' => $request->tanggal_course[$key],
                        'keterangan' => $request->keterangan_course[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                } else {
                    course_d::create([
                        'course_nam' => $value,
                        'tanggal' => $request->tanggal_course[$key],
                        'keterangan' => $request->keterangan_course[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                }
            }
        }

        if ($request->perusahaan === null) {
        } else {
            foreach ($request->perusahaan as $key => $value) {
                if (isset($request->item_id_exp[$key])) {
                    exp_d::where('id', $request->item_id_exp[$key])->update([
                        'perusahaan' => $value,
                        'alamat' => $request->alamat_exp[$key],
                        'jabatan' => $request->jabatan_exp[$key],
                        'keterangan' => $request->keterangan_exp[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                } else {
                    exp_d::create([
                        'perusahaan' => $value,
                        'alamat' => $request->alamat_exp[$key],
                        'jabatan' => $request->jabatan_exp[$key],
                        'keterangan' => $request->keterangan_exp[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                }
            }
        }

        if ($request->no_kontrak === null) {
        } else {
            foreach ($request->no_kontrak as $key => $value) {
                if (isset($request->item_id_kontrak[$key])) {
                    peg_d::where('id', $request->item_id_kontrak[$key])->update([
                        'no_kontrak' => $value,
                        'perpanjang' => $request->perpanjang[$key],
                        'berakhir' => $request->berakhir[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                } else {
                    peg_d::create([
                        'no_kontrak' => $value,
                        'perpanjang' => $request->perpanjang[$key],
                        'berakhir' => $request->berakhir[$key],
                        'no_payroll' => $request->no_payroll,
                    ]);
                }
            }
        }

        return redirect()
            ->route('datapegawai.index')
            ->with('success', 'New subject has been added.');
    }

    // delete_detail_pegawai
    public function delete_d_keluarga($id)
    {
        $keluarga = anak_d::findOrFail($id);
        $keluarga->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus!');
    }
    public function delete_d_pendidikan($id)
    {
        $pendidikan = pnddkand::findOrFail($id);
        $pendidikan->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus!');
    }
    public function delete_d_pelatihan($id)
    {
        $pelatihan = course_d::findOrFail($id);
        $pelatihan->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus!');
    }
    public function delete_d_exp($id)
    {
        $exp = exp_d::findOrFail($id);
        $exp->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus!');
    }
    public function delete_d_kontrak($id)
    {
        $kontrak = peg_d::findOrFail($id);
        $kontrak->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus!');
    }

    public function laporan_list(Request $request)
    {
        $r_bulan = [
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

        $r_tahun_s = Carbon::now()->year;
        $now_t = Carbon::now()->year;
        $r_tahun = range($now_t - 10, $now_t + 10);
        $r_tahun = array_reverse($r_tahun);

        if (request()->bulan || request()->tahun) {
            $r_bln = $request->bulan;
            $r_thn = $request->tahun;

            if ($request->bulan == 'Januari') {
                $bln = 1;
            } elseif ($request->bulan == 'Februari') {
                $bln = 2;
            } elseif ($request->bulan == 'Maret') {
                $bln = 3;
            } elseif ($request->bulan == 'April') {
                $bln = 4;
            } elseif ($request->bulan == 'Mei') {
                $bln = 5;
            } elseif ($request->bulan == 'Juni') {
                $bln = 6;
            } elseif ($request->bulan == 'Juli') {
                $bln = 7;
            } elseif ($request->bulan == 'Agustus') {
                $bln = 8;
            } elseif ($request->bulan == 'September') {
                $bln = 9;
            } elseif ($request->bulan == 'Oktober') {
                $bln = 10;
            } elseif ($request->bulan == 'November') {
                $bln = 11;
            } elseif ($request->bulan == 'Desember') {
                $bln = 12;
            }

        
            $thn = $request->tahun;
            $data = pegawai::get();
            $data = DB::table('peg_ds')
                ->join('pegawais', 'pegawais.no_payroll', '=', 'peg_ds.no_payroll')
                ->select('pegawais.nama_asli', DB::raw("DATE_FORMAT(peg_ds.Perpanjang, '%d %M %Y') as perpanjang"), DB::raw("DATE_FORMAT(peg_ds.berakhir, '%d %M %Y') as Berakhir"))
                ->where(DB::raw('YEAR(peg_ds.berakhir)'), $thn)
                ->where(DB::raw('MONTH(peg_ds.berakhir)'), $bln)
                ->where('pegawais.tgl_keluar' , null)
                ->orderBy('peg_ds.berakhir', 'ASC')
                ->get();
        } else {
            $now = Carbon::now();
            $r_bln = $now->translatedFormat('F');

            $r_thn = Carbon::now()->year;
            $bln = Carbon::now()->month;
            $thn = Carbon::now()->year;
            $data = DB::table('peg_ds')
                ->join('pegawais', 'pegawais.no_payroll', '=', 'peg_ds.no_payroll')
                ->select('pegawais.nama_asli', DB::raw("DATE_FORMAT(peg_ds.Perpanjang, '%d %M %Y') as perpanjang"), DB::raw("DATE_FORMAT(peg_ds.berakhir, '%d %M %Y') as Berakhir"))
                ->where(DB::raw('YEAR(peg_ds.berakhir)'), $thn)
                ->where(DB::raw('MONTH(peg_ds.berakhir)'), $bln)
                ->orderBy('peg_ds.berakhir', 'ASC')
                ->get();
        }

        // dd($bln);

        return view('/hr/dashboard/pegawai/kontrak/laporan/list', compact('data', 'r_bulan', 'r_tahun', 'r_tahun_s', 'r_thn', 'r_bln', 'now_t', 'thn'));
    }

    public function print_laporan_list(Request $request, $bln, $thn)
    {
        if ($bln == 'Januari') {
            $bln_a = 1;
        } elseif ($bln == 'Februari') {
            $bln_a = 2;
        } elseif ($bln == 'Maret') {
            $bln_a = 3;
        } elseif ($bln == 'April') {
            $bln_a = 4;
        } elseif ($bln == 'Mei') {
            $bln_a = 5;
        } elseif ($bln == 'Juni') {
            $bln_a = 6;
        } elseif ($bln == 'Juli') {
            $bln_a = 7;
        } elseif ($bln == 'Agustus') {
            $bln_a = 8;
        } elseif ($bln == 'September') {
            $bln_a = 9;
        } elseif ($bln == 'Oktober') {
            $bln_a = 10;
        } elseif ($bln == 'November') {
            $bln_a = 11;
        } elseif ($bln == 'Desember') {
            $bln_a = 12;
        }

        $data = DB::table('peg_ds')
            ->join('pegawais', 'pegawais.no_payroll', '=', 'peg_ds.no_payroll')
            ->select('pegawais.nama_asli', DB::raw("DATE_FORMAT(peg_ds.Perpanjang, '%d %M %Y') as perpanjang"), DB::raw("DATE_FORMAT(peg_ds.berakhir, '%d %M %Y') as Berakhir"))
            ->where(DB::raw('YEAR(peg_ds.berakhir)'), $thn)
            ->where(DB::raw('MONTH(peg_ds.berakhir)'), $bln_a)
            ->orderBy('peg_ds.berakhir', 'ASC')
            ->get();

        $pdf = Pdf::loadview('/hr/dashboard/pegawai/kontrak/laporan/print', compact('data', 'bln', 'thn'));
        return $pdf->setPaper('a4', 'potrait')->stream('Kontrak.pdf');
    }

    public function print(Request $request, $id)
    {
        $data = pegawai::where('id', $id)->first();
        $data_keluarga = anak_d::where('no_payroll', $data->no_payroll)->get();
        $data_pendidikan = pnddkand::where('no_payroll', $data->no_payroll)->get();
        $data_pelatihan = course_d::where('no_payroll', $data->no_payroll)->get();
        $data_exp = exp_d::where('no_payroll', $data->no_payroll)->get();
        $data_kontrak = peg_d::where('no_payroll', $data->no_payroll)->get();

        $pdf = Pdf::loadview('/hr/dashboard/pegawai/print', compact('data', 'data_keluarga', 'data_pendidikan', 'data_pelatihan', 'data_exp', 'data_kontrak'));
        return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
    }
}
