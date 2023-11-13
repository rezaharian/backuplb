<?php

namespace App\Http\Controllers;

use App\Models\kompe_d;
use App\Models\pegawai;
use App\Models\prob_msd;
use App\Models\prob_msn;
use App\Models\train_d;
use App\Models\train_h;
use App\Models\unit_msn;
use App\Models\User;
use App\Models\vmacunit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QcController extends Controller
{
    public function index(Request $request)
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
            ->where('line', 'like', '%' .  $line .'%' )
            ->where('unitmesin', 'like', '%' . $umsn . '%')
            ->where('masalah', 'like', '%' . $masalah . '%')
            ->where('penyebab', 'like', '%' . $penyebab . '%')
            ->orderby('prob_msns.id' , 'desc')
            ->get(['prob_msns.*', 'prob_msds.*']);
            }else {
                # code...
                $list = prob_msn::join('prob_msds', 'prob_msns.prob_cod', '=', 'prob_msds.prob_cod')
                ->where('line',  $line  )
                ->where('unitmesin', 'like', '%' . $umsn . '%')
                ->where('masalah', 'like', '%' . $masalah . '%')
                ->where('penyebab', 'like', '%' . $penyebab . '%')
                ->orderby('prob_msns.id' , 'desc')
                ->get(['prob_msns.*', 'prob_msds.*']);
            }


        // $list = prob_msd::get();
   
        $namaprofile = Auth::user();
        return view('qc.dashboard.problemmsn.index', compact('namaprofile', 'list','datau','datal','list'));
    }

    public function list()
    {
        $prob_h = prob_msn::orderby('id', 'desc')->get();

        $namaprofile = Auth::user();
        return view('qc.dashboard.problemmsn.list', compact('namaprofile', 'prob_h'));
    }

    public function view($id)
    {
        $view = prob_msn::findorfail($id);
        // dd($view->toArray());
        $view_d = prob_msd::where('prob_cod', $view->prob_cod)->get();
        $namaprofile = Auth::user();
       
        // dd($view_d->toArray());
        return view('qc.dashboard.problemmsn.view', compact('view', 'view_d','namaprofile'));
    }

    public function view_d($id)
    {
        $v = prob_msd::findorfail($id);
        $view =  prob_msn::where('prob_cod', $v->prob_cod)->first();
        $view_d = prob_msd::where('id',$id)->first();


        $namaprofile = Auth::user();
       
        return view('qc.dashboard.problemmsn.view', compact('view', 'view_d','namaprofile'));
    }

    public function create()
    {
        $namaprofile = Auth::user();
        $datau = unit_msn::orderby('id', 'DESC')->get();
        $datal = vmacunit::orderby('id', 'DESC')->get();
        $datano = prob_msn::select('id', 'prob_cod')->orderby('id', 'desc')
        ->first();

        $thn = Carbon::now()->format('Y');
        $trakhir = $datano->prob_cod;
        $thna_p = substr($trakhir, 0,2);

        $thns = Carbon::now()->format('Y');
        $bln = Carbon::now()->format('m');
        $thn_p = substr($thns, 2, 2);
        $trakhirconf = substr($trakhir, 5, 4);
        $trakhirconf++;
        if ($thna_p != $thn_p) {
            $f = 001;
        } else {
            $f = $trakhirconf;
        }


        $bln= Carbon::now()->format('m');
        $kode = 0001;
        $kode++;
        
        $nod = $bln . sprintf('%02s', $kode)  ;

        $no = $thn_p .  sprintf('%04s', $f);

       

        return view('qc.dashboard.problemmsn.create', compact('namaprofile', 'datau', 'datal', 'no','nod'));
    }

    public function store(Request $request)
    {

        // dd($request->toArray());
        $request->validate([
            'img_pro01' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro02' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro03' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro04' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
        ]);
       

     

        if ($image = $request->file('img_pro01')) {
            $destinationPath = 'image/';
            $profileImage1 = date('YmdHis') . "1." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage1);
            $input['img_pro01'] = "$profileImage1";
        }else{
            $profileImage1 = 'No Image';   
        }
        if ($image = $request->file('img_pro02')) {
            $destinationPath = 'image/';
            $profileImage2 = date('YmdHis') . "2." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage2);
            $input['img_pro02'] = "$profileImage2";
        }else{
            $profileImage2 = 'No Image';   
        }
        if ($image = $request->file('img_pro03')) {
            $destinationPath = 'image/';
            $profileImage3 = date('YmdHis') . "3." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage3);
            $input['img_pro03'] = "$profileImage3";
        }else{
            $profileImage3 = 'No Image';   
        }
        if ($image = $request->file('img_pro04')) {
            $destinationPath = 'image/';
            $profileImage4 = date('YmdHis') . "4." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage4);
            $input['img_pro04'] = "$profileImage4";
        }else{
            $profileImage4 = 'No Image';   
        }
        $input = ([
            'prob_cod' => $request->prob_cod,
            'tgl_input' => $request->tgl_input,
            'masalah' => $request->masalah,
            'line' => $request->line,
            'unitmesin' => $request->unitmesin,
            'img_pro01' => $profileImage1 ,
            'img_pro02' => $profileImage2,
            'img_pro03' => $profileImage3,
            'img_pro04' => $profileImage4,
        ]);
        prob_msn::create($input);

        foreach ($request->id_no as $key => $value) {
            prob_msd::create([
                'id_no' => $value,
                'penyebab' => $request->penyebab[$key],
                'perbaikan' => $request->perbaikan[$key],
                'tgl_rpr' => $request->tgl_rpr[$key],
                'pencegahan' => $request->pencegahan[$key],
                'tgl_pre' => $request->tgl_pre[$key],
                'prob_cod' => $request->prob_cod,
                'tgl_input' => $request->tgl_input,
            ]);
        }

        return redirect()
            ->route('problemmsn.list')
            ->with('success', 'New subject has been added.');
    }

    public function edit($id, Request $request){
        $namaprofile = Auth::user();
        $data = prob_msn::findorfail($id);
        $data_d = prob_msd::where('prob_cod', $data->prob_cod)
            ->orderby('id', 'asc')
            ->get(); 
        $jmlh_d = count($data_d);
        $datau = unit_msn::orderby('id', 'DESC')->get();
        $datal = vmacunit::orderby('id', 'DESC')->get();

            
        $bln= Carbon::now()->format('m');
        $kode = 0001;
        $kode++;
        $nod = $bln . sprintf('%02s', $kode)  ;
        return view('qc.dashboard.problemmsn.edit', compact('data', 'data_d', 'namaprofile', 'nod','jmlh_d','datal','datau'));
    }

    public function update($id , Request $request){
        // dd($request->toArray());
        $request->validate([
            'img_pro01' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro02' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro03' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro04' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
        ]);


        $data_h = prob_msn::where('id', $id)->first();
        $data_d = prob_msd::all();

        if ($image = $request->file('img_pro04')) {
            $destinationPath = 'image/';
            $profileImage4 = date('YmdHis') . "4." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage4);
            $data_h['img_pro04'] = "$profileImage4";
        }else{
            unset($data_h['img_pro04']);
        }
        if ($image = $request->file('img_pro03')) {
            $destinationPath = 'image/';
            $profileImage4 = date('YmdHis') . "4." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage4);
            $data_h['img_pro03'] = "$profileImage4";
        }else{
            unset($data_h['img_pro03']);
        }
        if ($image = $request->file('img_pro02')) {
            $destinationPath = 'image/';
            $profileImage4 = date('YmdHis') . "4." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage4);
            $data_h['img_pro02'] = "$profileImage4";
        }else{
            unset($data_h['img_pro02']);
        }
        if ($image = $request->file('img_pro01')) {
            $destinationPath = 'image/';
            $profileImage4 = date('YmdHis') . "4." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage4);
            $data_h['img_pro01'] = "$profileImage4";
        }else{
            unset($data_h['img_pro01']);
        }
          
        
        $data_h->prob_cod = $request->prob_cod;
        $data_h->tgl_input = $request->tgl_input;
        $data_h->masalah = $request->masalah;
        $data_h->line = $request->line;
        $data_h->unitmesin = $request->unitmesin;     
        $data_h->save();

        foreach ($request->id_no as $key => $value) {
            if (isset($request->item_id[$key])) {
                prob_msd::where('id', $request->item_id[$key])->update([
                    'id_no' => $value,
                    'prob_cod' =>  $request->prob_cod,
                    'tgl_input' => $request->tgl_input,
                    'penyebab' => $request->penyebab[$key],
                    'perbaikan' => $request->perbaikan[$key],
                    'tgl_rpr' => $request->tgl_rpr[$key],
                    'pencegahan' => $request->pencegahan[$key],
                    'tgl_pre' => $request->tgl_pre[$key],
                ]);
            } else {
                prob_msd::create([
                    'id_no' => $value,
                    'prob_cod' =>  $request->prob_cod,
                    'tgl_input' => $request->tgl_input,
                    'penyebab' => $request->penyebab[$key],
                    'perbaikan' => $request->perbaikan[$key],
                    'tgl_rpr' => $request->tgl_rpr[$key],
                    'pencegahan' => $request->pencegahan[$key],
                    'tgl_pre' => $request->tgl_pre[$key],
                ]);
            }
        }
        return redirect()
            ->route('problemmsn.list')
            ->with('success', 'New subject has been added.');

    }
    public function delete($id)
    {
        $prob_h = prob_msn::findOrFail($id);
        $prob_d = prob_msd::where('prob_cod', $prob_h->prob_cod);
        $prob_h->delete();
        $prob_d->delete();
        return redirect()
            ->route('problemmsn.list')
            ->with('success', 'Data berhasil dihapus!');
    }
    public function fotoprob($img_pro01,)
    {
        $data= prob_msd::findOrFail($img_pro01);
dd($data->toArray());    
        return back();
    }
    public function delete_d($id,)
    {
        $data_d = prob_msd::findOrFail($id);
        $data_d->delete();
        return back();
    }
    public function print($id){
        $v = prob_msd::findorfail($id);
        $view =  prob_msn::where('prob_cod', $v->prob_cod)->first();
        $view_d = prob_msd::where('id',$id)->first();

        $pdf = Pdf::loadview('qc.dashboard.problemmsn.print', compact('view', 'view_d'));
        return $pdf->setPaper('a4', 'potrait')->stream('Kerusakan.pdf');

    }
    
    public function print_d($id){
        $view = prob_msn::findorfail($id);
        $view_d = prob_msd::where('prob_cod', $view->prob_cod)->get();

        $pdf = Pdf::loadview('qc.dashboard.problemmsn.print_d', compact('view', 'view_d'));
        return $pdf->setPaper('a4', 'potrait')->stream('Kerusakan_d.pdf');

    }

    public function qtrlist()
    {
        $training = train_h::orderby('id', 'desc')->get();
        $namaprofile = Auth::user();
        return view('qc.dashboard.training.qtrlist', compact('training', 'namaprofile'));
    }



    public function qtrcreate(Request $request)
    {

        $namaprofile = Auth::user();

        $kompe_d = kompe_d::orderBy('kompetensi', 'asc')->get();
        $train_h = train_h::select('id', 'train_cod')
            ->orderby('id', 'desc')
            ->first();
        $pegawai = pegawai::select('id','nama', 'nama_asli', 'no_payroll')->get();
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
        return view('qc.dashboard.training.qrtcreate', compact('pemateri','kompe_d', 'no', 'pegawai', 'data','namaprofile'));
    }

    public function qtrstore(Request $request)
    {
        $request->validate([
            'train_cod' => 'required|unique:train_hs,train_cod',
        ]);

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
        ]);

        foreach ($request->no_payroll as $key => $value) {
            train_d::create([
                'no_payroll' => $value,
                'nama_asli' => $request->nama_asli[$key],
                'nilai' => $request->nilai[$key],
                'keterangan' => $request->keterangan[$key],
                'approve' => $request->approve[$key],
                'train_cod' => $request->train_cod,
                'train_dat' => $request->train_dat,
            ]);
        }

        return redirect()
            ->route('training.qtrlist')
            ->with('success', 'New subject has been added.');
    }

    public function qtrview($id)
    {
        $view = train_h::findorfail($id);
        $view_d = train_d::where('train_cod', $view->train_cod)->get();
        $namaprofile = Auth::user();
        return view('qc.dashboard.training.qtrview', compact('view', 'view_d', 'namaprofile'));
    }

    public function qtredit($id)
    {
        $data = train_h::findorfail($id);
        $data_d = train_d::where('train_cod', $data->train_cod)
            ->orderby('id', 'asc')
            ->get();
        $jmlh_d = count($data_d);
        $namaprofile = Auth::user();
        return view('qc.dashboard.training.qtredit', compact('data', 'data_d', 'jmlh_d', 'namaprofile'));
    }

    public function qtrupdate($id, Request $request, train_d $train_d, train_h $train_h)
    {
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
                    'nilai' => $request->nilai[$key],
                    'keterangan' => $request->keterangan[$key],
                    'approve' => $request->approved[$key],
                    'train_cod' => $request->train_cod,
                    'train_dat' => $request->train_dat,
                ]);
            }
        }
        return redirect()
        ->route('training.qtrlist')
        ->with('success', 'New subject has been added.');
    }

    public function qtrdelete($id, train_h $train_d)
    {
        $trainh = train_h::findOrFail($id);
        $traind = train_d::where('train_cod', $trainh->train_cod);
        $trainh->delete();
        $traind->delete();
        return redirect()
            ->route('training.qtrlist')
            ->with('success', 'Data berhasil dihapus!');
    }

    public function qtrdelete_d($id, train_d $train_d)
    {
        $train = train_d::findOrFail($id);
        $train->delete();
        return back();
    }

    public function qtrautocomplete(Request $request)
    {
        $pegawai = [];
        if($request->has('q')){
            $search = $request->q;
            $pegawai =pegawai::select("id","no_payroll", "nama_asli")
            		->where('nama_asli', 'LIKE', "%$search%")
            		->get();
        }
        return response()->json($pegawai);
    }

    public function qtrtrain_trash($id, train_d $data_d)
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
        return view('qc.dashboard.training.qtrtrain_trash', compact('data_d', 'data_dd'));
    }

    public function qtrrestore_trash($id, train_d $data_d)
    {
        $data_d = train_d::onlyTrashed()->where('id', $id);
        // dd($data_d->toArray());
        $data_d->restore();
        return back()->with('success', 'Data berhasil dipulihkan!');
    }

    public function qtrrestore_all($id, train_d $data_d)
    {
        $data_d_all = train_d::onlyTrashed()->where('train_cod', $id);
        $data_d_all->restore();
        return back()->with('success', 'Data berhasil dipulihkan!');
    }

    public function qtrprint($id)
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

        $pdf = Pdf::loadview('hr.dashboard.training.tr.print', compact('view', 'view_d', 'tgl'));
        return $pdf->setPaper('a4', 'potrait')->stream('DaftarHadir.pdf');
    }
}