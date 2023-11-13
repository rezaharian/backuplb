<?php

namespace App\Http\Controllers;

use App\Models\prob_msd;
use App\Models\prob_msn;
use App\Models\unit_msn;
use App\Models\User;
use App\Models\vmacunit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemMesinController extends Controller
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
        return view('superadmin.dashboard.problemmsn.listmasalah', compact('namaprofile', 'list','datau','datal','list'));
    }

    public function list()
    {
        $prob_h = prob_msn::orderby('id', 'desc')->get();

        $namaprofile = Auth::user();
        return view('superadmin.dashboard.problemmsn.list', compact('namaprofile', 'prob_h'));
    }

    public function view($id)
    {
        $view = prob_msn::findorfail($id);
        $view_d = prob_msd::where('prob_cod', $view->prob_cod)->get();
        $namaprofile = Auth::user();

       
        // dd($view_d->toArray());
        return view('superadmin.dashboard.problemmsn.view', compact('view', 'view_d','namaprofile'));
    }

    public function view_d($id)
    {
        $v = prob_msd::findorfail($id);
        $view =  prob_msn::where('prob_cod', $v->prob_cod)->first();
        $view_d = prob_msd::where('id',$id)->first();


        $namaprofile = Auth::user();
       
        return view('superadmin.dashboard.problemmsn.view', compact('view', 'view_d','namaprofile'));
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

       

        return view('superadmin.dashboard.problemmsn.create', compact('namaprofile', 'datau', 'datal', 'no','nod'));
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
            ->route('adm.problemmsn.list')
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
        return view('superadmin.dashboard.problemmsn.edit', compact('data', 'data_d', 'namaprofile', 'nod','jmlh_d','datal','datau'));
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
            ->route('adm.problemmsn.list')
            ->with('success', 'New subject has been added.');

    }
    public function delete($id)
    {
        $prob_h = prob_msn::findOrFail($id);
        $prob_d = prob_msd::where('prob_cod', $prob_h->prob_cod);
        $prob_h->delete();
        $prob_d->delete();
        return redirect()
            ->route('adm.problemmsn.list')
            ->with('success', 'Data berhasil dihapus!');
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

        $pdf = Pdf::loadview('superadmin.dashboard.problemmsn.print', compact('view', 'view_d'));
        return $pdf->setPaper('a4', 'potrait')->stream('Kerusakan.pdf');

    }
    
    public function print_d($id){
        $view = prob_msn::findorfail($id);
        $view_d = prob_msd::where('prob_cod', $view->prob_cod)->get();

        $pdf = Pdf::loadview('superadmin.dashboard.problemmsn.print_d', compact('view', 'view_d'));
        return $pdf->setPaper('a4', 'potrait')->stream('Kerusakan_d.pdf');

    }

}
