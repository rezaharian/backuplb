<?php

namespace App\Http\Controllers;

use App\Models\kompe_d;
use App\Models\pegawai;
use App\Models\prob_msd;
use App\Models\prob_msn;
use App\Models\train_d;
use App\Models\train_h;
use App\Models\Trainer;
use App\Models\unit_msn;
use App\Models\User;
use App\Models\vmacunit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    public function index()
    {
        $namaprofile = Auth::user();

        return view('hr.dashboard.training.trainer.index', compact('namaprofile'));
    }

    public function list()
    {
        $namaprofile = Auth::user();
        $training = train_h::orderby('id', 'desc')
            ->select('id', 'train_cod', 'pemateri', 'train_tema', 'kompetensi', DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), 'tipe', 'approve')
            ->where('train_hs.pemateri', $namaprofile->name)
            ->get();
        $namaprofile = Auth::user();
        return view('hr.dashboard.training.trainer.list', compact('training', 'namaprofile'));
    }

    public function view($id)
    {
        $view = train_h::findorfail($id);
        $view_d = train_d::where('train_cod', $view->train_cod)->get();
        $namaprofile = Auth::user();
        return view('hr.dashboard.training.trainer.view', compact('view', 'view_d', 'namaprofile'));
    }

    public function create(Request $request)
    {
        $namaprofile = Auth::user();

        $kompe_d = kompe_d::orderBy('kompetensi', 'asc')->get();
        $train_h = train_h::select('id', 'train_cod')
            ->orderby('id', 'desc')
            ->first();
        $pegawai = pegawai::select('id', 'nama', 'nama_asli', 'no_payroll')->get();
        $data = pegawai::select('nama', 'nama_asli', 'no_payroll')->get();
        $pemateri = User::select('name')->get();

        // pecah no akhir
        $noakhir = $train_h->train_cod;
        $tra_p = substr($noakhir, 0, 3);
        $thna_p = substr($noakhir, 3, 2);
        $bln_p = substr($noakhir, 6, 2);
        $urut_p = substr($noakhir, 8, 3);
        // tahun
        $thn = Carbon::now()->format('Y');
        $bln = Carbon::now()->format('m');
        $thn_p = substr($thn, 2, 2);
        $urut_p++;

        if ($thna_p != $thn_p) {
            $nmr_d = 001;
        } else {
            $nmr_d = $urut_p;
        }

        // dd($nmr_d);
        // dd($tra_p . $thn_p . $bln . $nmr_d);
        $no = $tra_p . $thn_p . $bln . sprintf('%03s', $nmr_d);
        return view('hr.dashboard.training.trainer.create', compact('kompe_d', 'no', 'pegawai', 'data', 'namaprofile', 'pemateri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'train_cod' => 'required|unique:train_hs,train_cod',
            'file' => 'file|mimes:doc,docx,ppt,pptx,xlsx,xlsx,pdf|max:10000',
        ]);

        if ($files = $request->file('file')) {
            $destinationPath = 'files/';
            $files_tr = $files->getClientOriginalName();
            $files->move($destinationPath, $files_tr);
            $input['file'] = "$files_tr";
        } else {
            $files_tr = 'No File';
        }
        $kompe_h = train_h::create([
            'train_cod' => $request->train_cod,
            'pemateri' => $request->pemateri,
            'tempat' => $request->tempat,
            'pemateri' => $request->pemateri,
            'pemateri' => $request->pemateri,
            'pltran_nam' => $request->pltran_nam,
            'kompetensi' => $request->kompetensi,
            'train_dat' => $request->train_dat,
            'jam' => $request->jam,
            'sdjam' => $request->sdjam,
            'train_tema' => $request->train_tema,
            'approve' => $request->approve_h,
            'tipe' => $request->tipe,
            'file' => $files_tr,
        ]);

        foreach ($request->no_payroll as $key => $value) {
            train_d::create([
                'no_payroll' => $value,
                'nama_asli' => $request->nama_asli[$key],
                'nilai_pre' => $request->nilai_pre[$key],
                'nilai' => $request->nilai[$key],
                'keterangan' => $request->keterangan[$key],
                'approve' => $request->approve[$key],
                'train_cod' => $request->train_cod,
                'train_dat' => $request->train_dat,
            ]);
        }

        return redirect()
            ->route('trainer.list')
            ->with('success', 'New subject has been added.');
    }

    public function edit($id)
    {
        $data = train_h::findorfail($id);
        $data_d = train_d::where('train_cod', $data->train_cod)
            ->orderby('id', 'asc')
            ->get();
        $jmlh_d = count($data_d);
        $namaprofile = Auth::user();
        $pemateri = User::select('name')->get();

        return view('hr.dashboard.training.trainer.edit', compact('data', 'data_d', 'jmlh_d', 'namaprofile', 'pemateri'));
    }

    public function update($id, Request $request, train_d $train_d, train_h $train_h)
    {
        $request->validate([
            'file' => 'file|mimes:doc,docx,ppt,pptx,xlsx,xlsx,pdf|max:10000',
        ]);
        $data_h = train_h::where('id', $id)->first();
        $data_d = train_d::all();

        $data_h->train_cod = $request->train_cod;
        $data_h->tempat = $request->tempat;
        $data_h->pemateri = $request->pemateri;
        $data_h->pltran_nam = $request->pltran_nam;
        $data_h->tipe = $request->tipe;
        $data_h->kompetensi = $request->kompetensi;
        $data_h->train_dat = $request->train_dat;
        $data_h->jam = $request->jam;
        $data_h->sdjam = $request->sdjam;
        $data_h->train_tema = $request->train_tema;
        $data_h->approve = $request->approve;
        $data_h->save();

        foreach ($request->no_payroll as $key => $value) {
            if (isset($request->item_id[$key])) {
                train_d::where('id', $request->item_id[$key])->update([
                    'no_payroll' => $value,
                    'nama_asli' => $request->nama_asli[$key],
                    'nilai_pre' => $request->nilai_pre[$key],
                    'nilai' => $request->nilai[$key],
                    'keterangan' => $request->keterangan[$key],
                    'approve' => $request->approved[$key],
                    'train_cod' => $request->train_cod,
                    'train_dat' => $request->train_dat,
                ]);
            } else {
                train_d::create([
                    'no_payroll' => $value,
                    'nama_asli' => $request->nama_asli[$key],
                    'nilai_pre' => $request->nilai_pre[$key],
                    'nilai' => $request->nilai[$key],
                    'keterangan' => $request->keterangan[$key],
                    'approve' => $request->approved[$key],
                    'train_cod' => $request->train_cod,
                    'train_dat' => $request->train_dat,
                ]);
            }
        }
        return redirect()
            ->route('trainer.list')
            ->with('success', 'New subject has been added.');
    }

    public function autocomplete(Request $request)
    {
        $pegawai = [];
        if ($request->has('q')) {
            $search = $request->q;
            $pegawai = pegawai::select('id', 'no_payroll', 'nama_asli')
                ->where('nama_asli', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($pegawai);
    }
    public function print($id)
    {
        $view = train_h::findorfail($id);
        // $view_d = train_d::where('train_cod', $view->train_cod)->get();
        $tgl = carbon::now()->format('d-m-Y');
        // $bag = pegawai::where('no_payroll', $view_d->no_payroll)->get();
        // dd($view_d->toArray());
        // dd($view->toArray());

        $view_d = train_d::join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
            ->where('train_cod', $view->train_cod)
            ->get();

        $pdf = Pdf::loadview('hr.dashboard.training.trainer.print', compact('view', 'view_d', 'tgl'));
        return $pdf->setPaper('a4', 'potrait')->stream('DaftarHadir.pdf');
    }

    public function train_trash($id, train_d $data_d)
    {
        $data = train_h::findorfail($id);
        $data_d = train_d::onlyTrashed()
            ->where('train_cod', $data->train_cod)
            ->orderby('id', 'asc')
            ->get();
        $data_dd = train_d::onlyTrashed()
            ->where('train_cod', $data->train_cod)
            ->orderby('id', 'asc')
            ->limit(1)
            ->get();

        // dd($data_d->toArray());
        $namaprofile = Auth::user();

        return view('hr.dashboard.training.trainer.train_trash', compact('data_d', 'data_dd', 'namaprofile'));
    }

    public function restore_trash($id, train_d $data_d)
    {
        $data_d = train_d::onlyTrashed()->where('id', $id);
        // dd($data_d->toArray());
        $data_d->restore();
        return back()->with('success', 'Data berhasil dipulihkan!');
    }

    public function restore_all($id, train_d $data_d)
    {
        $data_d_all = train_d::onlyTrashed()->where('train_cod', $id);
        $data_d_all->restore();
        return back()->with('success', 'Data berhasil dipulihkan!');
    }
    public function delete($id, train_h $train_d)
    {
        $trainh = train_h::findOrFail($id);
        $traind = train_d::where('train_cod', $trainh->train_cod);
        $trainh->delete();
        $traind->delete();
        return redirect()
            ->route('trainer.list')
            ->with('success', 'Data berhasil dihapus!');
    }

    public function delete_d($id, train_d $train_d)
    {
        $train = train_d::findOrFail($id);
        $train->delete();
        return back();
    }

    public function msnlist(Request $request)
    {
        // dd($request->toArray());
        $datau = unit_msn::orderby('id', 'DESC')->get();
        $datal = vmacunit::orderby('id', 'DESC')->get();

        $line = $request->cariline;
        $umsn = $request->cariunitmsn;
        $masalah = $request->masalah;
        $penyebab = $request->penyebab;

        if ($line == null) {
            $list = prob_msn::join('prob_msds', 'prob_msns.prob_cod', '=', 'prob_msds.prob_cod')
                ->where('line', 'like', '%' . $line . '%')
                ->where('unitmesin', 'like', '%' . $umsn . '%')
                ->where('masalah', 'like', '%' . $masalah . '%')
                ->where('penyebab', 'like', '%' . $penyebab . '%')
                ->orderby('prob_msns.id', 'desc')
                ->get(['prob_msns.*', 'prob_msds.*']);
        } else {
            # code...
            $list = prob_msn::join('prob_msds', 'prob_msns.prob_cod', '=', 'prob_msds.prob_cod')
                ->where('line', $line)
                ->where('unitmesin', 'like', '%' . $umsn . '%')
                ->where('masalah', 'like', '%' . $masalah . '%')
                ->where('penyebab', 'like', '%' . $penyebab . '%')
                ->orderby('prob_msns.id', 'desc')
                ->get(['prob_msns.*', 'prob_msds.*']);
        }

        // $list = prob_msd::get();

        $namaprofile = Auth::user();
        return view('hr.dashboard.training.trainer.msn.msnlist', compact('namaprofile', 'list', 'datau', 'datal', 'list'));
    }

    public function msnlist_d($id)
    {
        $v = prob_msd::findorfail($id);
        $view = prob_msn::where('prob_cod', $v->prob_cod)->first();
        $view_d = prob_msd::where('id', $id)->first();

        $namaprofile = Auth::user();

        return view('hr.dashboard.training.trainer.msn.msnlist_d', compact('view', 'view_d', 'namaprofile'));
    }
    public function print_d($id)
    {
        $view = prob_msn::findorfail($id);
        $view_d = prob_msd::where('prob_cod', $view->prob_cod)->get();

        $pdf = Pdf::loadview('hr.dashboard.training.trainer.msn.print_d', compact('view', 'view_d'));
        return $pdf->setPaper('a4', 'potrait')->stream('Kerusakan_d.pdf');
    }

    // Laporan Training

    public function rek_tra(Request $request)
    {
        // dd($request->toArray());s

        Carbon::setLocale('id');

        $nik = $request->no_payroll;

        $aw = $request->train_dat_awal;
        $ak = $request->train_dat_akhir;
        $jenis = $request->jns_tr;
        $nm = $request->no_payroll;

        // $namaprofile = Auth::user();

        // dd($namaprofile);
        $akses = null;

        $namaprofile = Auth::user();
        $user = Auth::user();
        $data_akses = DB::table('users')
        ->join('akses_users', 'users.id', '=', 'akses_users.iduser')
        ->where('akses_users.iduser', '=', $user->id)
        ->pluck('bagian');
        $akses = $data_akses;
        $akses = $akses ?? [];
        // dd($data_akses);

        // if ($namaprofile->name == 'Dessy Tiara Ningrum') {
        //     $akses = ['PROD. LANGSUNG', 'PROD. TAK LANGSUNG', 'GUDANG', 'PREPARASI', 'PROD.LANGSUNG TINTA'];
        // } elseif ($namaprofile->name == 'Haryono') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Wargino') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Bambang Eko') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Sony Trijoko') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Ellist Ocataviani') {
        //     $akses = ['Q C'];
        // } elseif ($namaprofile->name == 'MINTEN') {
        //     $akses = ['Q C'];
        // } elseif ($namaprofile->name == 'ADE NURHAYATI') {
        //     $akses = ['Q C'];
        // } elseif ($namaprofile->name == 'Vic Aguila') {
        //     $akses = ['ACCOUNTING', 'KEUANGAN', 'EDP'];
        // } elseif ($namaprofile->name == 'Bernadette Dewijanti') {
        //     $akses = ['PEMBELIAN','KEUANGAN'];
        // } elseif ($namaprofile->name == 'Agus Supriyatno') {
        //     $akses = ['EDP'];
        // } elseif ($namaprofile->name == 'Dwi Hananto') {
        //     $akses = ['TEKNIK'];
        // } elseif ($namaprofile->name == 'Wahyudi (elektrik)') {
        //     $akses = ['TEKNIK'];
        // } elseif ($namaprofile->name == 'Harzen') {
        //     $akses = ['MARKETING', 'EXPEDISI'];
        // } elseif ($namaprofile->name == 'Budhi Setya Dharma') {
        //     $akses = ['PROD. LANGSUNG', 'PROD. TAK LANGSUNG', 'GUDANG', 'PREPARASI', 'PROD.LANGSUNG TINTA'];
        // }
  

        if (request()->train_dat_awal || request()->train_dat_akhir) {
            $train_dat_awal = Carbon::parse(request()->train_dat_awal)->toDateString();
            $train_dat_akhir = Carbon::parse(request()->train_dat_akhir)->toDateString();

            $dtv1 = Carbon::createFromFormat('Y-m-d', $aw)->format('d-m-Y');
            $dtv2 = Carbon::createFromFormat('Y-m-d', $ak)->format('d-m-Y');

            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->leftjoin('target_trainings', function ($join) use ($train_dat_akhir) {
                    $join
                        ->on('pegawais.bagian', '=', 'target_trainings.bagian')
                        ->where('periode_awal', '<=', $train_dat_akhir)
                        ->where('periode_akhir', '>=', $train_dat_akhir);
                })
                ->select(DB::raw('ROUND((target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'), 'target_trainings.jumlah_jam', 'train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan', 'jumlah_jam')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->where('pegawais.no_payroll', 'LIKE', '%' . $nm . '%')
                ->where('train_hs.tipe', $jenis)
                ->wherein('pegawais.bagian', $akses)
                ->orderBy('bagian', 'ASC')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->orderBy('no_payroll', 'ASC')
                ->get();

                $jj = $data->sum('totaljam');
                $kj = $data->sum('kurangjam');
                $jjm = $data->sum('jumlah_jam');
          
            } else {
            $dtv1 = '-';
            $dtv2 = '-';

            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select('train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) , 2) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->limit(0)
                ->get();
                
                $jj = '';
                $kj =  '';
                $jjm =  '';
        }

        return view('hr.dashboard.training.trainer.laporan.rek_tra', compact('kj','jjm','jj','data', 'aw', 'ak', 'dtv1', 'dtv2', 'namaprofile', 'jenis', 'nm'));
    }

    public function rek_tra_print(Request $request)
    {
        // dd($request->toArray());
        $namaprofile = Auth::user();

        $user = Auth::user();
        $data_akses = DB::table('users')
        ->join('akses_users', 'users.id', '=', 'akses_users.iduser')
        ->where('akses_users.iduser', '=', $user->id)
        ->pluck('bagian');
        $akses = $data_akses;
        $akses = $akses ?? [];

        // $akses = null;

        // if ($namaprofile->name == 'Dessy Tiara Ningrum') {
        //     $akses = ['PROD. LANGSUNG', 'PROD. TAK LANGSUNG', 'GUDANG', 'PREPARASI', 'PROD.LANGSUNG TINTA'];
        // } elseif ($namaprofile->name == 'Haryono') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Wargino') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Bambang Eko') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Sony Trijoko') {
        //     $akses = ['PROD. LANGSUNG'];
        // } elseif ($namaprofile->name == 'Ellist Ocataviani') {
        //     $akses = ['Q C'];
        // } elseif ($namaprofile->name == 'MINTEN') {
        //     $akses = ['Q C'];
        // } elseif ($namaprofile->name == 'ADE NURHAYATI') {
        //     $akses = ['Q C'];
        // } elseif ($namaprofile->name == 'Vic Aguila') {
        //     $akses = ['ACCOUNTING', 'KEUANGAN', 'EDP'];
        // } elseif ($namaprofile->name == 'Bernadette Dewijanti') {
        //     $akses = ['PEMBELIAN','KEUANGAN'];
        // } elseif ($namaprofile->name == 'Agus Supriyatno') {
        //     $akses = ['EDP'];
        // } elseif ($namaprofile->name == 'Dwi Hananto') {
        //     $akses = ['TEKNIK'];
        // } elseif ($namaprofile->name == 'Wahyudi (elektrik)') {
        //     $akses = ['TEKNIK'];
        // } elseif ($namaprofile->name == 'Harzen') {
        //     $akses = ['MARKETING', 'EXPEDISI'];
        // } elseif ($namaprofile->name == 'Budhi Setya Dharma') {
        //     $akses = ['PROD. LANGSUNG', 'PROD. TAK LANGSUNG', 'GUDANG', 'PREPARASI', 'PROD.LANGSUNG TINTA'];
        // } 

        // $akses = $akses ?? [];


        $nik = $request->no_payroll;
        $aw = $request->train_dat_awal;
        $ak = $request->train_dat_akhir;
        $jenis = $request->jenis;
        $nm = $request->nm;

        $train_dat_awal = Carbon::parse(request()->train_dat_awal)->toDateString();
        $train_dat_akhir = Carbon::parse(request()->train_dat_akhir)->toDateString();

        if ($aw == null || $ak == null) {
            $dtv1 = 0;
            $dtv2 = 0;

       
                $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftjoin('target_trainings', function ($join) use ($train_dat_akhir) {
                    $join
                        ->on('pegawais.bagian', '=', 'target_trainings.bagian')
                        ->where('periode_awal', '<=', $train_dat_akhir)
                        ->where('periode_akhir', '>=', $train_dat_akhir);
                })
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select(DB::raw('ROUND((target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'), 'target_trainings.jumlah_jam', 'train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan', 'jumlah_jam')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->wherein('pegawais.bagian', $akses)

                ->orderBy('bagian', 'ASC')
                ->whereNull('deleted_at')
                ->get();

                $jj = $data->sum('totaljam');
                $kj = $data->sum('kurangjam');
                $jjm = $data->sum('jumlah_jam');
        } else {
            $dtv1 = Carbon::createFromFormat('Y-m-d', $aw)->format('d-m-Y');
            $dtv2 = Carbon::createFromFormat('Y-m-d', $ak)->format('d-m-Y');

            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftjoin('target_trainings', function ($join) use ($train_dat_akhir) {
                    $join
                        ->on('pegawais.bagian', '=', 'target_trainings.bagian')
                        ->where('periode_awal', '<=', $train_dat_akhir)
                        ->where('periode_akhir', '>=', $train_dat_akhir);
                })
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select(DB::raw('ROUND((target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'), 'target_trainings.jumlah_jam', 'train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan', 'jumlah_jam')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->orderBy('bagian', 'ASC')
                ->where('pegawais.no_payroll', 'LIKE', '%' . $nm . '%')
                ->where('train_hs.tipe', $jenis)
                ->wherein('pegawais.bagian', $akses)
                ->orderBy('bagian', 'ASC')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->orderBy('no_payroll', 'ASC')
                ->get();

                $jj = $data->sum('totaljam');
                $kj = $data->sum('kurangjam');
                $jjm = $data->sum('jumlah_jam');
        }

        $pdf = Pdf::loadview('hr.dashboard.training.trainer.laporan.rek_tra_print', compact('kj','jjm','jj','data', 'aw', 'ak', 'dtv1', 'dtv2','jenis'));
        return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
    }

    public function tra_kar(Request $request, $id, $dtv1, $dtv2, $jenis)
    {
        // $nik = pegawai::findorfail($id);

        // dd($dtv1);
        $train_dat_awal = Carbon::parse(request()->dtv1)->toDateString();
        $train_dat_akhir = Carbon::parse(request()->dtv2)->toDateString();

        $nik = $id;
        // $nik = $request->no_payroll;

        $namaprofile = Auth::user();
        $data = DB::table('train_ds')
            ->where('no_payroll', $nik)
            ->exists();
        if ($data) {
            $data = train_d::join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->whereNull('deleted_at')
                ->where('train_ds.no_payroll', $nik)
                ->first();

            $data_t = train_h::join('train_ds', 'train_hs.train_cod', '=', 'train_ds.train_cod')
                ->leftJoin('users', 'users.name', '=', 'train_hs.pemateri')
                ->whereNull('deleted_at')
                ->select('train_ds.nilai', 'train_ds.nilai_pre','train_ds.keterangan', 'users.level', 'train_hs.pemateri', 'train_hs.train_tema', 'train_ds.nama_asli',  DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), DB::raw('ROUND((((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
                ->where('train_ds.no_payroll', $nik)
                ->where('train_hs.approve', 'YES')
                ->where('train_hs.tipe', $jenis)
                ->where('train_ds.approve', 'Y')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->get();

            $data_total = train_d::join('train_hs', 'train_hs.train_cod', '=', 'train_ds.train_cod')
                ->whereNull('deleted_at')
                ->select(DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
                ->where('train_ds.no_payroll', $nik)
                ->where('train_hs.approve', 'YES')
                ->where('train_hs.tipe', $jenis)
                ->where('train_ds.approve', 'Y')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->first();

            return view('hr.dashboard.training.trainer.laporan.tra_kar', compact('data_total', 'data', 'data_t', 'nik', 'namaprofile', 'dtv1', 'dtv2'));
            // $pdf = Pdf::loadview('hr.dashboard.training.trainer.laporan.tra_kar', compact('data_total', 'data', 'data_t', 'aw', 'ak', 'nik','dtv1','dtv2'));
            // return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
        } else {
            //jika tidak maka
            return back()->with('success', 'Maaf, NIK Tidak Ditemukan');
        }
    }
    public function tra_kar_print(Request $request, $id, $dtv1, $dtv2)
    {
        // $nik = pegawai::findorfail($id);

        // dd($dtv1);
        $train_dat_awal = Carbon::parse(request()->dtv1)->toDateString();
        $train_dat_akhir = Carbon::parse(request()->dtv2)->toDateString();

        $nik = $id;
        // $nik = $request->no_payroll;

        $namaprofile = Auth::user();
        $data = DB::table('train_ds')
            ->where('no_payroll', $nik)
            ->exists();
        if ($data) {
            $data = train_d::join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->whereNull('deleted_at')
                ->where('train_ds.no_payroll', $nik)
                ->first();

            $data_t = train_h::join('train_ds', 'train_hs.train_cod', '=', 'train_ds.train_cod')
                ->leftJoin('users', 'users.name', '=', 'train_hs.pemateri')
                ->whereNull('deleted_at')
                ->select('train_ds.nilai', 'train_ds.nilai_pre','train_ds.keterangan', 'users.level', 'train_hs.pemateri', 'train_hs.train_tema', 'train_ds.nama_asli',  DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), DB::raw('ROUND((((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
                ->where('train_ds.no_payroll', $nik)
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->get();

            $data_total = train_d::join('train_hs', 'train_hs.train_cod', '=', 'train_ds.train_cod')
                ->whereNull('deleted_at')
                ->select(DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
                ->where('train_ds.no_payroll', $nik)
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->first();

            // return view('hr.dashboard.training.trainer.laporan.tra_kar',compact('data_total', 'data', 'data_t', 'nik','namaprofile','dtv1','dtv2'));
            $pdf = Pdf::loadview('hr.dashboard.training.trainer.laporan.tra_kar_print', compact('data_total', 'data', 'data_t', 'nik', 'namaprofile', 'dtv1', 'dtv2'));
            return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
        } else {
            //jika tidak maka
            return back()->with('success', 'Maaf, NIK Tidak Ditemukan');
        }
    }

    //profile

    public function profile_auth(){

        $namaprofile = Auth::user();

        return view('hr.dashboard.training.trainer.profile_auth', compact( 'namaprofile'));
    }

    public function profile_auth_edit(){

        $profile = Auth::user();

        return view('hr.dashboard.training.trainer.profile_auth_edit', compact( 'profile'));
    }

    
    public function profile_auth_update(Request $request, $id)
    {
        // dd($request->toArray());
        $user = User::find($id);
        $user->id = $request->id;
        $user->name = $request->name;
        $user->level = $request->level;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();

        return back()->with('success', 'Profile Update successfully');
    }
}
