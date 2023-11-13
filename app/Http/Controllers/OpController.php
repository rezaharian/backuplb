<?php

namespace App\Http\Controllers;

use App\Models\prob_msd;
use App\Models\prob_msn;
use App\Models\unit_msn;
use App\Models\vmacunit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpController extends Controller
{
    public function produksi(Request $request)
{
    $namaprofile = Auth::user();

    return view('op.dashboard.problemmsn.produksi', compact('namaprofile'));

}
    public function index(Request $request)
    {

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
        return view('op.dashboard.problemmsn.index', compact('namaprofile', 'list','datau','datal','list'));
    }

    public function list()
    {
        $prob_h = prob_msn::orderby('id', 'desc')->get();

        $namaprofile = Auth::user();
        return view('op.dashboard.problemmsn.list', compact('namaprofile', 'prob_h'));
    }

    public function view($id)
    {
        $view = prob_msn::findorfail($id);
        // dd($view->toArray());
        $view_d = prob_msd::where('prob_cod', $view->prob_cod)->get();
        $namaprofile = Auth::user();
       
        // dd($view_d->toArray());
        return view('op.dashboard.problemmsn.view', compact('view', 'view_d','namaprofile'));
    }

    public function view_d($id)
    {
        $v = prob_msd::findorfail($id);
        $view =  prob_msn::where('prob_cod', $v->prob_cod)->first();
        $view_d = prob_msd::where('id',$id)->first();


        $namaprofile = Auth::user();
       
        return view('op.dashboard.problemmsn.view', compact('view', 'view_d','namaprofile'));
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
}    


