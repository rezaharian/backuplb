<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use App\Models\ktiga;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KtigaUserController extends Controller
{
    public function list()
    {
        $ktiga = ktiga::orderBy('id', 'desc')->get();
        $namaprofile = Auth::user();

        return view('hr.dashboard.training.trainer.ktiga.list', compact('ktiga','namaprofile'));
    }
    public function create()
    {
        $namaprofile = Auth::user();
        $bagian = bagian::orderBy('bagian', 'asc')->get();
        $awal = ktiga::select('no_urut')->latest()->first();

        if ($awal) {
            // Jika data awal ditemukan
            $nomor_urut_terakhir = $awal->no_urut;
        } else {
            // Jika data awal tidak ada, set nilai awal ke "000"
            $nomor_urut_terakhir = "000";
        }
        
        $now = Carbon::now();
        $tahun = $now->format('y');
        $bulan = $now->format('m');
        
        // Mengambil 3 digit terakhir dari nomor urut terakhir
        $nomor_urut_terakhir_3_digit = substr($nomor_urut_terakhir, -3);
        
        // Increment nomor urut terakhir
        $nomor_urut_baru = $nomor_urut_terakhir_3_digit + 1;
        
        // Format nomor urut baru menjadi 3 digit
        $nomor_urut_baru_3_digit = sprintf("%03d", $nomor_urut_baru);
        
        // Menggabungkan tahun, bulan, dan nomor urut baru
        $kode = $tahun . $bulan . $nomor_urut_baru_3_digit;
        
        return view('hr.dashboard.training.trainer.ktiga.create', compact('kode', 'bagian','namaprofile'));
    }
    public function store(Request $request)
    {
        // dd($request->toArray());

        $request->validate([
            'file_foto' => 'mimes:pdf,xls,xlsx,doc,docx,ppt,pptx,jpg,jpeg,png,|max:9048',
        ]);

        // Jika ada file yang diunggah, simpan file ke direktori /uploads/materi
        if ($request->hasFile('file_foto')) {
            $file = $request->file('file_foto');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads/ktiga'), $filename);
        }

        $ktiga = ktiga::create([
            'pemohon' => $request->pemohon,
            'no_urut' => $request->no_urut,
            'jenis_masalah' => $request->jenis_masalah,
            'bagian' => $request->bagian,
            'tanggal' => $request->tanggal,
            'masalah' => $request->masalah,
            'klas_temuan' => $request->klas_temuan,
            'tgl_ttd' => $request->tgl_ttd,
            'penerima' => $request->penerima,
            'tgl_terima' => $request->tgl_terima,
            'analisa_sebab' => $request->analisa_sebab,
            'analis' => $request->analis,
            'tgl_analis' => $request->tgl_analis,
            'perbaikan' => $request->perbaikan,
            'pj_perbaikan' => $request->pj_perbaikan,
            'batas_perbaikan' => $request->batas_perbaikan,
            'r_verifikasi_perbaikan' => $request->r_verifikasi_perbaikan,
            'atasan' => $request->atasan,
            'tgl_atasan' => $request->tgl_atasan,
            'pic' => $request->pic,
            'tgl_pic' => $request->tgl_pic,
            'pencegahan' => $request->pencegahan,
            'hasil_verifikasi' => $request->hasil_verifikasi,
            'r_verifikasi_cegah' => $request->r_verifikasi_cegah,
            'catatan_te' => $request->catatan_te,
            'so_pic' => $request->so_pic,
            'tgl_so' => $request->tgl_so,
            'file_foto' => $filename ?? null,
        ]);

        return redirect()
            ->route('hr.trainer.ktiga.list')
            ->with('success', 'Subject has been Created.');
    }

    public function edit($id, Request $request)
    {
        $namaprofile = Auth::user();
        $data = ktiga::findorfail($id);
        $bagian = bagian::orderBy('bagian', 'asc')->get();


        return view('hr.dashboard.training.trainer.ktiga.edit', compact('data','namaprofile','bagian'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'file_foto' => 'mimes:pdf,xls,xlsx,doc,docx,ppt,pptx,jpg,jpeg,png,|max:9048',
        ]);

        $ktiga = ktiga::findOrFail($id);
        $ktiga->no_urut = $request->no_urut;
        $ktiga->tanggal = $request->tanggal;
        $ktiga->pemohon = $request->pemohon;
        $ktiga->bagian = $request->bagian;
        $ktiga->jenis_masalah = $request->jenis_masalah;
        $ktiga->masalah = $request->masalah;
        // $ktiga->file_foto = $file_foto;
        $ktiga->klas_temuan = $request->klas_temuan;
        $ktiga->tgl_ttd = $request->tgl_ttd;
        $ktiga->penerima = $request->penerima;
        $ktiga->tgl_terima = $request->tgl_terima;
        $ktiga->analisa_sebab = $request->analisa_sebab;
        $ktiga->analis = $request->analis;
        $ktiga->tgl_analis = $request->tgl_analis;
        $ktiga->perbaikan = $request->perbaikan;
        $ktiga->pj_perbaikan = $request->pj_perbaikan;
        $ktiga->batas_perbaikan = $request->batas_perbaikan;
        $ktiga->r_verifikasi_perbaikan = $request->r_verifikasi_perbaikan;
        $ktiga->atasan = $request->atasan;
        $ktiga->tgl_atasan = $request->tgl_atasan;
        $ktiga->pic = $request->pic;
        $ktiga->tgl_pic = $request->tgl_pic;
        $ktiga->pencegahan = $request->pencegahan;
        $ktiga->hasil_verifikasi = $request->hasil_verifikasi;
        $ktiga->catatan_te = $request->catatan_te;
        $ktiga->r_verifikasi_cegah = $request->r_verifikasi_cegah;
        $ktiga->so_pic = $request->so_pic;
        $ktiga->tgl_so = $request->tgl_so;

        // Jika ada file yang diunggah
        if ($request->hasFile('file_foto')) {
            $file = $request->file('file_foto');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads/ktiga/'), $filename);

            // Hapus file lama jika ada
            if ($ktiga->file_foto && file_exists(public_path('uploads/ktiga/' . $ktiga->file_ktiga))) {
                unlink(public_path('uploads/ktiga/' . $ktiga->file_foto));
            }

            $ktiga->file_foto = $filename;
        }

        $ktiga->save();

        return redirect()
            ->route('hr.trainer.ktiga.list')
            ->with('success', 'Subject has been Updated.');
    }


    public function delete($id)
    {
        $ktiga = ktiga::findOrFail($id);
        if ( $ktiga->file_foto && file_exists(public_path('uploads/ktiga/' .  $ktiga->file_foto))) {
            unlink(public_path('uploads/ktiga/' .  $ktiga->file_foto));
        }

        $ktiga->delete();
        return redirect()
            ->route('hr.trainer.ktiga.list')
            ->with('success', 'Data Berhasil di Hapus');
        
    }

    public function print($id, Request $request)
    {
        $data = ktiga::find($id);

        if ($data) {
            $data->tanggal = $data->tanggal ? Carbon::parse($data->tanggal)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->tgl_ttd = $data->tgl_ttd ? Carbon::parse($data->tgl_ttd)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->tgl_terima = $data->tgl_terima ? Carbon::parse($data->tgl_terima)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->tgl_analis = $data->tgl_analis ? Carbon::parse($data->tgl_analis)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->tgl_atasan = $data->tgl_atasan ? Carbon::parse($data->tgl_atasan)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->tgl_pic = $data->tgl_pic ? Carbon::parse($data->tgl_pic)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->tgl_so = $data->tgl_so ? Carbon::parse($data->tgl_so)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->batas_perbaikan = $data->batas_perbaikan ? Carbon::parse($data->batas_perbaikan)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->r_verifikasi_perbaikan = $data->r_verifikasi_perbaikan ? Carbon::parse($data->r_verifikasi_perbaikan)->locale('id')->isoFormat('D MMMM Y') : null;
            $data->r_verifikasi_cegah = $data->r_verifikasi_cegah ? Carbon::parse($data->r_verifikasi_cegah)->locale('id')->isoFormat('D MMMM Y') : null;
            
            // Lanjutkan dengan tindakan lainnya jika data ditemukan
        } else {
            // Tindakan yang diambil jika data tidak ditemukan
        }
        
    
        $pdf = Pdf::loadview('hr.dashboard.training.trainer.ktiga.print', compact('data'));
        return $pdf->setPaper('a4', 'portrait')->stream('Ktiga.pdf');
        // return view('hr.dashboard.ktiga.print', compact('data'));
    }
}
