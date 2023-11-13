<?php

namespace App\Http\Controllers;

use App\Models\akses_users;
use App\Models\bagian;
use App\Models\data;
use App\Models\DdataMaster;
use App\Models\jalur;
use App\Models\pegawai;
use App\Models\prob_msd;
use App\Models\prob_msn;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DashboardSuperAdminController extends Controller
{
    public function index()
    {
        $namaprofile = Auth::user();

        $user = DB::table('users')
        ->select(DB::raw('count(*) as jumlah, level, count(level) as lev'))
        ->groupBy('level')
        ->get();

        $jns_peg = DB::table('pegawais')
        ->select(DB::raw('count(*) as jumlah, jns_peg, count(jns_peg) as jp'))
        ->whereNull('tgl_keluar')
        ->groupBy('jns_peg')
        ->get();

        $ker_line = DB::table('prob_msns')
        ->select(DB::raw('count(*) as jumlah, line, count(line) as ln'))
        ->groupBy('line')
        ->get();

        $jml_user = User::count();
        $jml_kerusakan = prob_msd::count();

        return view('superadmin.dashboard.index', compact( 'ker_line','jml_user', 'jml_kerusakan', 'namaprofile', 'user','jns_peg'));
    }

    public function indexUser(User $user)
    {
        $user = User::get();
        $namaprofile = Auth::user();

        return view('superadmin.dashboard.user.index', compact('user', 'namaprofile'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', ''],
            'level' => ['required'],
            'role_id' => ['required'],
        ]);

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'level' => $request['level'],
            'password' => Hash::make($request['password']),
            'role_id' => $request['role_id'],
        ]);

        return back()->with('success', 'User Added successfully');
    }

    public function destroyUser(User $user, $id)
    {
        user::find($id)->delete();

        return back()->with('success', 'User deleted successfully');
    }

    public function editUser($id)
    {
        $user = User::whereId($id)->first();
        $namaprofile = Auth::user();
        
        $data = akses_users::where('iduser', $user->id)
        ->get();
        $jmlh_data_d  = count($data);
        $int_akses = Str::random(10);
        $bag = bagian::select('bagian')->get();

        return view('superadmin.dashboard.user.edit', compact('namaprofile','data','jmlh_data_d','int_akses','bag'))->with('user', $user);
    }
    public function updateUser(Request $request, User $user, $id)
    {
        // dd($request->toArray());
        $user = User::find($id);
        $user->id = $request->id;
        $user->name = $request->name;
        $user->level = $request->level;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();

        if (!empty($request->int_akses)) {
        foreach ($request->int_akses as $key => $value) {
            if (isset($request->id_akses_user[$key])) {
                akses_users::where('id', $request->id_akses_user[$key])->update([
                    'int_akses' => $value,
                    'bagian' => $request->bagian[$key],
                    'iduser' => $request->id,
                  
        
                ]);
            } else {
                akses_users::create([
                    'int_akses' => $value,
                    'bagian' => $request->bagian[$key],
                    'iduser' => $request->id,

                ]);
            }
        }
    }
        return back()->with('success', 'Akses Update successfully');
    }

    public function delete_akses($id){
        akses_users::find($id)->delete();

        return back()->with('success', 'Akses deleted successfully');
    }

    public function userautocomplete(Request $request)
    {
        $namee = [];
        if($request->has('q')){
            $search = $request->q;
            $namee =pegawai::select("id", "nama_asli")
            		->where('nama_asli', 'LIKE', "%$search%")
            		->get();
        }
        return response()->json($namee);
    }

    //profile //////.........................

    public function profileAdmin(Request $request)
    {
        $namaprofile = Auth::user();

        return view('superadmin/dashboard/profile/index', compact('namaprofile'));
    }

    //line / jalur =--------------

    public function lineloogbok()
    {
        $namaprofile = Auth::user();
        $jalur = jalur::select('nama', 'jenis')
            ->orderBy('jenis', 'ASC')
            ->where('jenis', 'Extruder')
            ->get();
        return view('superadmin/dashboard/logbook/line', compact('namaprofile', 'jalur'));
    }

    public function storeJalur(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
        ]);

        jalur::create([
            'nama' => $validatedData['nama'],
            'jenis' => $validatedData['jenis'],
        ]);
        data::create([
            'jalur_id' => $validatedData['nama'],
        ]);
        return back();
    }
    //data-----------------------------------------------------------------------------

    public function indexCariData($jalur_id, Request $request)
    {
        $cari = $request->cari;
        $carit = $request->carit;

        $caridata = data::where('jalur_id', $jalur_id)
            ->where('tanggal', 'like', '%' . $cari . '%')
            ->where('shift', 'like', '%' . $carit . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        $prev = data::orderBy('id', 'desc')
            ->where('jalur_id', $jalur_id)
            ->limit(1)
            ->get();

        // dd($carit);

        $namaprofile = Auth::user();
        $arr = ['jalur_id' => $jalur_id];

        return view('superadmin.dashboard.logbook.pencarian', compact('caridata', 'arr', 'namaprofile', 'prev'));
    }

    public function indexData($jalur_id, Request $request)
    {
        $datas = data::where('jalur_id', $jalur_id)
            // ->limit(8)
            ->orderBy('created_at', 'desc')
            ->simplePaginate(8);

        $awal = data::select('akhir')->where('jalur_id', $jalur_id);
        $dms = DdataMaster::select('shift')
            ->orderBy('id', 'DESC')
            ->first();
        $nilaiakhir = data::where('jalur_id', $jalur_id)
            ->orderBy('id', 'DESC')
            ->first();
        $tgl = Carbon::now()->format('m-d-Y ');
        // $wkt = Carbon::now()->format('H');
        // dd($nilaiakhir->toArray());
        $namaprofile = Auth::user();

        $wkt = 0;

        $prev = data::orderBy('id', 'desc')
            ->where('jalur_id', $jalur_id)
            ->limit(1)
            ->get();
        // dd($wkt);
        if ($wkt >= 7 && $wkt <= 16) {
            $shiftany = 'I';
        } elseif ($wkt >= 17 && $wkt <= 23) {
            $shiftany = 'II';
        } else {
            $shiftany = 'III';
        }

        $arr = ['jalur_id' => $jalur_id];
        return view('superadmin.dashboard.logbook.index', compact('datas', 'awal', 'tgl', 'prev', 'arr', 'shiftany', 'dms', 'nilaiakhir', 'namaprofile'));
    }

    public function insertdata($jalur_id, Request $request)
    {
        $datas = data::where('jalur_id', $jalur_id)->get();

        $nilaiakhir = data::where('jalur_id', $jalur_id)
            ->orderBy('id', 'DESC')
            ->first();

        // dd($nilaiakhir->akhir);

        $dms = DdataMaster::select('shift')
            ->orderBy('id', 'DESC')
            ->first();
        $arr = ['jalur_id' => $jalur_id];

        $tgl = Carbon::now()->format('d-m-Y ');

        return view('data.insert', compact('datas', 'dms', 'tgl', 'arr', 'nilaiakhir'));
    }

    public function insertfielddata($jalur_id, Request $request)
    {
        // $input = $request->all();

        if ($request->tanggal == null) {
            $tgl = Carbon::now()->format('Y-m-d');
        } else {
            $tgl = $request->tanggal;
        }
        $shift = $request->shift;
        $reset = 'No';
        $akhir = 0;

        if ($shift == 1) {
            $abc = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'];
        } elseif ($shift == 2) {
            $abc = ['16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];
        } else {
            $abc = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00'];
        }

        for ($i = 0; $i < count($abc); $i++) {
                // buat capping
            if ($abc[$i] == '08:00' || $abc[$i] == '16:00' || $abc[$i] == '00:00') {
                $reset = 'YES';
            }else{
                $reset = 'NO';
            }

            $tmp[] = [
                'tanggal' => $tgl,
                'awal' => 0,
                'akhir' => $akhir,
                'reset' => $reset,
                'resetw' => 0,
                'shift' => $shift,
                'etc' => $abc[$i],
                'jalur_id' => $jalur_id,
            ];
        }

        //    dd($tmp);
        //    return response()->json($tmp);
        // data::create($tmp);

        $input['jalur_id'] = $jalur_id;
        foreach ($tmp as $key => $value) {
            data::create($value);
        }

        // foreach ($request->addMoreInputFields as $key => $value) {
        //     data::create($value);
        // }

        return redirect()->route('superadmin.index.data', ['jalur_id' => $jalur_id]);
    }

    public function store($jalur_id, Request $request)
    {
        $request->validate([
            'akhir' => 'required',
            'akhirjam' => 'required',
            'akhirmenit' => 'required',
        ]);

        $input = $request->all();
        $input['jalur_id'] = $jalur_id;

        data::create($input);
        return redirect()->route('superadmin.index.data', ['jalur_id' => $jalur_id]);
    }

    public function edit($jalur_id, data $data)
    {
        $arr = ['jalur_id' => $jalur_id];

        return view('data.edit', compact('data', 'arr'));
    }

    public function update($jalur_id, Request $request, data $data)
    {
        if ($request->ajax()) {
            data::find($request->pk)->update([
                $request->name => $request->value,
            ]);

            return redirect()->route('superadmin.batalresetpcsrefresh.data', ['jalur_id' => $data->jalur_id, 'data' => $request->pk]);
        }
    }

    public function deleteTmpData($jalur_id)
    {
        $datas = data::where('jalur_id', $jalur_id)
            ->limit(8)
            ->orderBy('created_at', 'desc')
            ->delete();

        // dd($datas->toArray());

        // data::find($datas)->delete();

        $arr = ['jalur_id' => $jalur_id];

        return back();
    }

    public function reset($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        //   dd($datapro->toArray());
        $i = 0;
        $awala = 0;
        $jumlahpershifta = 0;
        $jumlahperharia = 0;

        foreach ($datapro as $dt) {
            if ($dt->reset == 'YES' && $i > 0) {
                break;
            }

            if ($i == 0) {
                $awal = 0;
                $akhir = $dt->akhir;
                if ($akhir - $awal < 0) {
                    $kalkulasi = $akhir - $awal + 100000;
                } else {
                    $kalkulasi = 0;
                }
                $jumlahpershift = 0;
                $jumlahperhari = 0;
                $reset = 'YES';
            } else {
                if ($dt->akhir == 0) {
                    $awal = $awala;
                } else {
                    $awal = $awala;
                }
                $akhir = $dt->akhir;
                if ($dt->akhir == 0) {
                    $kalkulasi = 0;
                    $jumlahpershift = 0;
                    $jumlahperhari = 0;
                } else {
                    if ($akhir - $awal < 0) {
                        $kalkulasi = $akhir - $awal + 100000;
                    } else {
                        $kalkulasi = $akhir - $awal;
                    }
                    $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                    $jumlahperhari = $jumlahpershifta + ($akhir - $awal) + $jumlahperharia;
                }
                $reset = $dt->reset;
            }

            $awala = $akhir;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;

            // dd($dt->toArray());
            $dt->update([
                'reset' => $reset,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
            ]);

            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('superadmin.index.data', ['jalur_id' => $jalur_id]);
    }

    public function resetwaktu($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        //   dd($datapro->toArray());
        $i = 0;
        $awala = 0;
        $awaljama = 0;
        $awalmenita = 0;
        $jumlahpershifta = 0;
        $jumlahperharia = 0;
        $runtimeshifta = 0;
        $runtimeharia = 0;

        foreach ($datapro as $dt) {
            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + $dt->kalkulasi;
                $jumlahperhari = $awaljam = 0;
                $awalmenit = 0;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = 0;
                $runtimeshift = 0;
                $runtimehari = 0;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = 'YES';
            } else {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                $jumlahperhari = $jumlahpershifta + ($akhir - $awal) + $jumlahperharia;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = $dt->resetw;
            }

            $awala = $akhir;
            $awaljama = $akhirjam;
            $awalmenita = $akhirmenit;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $runtimeshifta = $runtimeshift;
            $runtimeharia = $runtimehari;

            $dt->update([
                'reset' => $reset,
                'resetw' => $resetw,
                // 'awal' => $awal,
                // 'akhir' => $akhir,
                // 'kalkulasi' => $kalkulasi,
                // 'jumlahpershift' => $jumlahpershift,
                // 'jumlahperhari' => $jumlahperhari,
                'awaljam' => $awaljam,
                'awalmenit' => $awalmenit,
                'akhirjam' => $akhirjam,
                'akhirmenit' => $akhirmenit,
                'runtimemenit' => $runtimemenit,
                'runtimeshift' => $runtimeshift,
                'runtimehari' => $runtimehari,
                'spk' => $spk,
                'produk' => $produk,
                'keterangan' => $keterangan,
                'operator' => $operator,
                'etc' => $etc,
            ]);
            if ($data->reset == 'YES') {
                break;
            }

            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('superadmin.index.data', ['jalur_id' => $jalur_id]);
    }

    public function batalresetpcs($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        $prev = data::where('jalur_id', $jalur_id)
            ->where('id', '<', $data->id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($prev->toArray());

        //   dd($datapro->toArray());
        $i = 0;
        $awala = $prev->akhir ? $prev->akhir : $prev->awal;
        $jumlahpershifta = $prev->jumlahpershift;
        $jumlahperharia = $prev->jumlahperhari;
        foreach ($datapro as $dt) {
            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                if (($akhir - $awal)  < 0 ) {
                    $kalkulasi = $akhir - $awal + 100000;
                } else {
                    $kalkulasi = $akhir - $awal;
                }
                $jumlahpershift = $jumlahpershifta + $kalkulasi;
                $jumlahperhari = $jumlahperharia + $jumlahpershift;
                $reset = 'NO';
            } else {
                if ($dt->akhir == 0) {
                    $awal = $awala;
                } else {
                    $awal = $awala;
                }
                $akhir = $dt->akhir;
                if ($dt->akhir == 0) {
                    $kalkulasi = 0;
                    $jumlahpershift = 0;
                    $jumlahperhari = 0;
                } else {
                    if ($akhir - $awal < 0) {
                        $kalkulasi = $akhir - $awal + 100000;
                    } else {
                        $kalkulasi = $akhir - $awal;
                    }
                    $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                    $jumlahperhari = $jumlahpershifta + ($akhir - $awal) + $jumlahperharia;
                }
                $reset = $dt->reset;
            }
            // $awala = $akhir;
            $awala = $akhir ? $akhir : $awala;
          
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $dt->update([
                'reset' => $reset,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
            ]);
            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('superadmin.index.data', ['jalur_id' => $jalur_id]);
    }

    public function batalresettime($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        $prev = data::where('jalur_id', $jalur_id)
            ->where('id', '<', $data->id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($prev->toArray());

        //   dd($datapro->toArray());
        $i = 0;
        $awala = $prev->akhir;
        $awaljama = $prev->akhirjam;
        $awalmenita = $prev->akhirmenit;
        $jumlahpershifta = $prev->jumlahpershift;
        $jumlahperharia = $prev->jumlahperhari;
        $runtimeshifta = $prev->runtimeshift;
        $runtimeharia = $prev->runtimehari;

        foreach ($datapro as $dt) {
            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + $kalkulasi;
                $jumlahperhari = $jumlahperharia + $jumlahpershift;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $runtimemenit;
                $runtimehari = $runtimeharia + $runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = 'NO';
                $resetw = 'NO';
            } else {
                $awal = $awala;
                $akhir = $dt->akhir;
                $kalkulasi = $akhir - $awal;
                $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                $jumlahperhari = $jumlahpershifta + ($akhir - $awal) + $jumlahperharia;
                $awaljam = $awaljama;
                $awalmenit = $awalmenita;
                $akhirjam = $dt->akhirjam;
                $akhirmenit = $dt->akhirmenit;
                $runtimemenit = $akhirjam * 60 + $akhirmenit - ($awaljam * 60 + $awalmenit);
                $runtimeshift = $runtimeshifta + $dt->runtimemenit;
                $runtimehari = $runtimeharia + $dt->runtimeshift;
                $spk = $dt->spk;
                $produk = $dt->produk;
                $keterangan = $dt->keterangan;
                $operator = $dt->operator;
                $etc = $dt->etc;
                $reset = $dt->reset;
                $resetw = $dt->resetw;
            }

            $awala = $akhir;
            $awaljama = $akhirjam;
            $awalmenita = $akhirmenit;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $runtimeshifta = $runtimeshift;
            $runtimeharia = $runtimehari;

            $dt->update([
                'reset' => $reset,
                'resetw' => $resetw,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
                'awaljam' => $awaljam,
                'awalmenit' => $awalmenit,
                'akhirjam' => $akhirjam,
                'akhirmenit' => $akhirmenit,
                'runtimemenit' => $runtimemenit,
                'runtimeshift' => $runtimeshift,
                'runtimehari' => $runtimehari,
                'spk' => $spk,
                'produk' => $produk,
                'keterangan' => $keterangan,
                'operator' => $operator,
                'etc' => $etc,
            ]);

            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('superadmin.index.data', ['jalur_id' => $jalur_id]);
    }

    public function batalresetpcsrefresh($jalur_id, Request $request, data $data)
    {
        $datapro = data::where('id', '>=', $data->id)
            ->where('jalur_id', $jalur_id)
            ->orderBy('id', 'ASC')
            ->get();

        $prev = data::where('jalur_id', $jalur_id)
            ->where('id', '<', $data->id)
            ->orderBy('id', 'desc')
            ->where('akhir', '>', 0)
            ->first();

        $arr = ['jalur_id' => $jalur_id];

        // dd($prev->toArray());

        //   dd($datapro->toArray());

        data::where('id', '>', $prev->id)
            ->where('id', '<', $data->id)
            ->update([
                'awal' => $prev->akhir,
            ]);

        $i = 0;
        $awala = $prev->akhir;
        $jumlahpershifta = $prev->jumlahpershift;
        $jumlahperharia = $prev->jumlahperhari;
        foreach ($datapro as $dt) {

            if ($dt->reset == 'YES') {
                break;
            } 
            if ($i == 0) {
                $awal = $awala;
                $akhir = $dt->akhir;
                if (($akhir - $awal)  < 0 ) {
                    $kalkulasi = $akhir - $awal + 100000;
                } else {
                    $kalkulasi = $akhir - $awal;
                }
                $jumlahpershift = $jumlahpershifta + $kalkulasi;
                $jumlahperhari = $jumlahperharia + $jumlahpershift;
                $reset = 'NO';
            } else {
                if ($dt->akhir == 0) {
                    $awal = $awala;
                } else {
                    $awal = $awala;
                }
                $akhir = $dt->akhir;
                if ($dt->akhir == 0) {
                    $kalkulasi = 0;
                    $jumlahpershift = 0;
                    $jumlahperhari = 0;
                } else {
                    if ($akhir - $awal < 0) {
                        $kalkulasi = $akhir - $awal + 100000;
                    } else {
                        $kalkulasi = $akhir - $awal;
                    }
                    $jumlahpershift = $jumlahpershifta + ($akhir - $awal);
                    $jumlahperhari = $jumlahpershifta + ($akhir - $awal) + $jumlahperharia;
                }
                $reset = $dt->reset;
            }
            // $awala = $akhir;
            $awala = $akhir ? $akhir : $awala;
            $jumlahpershifta = $jumlahpershift;
            $jumlahperharia = $jumlahperhari;
            $dt->update([
                'reset' => $reset,
                'awal' => $awal,
                'akhir' => $akhir,
                'kalkulasi' => $kalkulasi,
                'jumlahpershift' => $jumlahpershift,
                'jumlahperhari' => $jumlahperhari,
            ]);
            $i++;
        }

        // $data->update($request->all());

        return redirect()->route('superadmin.index.data', ['jalur_id' => $jalur_id]);
    }
}
