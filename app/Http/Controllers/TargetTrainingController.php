<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use App\Models\pegawai;
use App\Models\TargetTraining;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Unique;

class TargetTrainingController extends Controller
{
    public function list()
    {
        $target = TargetTraining::select('id', 'bagian', 'jumlah_jam', DB::raw("DATE_FORMAT(target_trainings.tgl_input, '%d-%m-%Y') AS tgl_input_f"), DB::raw("DATE_FORMAT(target_trainings.periode_awal, '%d-%m-%Y') AS periode_awal_f"), DB::raw("DATE_FORMAT(target_trainings.periode_akhir, '%d-%m-%Y') AS periode_akhir_f"))
            ->orderBy('periode_akhir', 'desc')
            ->orderBy('bagian', 'asc')
            ->get();
        $bagian = bagian::orderBy('bagian', 'asc')->get();

        return view('hr/dashboard/training/target/list', compact('target', 'bagian'));
    }

    public function create(Request $request)
    {
        $target = TargetTraining::create([
            'tgl_input' => $request->tgl_input,
            'bagian' => $request->bagian,
            'jumlah_jam' => $request->jumlah_jam,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
        ]);

        return redirect()
            ->route('hr.training.target.list')
            ->with('success', 'New Target has been added.');
    }

    public function edit($id, Request $request)
    {
        $bagian = bagian::orderBy('bagian', 'asc')->get();
        $data = TargetTraining::findorfail($id);

        return view('hr/dashboard/training/target/edit', compact('data', 'bagian'));
    }

    public function update(Request $request, $id)
    {
        $target = TargetTraining::findOrFail($id);
        $target->tgl_input = $request->tgl_input;
        $target->bagian = $request->bagian;
        $target->jumlah_jam = $request->jumlah_jam;
        $target->periode_awal = $request->periode_awal;
        $target->periode_akhir = $request->periode_akhir;
        $target->save();

        return redirect()
            ->route('hr.training.target.list')
            ->with('success', 'target has been Update.');
    }

    public function delete($id)
    {
        $target = TargetTraining::findOrFail($id);
        $target->delete();
        return redirect()
            ->route('hr.training.target.list')
            ->with('success', 'Target Berhasil di Hapus');
    }

    public function laporan_list(Request $request)
    {
        // $target = TargetTraining::select('id','bagian','jumlah_jam',DB::raw("DATE_FORMAT(target_trainings.tgl_input, '%d-%m-%Y') AS tgl_input_f"), DB::raw("DATE_FORMAT(target_trainings.periode_awal, '%d-%m-%Y') AS periode_awal_f"),DB::raw("DATE_FORMAT(target_trainings.periode_akhir, '%d-%m-%Y') AS periode_akhir_f"))
        // ->orderBy('id', 'ASC')->get();

        // dd($request->toArray());
        $bagian = bagian::orderBy('bagian', 'asc')->get();
        $req_bagian = $request->bagian;

   
        $periode_target = TargetTraining::select('periode_awal', 'periode_akhir')
            ->groupBy('periode_awal', 'periode_akhir')
            ->get();

        if (request()->train_dat_awal || request()->train_dat_akhir) {
            $train_dat_awal = Carbon::parse(request()->train_dat_awal)->toDateString();
            $train_dat_akhir = Carbon::parse(request()->train_dat_akhir)->toDateString();
            $train_dat_awal_target = Carbon::parse(request()->train_dat_awal_target)->toDateString();

            $train_dat_akhir_target = TargetTraining::select('periode_akhir')
                ->where('periode_awal', $request->train_dat_awal_target)
                ->first();

            $train_dat_akhir_targett = date('Y-m-d', strtotime($train_dat_akhir_target->periode_akhir));

            // dd($train_dat_akhir_targett);

            $t1 = Carbon::createFromFormat('Y-m-d', $train_dat_awal)->format('d-m-Y');
            $t2 = Carbon::createFromFormat('Y-m-d', $train_dat_akhir)->format('d-m-Y');

            $data1 = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select('pegawais.bagian', DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'))
                ->where('train_hs.tipe', 'Pelatihan')
                ->where('pegawais.tgl_keluar', null)
                ->whereNull('train_ds.deleted_at')
                ->whereBetween('train_hs.train_dat', [$train_dat_awal, $train_dat_akhir])
                ->groupBy('pegawais.bagian');

            $data2 = DB::table('pegawais')
                ->join('target_trainings', function ($join) use ($train_dat_awal_target, $train_dat_akhir_targett) {
                    $join
                        ->on('pegawais.bagian', '=', 'target_trainings.bagian')
                        ->where('periode_awal', '>=', $train_dat_awal_target)
                        ->where('periode_akhir', '<=', $train_dat_akhir_targett)
                        ->where('pegawais.tgl_keluar', null)
                        ->whereRaw('LOWER(pegawais.jabatan) NOT LIKE ?', ['%manager%'])

                ;
                })
                ->select('pegawais.bagian', DB::raw('SUM(target_trainings.jumlah_jam) as target'))
                ->where('pegawais.tgl_keluar', null)

                ->groupBy('pegawais.bagian');

                if ($request->bagian) {

                $requestedBagian = $request->bagian;

                $data = DB::table(DB::raw("({$data2->toSql()}) as sub1"))
                ->mergeBindings($data2)
                ->leftJoinSub($data1, 'sub2', function ($join) {
                    $join->on('sub2.bagian', '=', 'sub1.bagian');
                })
                ->select('sub1.bagian', 'sub2.totaljam', 'sub1.target', DB::raw('IFNULL(sub1.target, 0) - sub2.totaljam as kurangjam'))
                ->where('sub1.bagian', $requestedBagian)
                ->get();
            }else{
                $data = DB::table(DB::raw("({$data2->toSql()}) as sub1"))
                ->mergeBindings($data2)
                ->leftJoinSub($data1, 'sub2', function ($join) {
                    $join->on('sub2.bagian', '=', 'sub1.bagian');
                })
                ->select('sub1.bagian', 'sub2.totaljam', 'sub1.target', DB::raw('IFNULL(sub1.target, 0) - sub2.totaljam as kurangjam'))
                ->get(); 
            }

            // dd($data1);
        } else {
            $train_dat_awal_i = Carbon::parse(request()->train_dat_awal)->toDateString();
            $train_dat_akhir_i = Carbon::parse(request()->train_dat_akhir)->toDateString();
            $train_dat_awal_target = '';
            $train_dat_awal = '';
            $train_dat_akhir = '';

            $t1 = '';
            $t2 = '';
            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->leftjoin('target_trainings', function ($join) use ($train_dat_akhir_i) {
                    $join
                        ->on('pegawais.bagian', '=', 'target_trainings.bagian')
                        ->whereNull('deleted_at')
                        ->where('periode_awal', '<=', $train_dat_akhir_i)
                        ->where('periode_akhir', '>=', $train_dat_akhir_i)
                        ->where('pegawais.tgl_keluar', null)
                        ->whereRaw('LOWER(pegawais.jabatan) NOT LIKE ?', ['%manager%'])
             ;   })
                ->select(DB::raw('SUM(target_trainings.jumlah_jam) as jumlah_jamm'), 'pegawais.bagian', DB::raw('ROUND(SUM(target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'))
                ->groupBy('bagian', 'jumlah_jam')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->where('train_hs.tipe', 'Pelatihan')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->orderBy('bagian', 'ASC')
                ->limit(0)
                ->get();
        }

        // dd($data->toArray());
        return view('hr/dashboard/training/target/laporan/list', compact('req_bagian','bagian','train_dat_awal_target', 'periode_target', 'data', 'train_dat_awal', 'train_dat_akhir', 't1', 't2'));
    }

    public function laporan_list_print(Request $request)
    {
        // dd($request->toArray());
        $w1 = Carbon::parse(request()->train_dat_awal)->toDateString();
        $w2 = Carbon::parse(request()->train_dat_akhir)->toDateString();

        $w11 = Carbon::parse(request()->train_dat_awal_target)->toDateString();

        $train_dat_akhir_target = TargetTraining::select('periode_akhir')
            ->where('periode_awal', $request->train_dat_awal_target)
            ->first();

        $train_dat_akhir_targett = date('Y-m-d', strtotime($train_dat_akhir_target->periode_akhir));

        $t1 = Carbon::createFromFormat('Y-m-d', $w1)->format('d-m-Y');
        $t2 = Carbon::createFromFormat('Y-m-d', $w2)->format('d-m-Y');

        if (request()->train_dat_awal || request()->train_dat_akhir) {
            $data1 = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->select('pegawais.bagian', DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'))
                ->where('train_hs.tipe', 'Pelatihan')
                ->where('pegawais.tgl_keluar', null)
                ->whereNull('train_ds.deleted_at')
                ->whereBetween('train_hs.train_dat', [$w1, $w2])
                ->groupBy('pegawais.bagian');

            $data2 = DB::table('pegawais')
                ->join('target_trainings', function ($join) use ($train_dat_akhir_targett, $w11) {
                    $join
                        ->on('pegawais.bagian', '=', 'target_trainings.bagian')
                        ->where('periode_awal', '>=', $w11)
                        ->where('periode_akhir', '<=', $train_dat_akhir_targett)
                        ->where('pegawais.tgl_keluar', null)
                        ->whereRaw('LOWER(pegawais.jabatan) NOT LIKE ?', ['%manager%']);
                })
                ->select('pegawais.bagian', DB::raw('SUM(target_trainings.jumlah_jam) as target'))
                ->where('pegawais.tgl_keluar', null)
                ->groupBy('pegawais.bagian');

                if ($request->req_bagian) {

                    $requestedBagian = $request->req_bagian;
            $data = DB::table(DB::raw("({$data2->toSql()}) as sub1"))
                ->mergeBindings($data2)
                ->leftJoinSub($data1, 'sub2', function ($join) {
                    $join->on('sub2.bagian', '=', 'sub1.bagian');
                })
                ->select('sub1.bagian', 'sub2.totaljam', 'sub1.target', DB::raw('IFNULL(sub1.target, 0) - sub2.totaljam as kurangjam'))
               ->where('sub1.bagian', $requestedBagian)
                ->get();
            }else{
                $data = DB::table(DB::raw("({$data2->toSql()}) as sub1"))
                ->mergeBindings($data2)
                ->leftJoinSub($data1, 'sub2', function ($join) {
                    $join->on('sub2.bagian', '=', 'sub1.bagian');
                })
                ->select('sub1.bagian', 'sub2.totaljam', 'sub1.target', DB::raw('IFNULL(sub1.target, 0) - sub2.totaljam as kurangjam'))
                ->get();
                }
        } else {
            $data = DB::table('train_ds')
                ->join('pegawais', 'train_ds.no_payroll', '=', 'pegawais.no_payroll')
                ->leftJoin('train_hs', 'train_ds.train_cod', '=', 'train_hs.train_cod')
                ->leftjoin('target_trainings', function ($join) use ($w2) {
                    $join
                        ->on('pegawais.bagian', '=', 'target_trainings.bagian')
                        ->where('periode_awal', '<=', $w2)
                        ->where('periode_akhir', '>=', $w2);
                })
                ->select(DB::raw('SUM(target_trainings.jumlah_jam) as jumlah_jamm'), 'pegawais.bagian', DB::raw('ROUND(SUM(target_trainings.jumlah_jam) - (sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ) ,2 ) as kurangjam'), DB::raw('ROUND(sum(((((SUBSTR(train_hs.sdjam, 1,2)* 60 ) + (SUBSTR(train_hs.sdjam, 4,2) ))) - (((SUBSTR(train_hs.jam, 1,2)* 60 ) + (SUBSTR(train_hs.jam, 4,2) )))) / 60 ) ,2 ) AS totaljam'))
                ->groupBy('bagian', 'jumlah_jam')
                ->where('train_hs.approve', 'YES')
                ->where('train_ds.approve', 'Y')
                ->where('train_hs.tipe', 'Pelatihan')
                ->whereNull('deleted_at')
                ->where('pegawais.tgl_keluar', null)
                ->orderBy('bagian', 'ASC')
                ->limit(0)
                ->get();
        }

        $pdf = Pdf::loadview('hr/dashboard/training/target/laporan/print', compact('data', 't1', 't2'));
        return $pdf->setPaper('a4', 'potrait')->stream('Training.pdf');
    }
}
