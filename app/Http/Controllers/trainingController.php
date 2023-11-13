<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use App\Models\Coba;
use App\Models\kompe_d;
use App\Models\kompe_h;
use App\Models\Materi;
use App\Models\pegawai;
use App\Models\TargetTraining;
use App\Models\train_d;
use App\Models\train_h;
use App\Models\Trainer;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Constraints\SoftDeletedInDatabase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Exists;
use Nette\Utils\Strings;
use Termwind\Components\Raw;
use Illuminate\Support\Str;


class trainingController extends Controller
{
    public function list()
    {
        $training = train_h::orderby('id', 'desc')
        ->select('id','train_cod','pemateri','train_tema','kompetensi',DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"),'tipe','approve')
        ->get();

        // $kode_unik = Str::random(10);

        // dd($kode_unik);

        return view('hr.dashboard.training.tr.list', compact('training'));
    }

    public function view($id)
    {
        $view = train_h::findorfail($id);
        $view_d = train_d::where('train_cod', $view->train_cod)->get();
        $data = Materi::where('kode_materi', $view->kode_materi)->first();
        $url = url('uploads/materi/'.$data->file_materi);


        // dd($view_d->toArray());
        return view('hr.dashboard.training.tr.view', compact('view', 'view_d','url'));
    }
    public function print($id)
    {
        $view = train_h::findorfail($id);
        $viewew = train_h::
        select('train_cod','pltran_nam',  DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), 'jam','tempat','pemateri', 'train_tema')
        ->where('id', $id)
        ->first();
        // $view_d = train_d::where('train_cod', $view->train_cod)->get();
        $tgl = carbon::now()->format('d-m-Y');
        // $bag = pegawai::where('no_payroll', $view_d->no_payroll)->get();
        // dd($view_d->toArray());
        // dd($view->toArray());

        $view_d = train_d::join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
            ->where('train_cod', $view->train_cod)
            ->get();

        $pdf = Pdf::loadview('hr.dashboard.training.tr.print', compact('viewew','view', 'view_d', 'tgl'));
        return $pdf->setPaper('a4', 'potrait')->stream('DaftarHadir.pdf');
    }

    public function create(Request $request)
    {
        $kompe_d = kompe_d::orderBy('kompetensi', 'asc')->get();
        $train_h = train_h::select('id', 'train_cod')
            ->orderby('id', 'desc')
            ->first();
        $pegawai = pegawai::select('id', 'nama', 'nama_asli', 'no_payroll')->get();
        $data = pegawai::select('nama', 'nama_asli', 'no_payroll')->get();
        $pemateri = user::select('name')->get();
        $materi = Materi::orderBy('materi' , 'asc')->get();
        $bagian = bagian::orderBy('bagian', 'asc')->get();

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
        return view('hr.dashboard.training.tr.create', compact('kompe_d', 'no', 'pegawai', 'data', 'pemateri','materi','bagian'));
    }

    public function store(Request $request)
    {

        // dd($request->toArray());
        $request->validate([
            'train_cod' => 'required|unique:train_hs,train_cod',
            'file' => 'file|mimes:doc,docx,ppt,pptx,xls,xlsx,pdf|max:10000',
            'file_absen' => 'file|mimes:doc,docx,ppt,pptx,xls,xlsx,pdf|max:10000',
        ]);

        if ($files = $request->file('file')) {
            $destinationPath = 'files/';
            $files_tr = $files->getClientOriginalName();
            $files->move($destinationPath, $files_tr);
            $input['file'] = "$files_tr";
        } else {
            $files_tr = 'No File';
        }
        if ($files = $request->file('file_absen')) {
            $destinationPath = 'files_absen/';
            $files_absen = $files->getClientOriginalName();
            $files->move($destinationPath, $files_absen);
            $input['file_absen'] = "$files_absen";
        } else {
            $files_absen = 'No File Absen';
        }

        $km = Materi::where('materi', $request->train_tema)->first();
        // dd($km->toArray());

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
            'file_absen' => $files_absen,
            'kode_materi' => $km->kode_materi,
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
            ->route('hr.training.list')
            ->with('success', 'New subject has been added.');
    }

    public function edit(Request $request, $id)
    {
        $data = train_h::findorfail($id);
        $data_d = train_d::where('train_cod', $data->train_cod)
            ->orderby('id', 'asc')
            ->get();
        $pemateri = user::select('name')->get();
        $materi = Materi::orderBy('materi' , 'asc')->get();
        $bagian = bagian::orderBy('bagian', 'asc')->get();


        $jmlh_d = count($data_d);

        // dd($view_d->toArray());
        return view('hr.dashboard.training.tr.edit', compact('data', 'data_d', 'jmlh_d', 'pemateri','materi','bagian'));
    }

    public function update($id, Request $request, train_d $train_d, train_h $train_h)
    {
        // dd($request->toArray());
        $request->validate([
            // 'train_cod' => 'required|unique:train_hs,train_cod',
            'file' => 'file|mimes:doc,docx,ppt,pptx,xls,xlsx,pdf|max:10000',
        ]);

        $km = Materi::where('materi', $request->train_tema)->first();


        if ($files = $request->file('file')) {
            $destinationPath = 'files/';
            $files_tr = $files->getClientOriginalName();
            $files->move($destinationPath, $files_tr);
            $input['file'] = "$files_tr";
        } else {
            $files_tr = 'No File';
        }
        if ($files = $request->file('file_absen')) {
            $destinationPath = 'files_absen/';
            $files_absen = $files->getClientOriginalName();
            $files->move($destinationPath, $files_absen);
            $input['file_absen'] = "$files_absen";
        } else {
            $files_absen = 'No File Absen';
        }

        // dd($request->toArray());
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
        $data_h->file = $files_tr;
        $data_h->file_absen = $files_absen;
        $data_h->kode_materi = $km->kode_materi;
        $data_h->save();

        // dd($request->toArray());
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
            ->route('hr.training.list')
            ->with('success', 'New subject has been added.');
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
        return view('hr.dashboard.training.tr.train_trash', compact('data_d', 'data_dd'));
    }

    public function delete($id, train_h $train_d)
    {
        $trainh = train_h::findOrFail($id);
        $traind = train_d::where('train_cod', $trainh->train_cod);
        $trainh->delete();
        $traind->delete();
        return redirect()
            ->route('hr.training.list')
            ->with('success', 'Data berhasil dihapus!');
    }

    public function delete_d($id, train_d $train_d)
    {
        $train = train_d::findOrFail($id);
        $train->delete();
        return back();
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

    public function autocomplete(Request $request)
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
    public function autocompleted_pegawai(Request $request)
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

    //laporan

    public function rek_tra(Request $request)
    {      

        Carbon::setLocale('id');
        $nik = $request->no_payroll;
        $aw = $request->train_dat_awal;
        $ak = $request->train_dat_akhir;


        $train_dat_awal = Carbon::parse(request()->train_dat_awal)->toDateString();
        $train_dat_akhir = Carbon::parse(request()->train_dat_akhir)->toDateString();

        if (request()->train_dat_awal || request()->train_dat_akhir) {


            
            $train_dat_awal = Carbon::parse(request()->train_dat_awal)->toDateString();
            $train_dat_akhir = Carbon::parse(request()->train_dat_akhir)->toDateString();
            $jenis = $request->jns_tr;
            $nm = $request->no_payroll;

            $dtv1 = Carbon::createFromFormat('Y-m-d', $aw)->format('d-m-Y');
            $dtv2 = Carbon::createFromFormat('Y-m-d', $ak)->format('d-m-Y');

            // $hasil = TargetTraining::select('jumlah_jam')
            // ->where('periode_awal', '<', $train_dat_akhir )
            // ->where('periode_akhir', '>', $train_dat_akhir )
            // ->get();

            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->leftjoin('target_trainings', function($join) use ($train_dat_akhir) {
                    $join->on('pegawais.bagian', '=', 'target_trainings.bagian')
                    ->where('periode_awal', '<=', $train_dat_akhir )
                    ->where('periode_akhir', '>=', $train_dat_akhir );
                })                
                ->select(DB::raw('ROUND((target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'),'target_trainings.jumlah_jam','train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan','jumlah_jam')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar' , null)
                ->where('pegawais.no_payroll', 'LIKE', '%'.$nm.'%')
                ->where('train_hs.tipe' , $jenis)
                ->orderBy('bagian', 'ASC')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->orderBy('no_payroll', 'ASC')
                ->get();

        } 
        else {

            $dtv1 = '-';
            $dtv2 = '-';
            $jenis = '-';
            $nm = '';

            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select('train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'),DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) , 2) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
                ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar' , null)
                ->limit(0)
                ->get();       
        }
        return view('hr.dashboard.training.tr.laporan.index', compact('data', 'aw', 'ak','dtv1', 'dtv2','jenis', 'nm'));
    }

    public function rek_tra_print(Request $request)
    {

        $nik = $request->no_payroll;
        $aw = $request->train_dat_awal;
        $ak = $request->train_dat_akhir;
        $jenis = $request->jenis;
        $nama = $request->nm;
        
        $train_dat_awal = Carbon::parse(request()->train_dat_awal)->toDateString();
        $train_dat_akhir = Carbon::parse(request()->train_dat_akhir)->toDateString();

        if ($aw == null || $ak == null) {

            $dtv1= 0;
            $dtv2= 0;
            
            $data = DB::table('train_ds')
            ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
            ->leftjoin('target_trainings', function($join) use ($train_dat_akhir) {
                $join->on('pegawais.bagian', '=', 'target_trainings.bagian')
                ->where('periode_awal', '<=', $train_dat_akhir )
                ->where('periode_akhir', '>=', $train_dat_akhir );
            })  
            ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
            ->select(DB::raw('ROUND((target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'),'target_trainings.jumlah_jam','train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
            ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan','jumlah_jam')
            ->where('train_hs.approve', 'YES')
            ->where('train_ds.approve', 'Y')
            ->where('pegawais.tgl_keluar' , null)
            ->orderBy('bagian', 'ASC')
            ->whereNull('deleted_at')
            ->get();
        }else{

            $dtv1 = Carbon::createFromFormat('Y-m-d', $aw)->format('d-m-Y');
            $dtv2 = Carbon::createFromFormat('Y-m-d', $ak)->format('d-m-Y'); 

            $data = DB::table('train_ds')
            ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
            ->leftjoin('target_trainings', function($join) use ($train_dat_akhir) {
                $join->on('pegawais.bagian', '=', 'target_trainings.bagian')
                ->where('periode_awal', '<=', $train_dat_akhir )
                ->where('periode_akhir', '>=', $train_dat_akhir );
            })  
            ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
            ->select(DB::raw('ROUND((target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'),'target_trainings.jumlah_jam','train_ds.no_payroll', DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'), DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'), 'train_ds.no_payroll', 'train_ds.nama_asli', 'pegawais.bagian', 'pegawais.jabatan')
            ->groupBy('no_payroll', 'nama_asli', 'bagian', 'jabatan','jumlah_jam')
            ->where('train_hs.approve', 'YES')
            ->where('train_ds.approve', 'Y')
            ->whereNull('deleted_at')
            ->where('pegawais.tgl_keluar' , null)
            ->orderBy('bagian', 'ASC')
            ->where('pegawais.no_payroll', 'LIKE', '%'.$nama.'%')
            ->where('train_hs.tipe' , $jenis) 
            ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
            ->orderBy('no_payroll', 'ASC')
            ->get();
        }


        $pdf = Pdf::loadview('hr.dashboard.training.tr.laporan.rek_tra_print', compact('data', 'aw', 'ak','dtv1','dtv2','jenis'));
        return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
    }

    public function tra_kar(Request $request)
    {
        return view('hr.dashboard.training.tr.laporan.tra_kar');
    }
    public function tra_kar_print(Request $request)
    {
        $nik = $request->no_payroll;
        $aw = $request->train_dat_awal;
        $ak = $request->train_dat_akhir;

        $data = DB::table('train_ds')
            ->where('no_payroll', $nik)
            ->exists();
        if ($data) {
            // Lakukan aksi jika data ditemukan
            $train_dat_awal = Carbon::parse(request()->train_dat_awal)->toDateString();
            $train_dat_akhir = Carbon::parse(request()->train_dat_akhir)->toDateString();

            $dtv1 = Carbon::createFromFormat('Y-m-d', $aw)->format('d-m-Y');
            $dtv2 = Carbon::createFromFormat('Y-m-d', $ak)->format('d-m-Y'); 

            $data = train_d::join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
            ->whereNull('deleted_at')
            ->where('train_ds.no_payroll', $nik)
            ->first();

        $data_t = train_h::join('train_ds', 'train_hs.train_cod', '=', 'train_ds.train_cod')
            ->leftJoin('users', 'users.name', '=', 'train_hs.pemateri')
            ->whereNull('deleted_at')
            ->select('train_ds.nilai', 'train_ds.nilai_pre', 'train_ds.keterangan','users.level', 'train_hs.pemateri', 'train_hs.train_tema', 'train_ds.nama_asli',  DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), DB::raw('ROUND((((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
            ->where('train_ds.no_payroll', $nik)
            ->where('train_hs.approve', 'YES')     
            ->where('train_ds.approve', 'Y')
            ->where('train_hs.tipe', 'like', '%' . $request->jns_tr . '%')
            ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
            ->get();

        $data_total = train_d::join('train_hs', 'train_hs.train_cod', '=', 'train_ds.train_cod')
            ->whereNull('deleted_at')
            ->select(DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'),DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
            ->where('train_ds.no_payroll', $nik)
            ->where('train_hs.approve', 'YES')     
            ->where('train_ds.approve', 'Y')
            ->where('train_hs.tipe', 'like', '%' . $request->jns_tr . '%')
            ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
            ->first();
            $pdf = Pdf::loadview('hr.dashboard.training.tr.laporan.tra_kar_print', compact('data_total', 'data', 'data_t', 'aw', 'ak', 'nik','dtv1','dtv2'));
            return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
        } else {
            //jika tidak maka 
            return back()->with('success', 'Maaf, NIK Tidak Ditemukan');
        }
    }

       
    public function tra_kar_detail(Request $request, $id ,$dtv1,$dtv2 ,$jenis)
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
            ->where('train_hs.tipe' , $jenis) 
            ->where('train_ds.approve', 'Y')
            ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
            ->get();

        $data_total = train_d::join('train_hs', 'train_hs.train_cod', '=', 'train_ds.train_cod')
            ->whereNull('deleted_at')
            ->select(DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'),DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
            ->where('train_ds.no_payroll', $nik)
            ->where('train_hs.approve', 'YES')     
            ->where('train_hs.tipe' , $jenis)
            ->where('train_ds.approve', 'Y')
            ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
            ->first();
                return view('hr.dashboard.training.tr.laporan.tra_kar_detail',compact('data_total', 'data', 'data_t', 'nik','namaprofile','dtv1','dtv2','jenis'));
            // $pdf = Pdf::loadview('hr.dashboard.training.trainer.laporan.tra_kar', compact('data_total', 'data', 'data_t', 'aw', 'ak', 'nik','dtv1','dtv2'));
            // return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
        } else {
            //jika tidak maka 
            return back()->with('success', 'Maaf, NIK Tidak Ditemukan');
        }
    }
    public function tra_kar_print_detail(Request $request, $id ,$dtv1,$dtv2, $jenis )
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
            ->select('train_ds.nilai', 'train_ds.nilai_pre' ,'train_ds.keterangan', 'users.level', 'train_hs.pemateri', 'train_hs.train_tema', 'train_ds.nama_asli',  DB::raw("DATE_FORMAT(train_hs.train_dat, '%d-%m-%Y') AS train_date"), DB::raw('ROUND((((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
            ->where('train_ds.no_payroll', $nik)
            ->where('train_hs.approve', 'YES')     
            ->where('train_ds.approve', 'Y')
            ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
            ->get();

        $data_total = train_d::join('train_hs', 'train_hs.train_cod', '=', 'train_ds.train_cod')
            ->whereNull('deleted_at')
            ->select(DB::raw('ROUND(AVG(train_ds.nilai), 2) as totalnilai'),DB::raw('ROUND(AVG(train_ds.nilai_pre), 2) as totalnilaipre'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ), 2) AS totaljam'))
            ->where('train_ds.no_payroll', $nik)
            ->where('train_hs.approve', 'YES')     
            ->where('train_ds.approve', 'Y')
            ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
            ->first();

                // return view('hr.dashboard.training.trainer.laporan.tra_kar',compact('data_total', 'data', 'data_t', 'nik','namaprofile','dtv1','dtv2'));
            $pdf = Pdf::loadview('hr.dashboard.training.tr.laporan.tra_kar_print_detail', compact('data_total', 'data', 'data_t', 'nik','namaprofile','dtv1','dtv2'));
            return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
        } else {
            //jika tidak maka 
            return back()->with('success', 'Maaf, NIK Tidak Ditemukan');
        }
    }
}
