<?php

namespace App\Http\Controllers;

use App\Models\bagian;
use App\Models\ktiga;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;






class KtigaController extends Controller
{
    public function list()
    {
        $ktiga = ktiga::orderBy('id', 'desc')->get();

        return view('hr.dashboard.ktiga.list', compact('ktiga'));
    }
    public function create()
    {
        $bagian = bagian::orderBy('bagian', 'asc')->get();
        $awal = ktiga::select('no_urut')
            ->latest()
            ->first();

        if ($awal) {
            // Jika data awal ditemukan
            $nomor_urut_terakhir = $awal->no_urut;
        } else {
            // Jika data awal tidak ada, set nilai awal ke "000"
            $nomor_urut_terakhir = '000';
        }

        $now = Carbon::now();
        $tahun = $now->format('y');
        $bulan = $now->format('m');

        // Mengambil 3 digit terakhir dari nomor urut terakhir
        $nomor_urut_terakhir_3_digit = substr($nomor_urut_terakhir, -3);

        // Increment nomor urut terakhir
        $nomor_urut_baru = $nomor_urut_terakhir_3_digit + 1;

        // Format nomor urut baru menjadi 3 digit
        $nomor_urut_baru_3_digit = sprintf('%03d', $nomor_urut_baru);

        // Menggabungkan tahun, bulan, dan nomor urut baru
        $kode = $tahun . $bulan . $nomor_urut_baru_3_digit;

        return view('hr.dashboard.ktiga.create', compact('kode', 'bagian'));
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
            ->route('hr.ktiga.list')
            ->with('success', 'Subject has been Created.');
    }

    public function edit($id, Request $request)
    {
        $data = ktiga::findorfail($id);
        $bagian = bagian::orderBy('bagian', 'asc')->get();

        return view('hr/dashboard/ktiga/edit', compact('data', 'bagian'));
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
            ->route('hr.ktiga.list')
            ->with('success', 'Subject has been Updated.');
    }

    public function delete($id)
    {
        $ktiga = ktiga::findOrFail($id);
        if ($ktiga->file_foto && file_exists(public_path('uploads/ktiga/' . $ktiga->file_foto))) {
            unlink(public_path('uploads/ktiga/' . $ktiga->file_foto));
        }

        $ktiga->delete();
        return redirect()
            ->route('hr.ktiga.list')
            ->with('success', 'Data Berhasil di Hapus');
    }

    public function print($id, Request $request)
    {
        $data = ktiga::find($id);

        if ($data) {
            $data->tanggal = $data->tanggal
                ? Carbon::parse($data->tanggal)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->tgl_ttd = $data->tgl_ttd
                ? Carbon::parse($data->tgl_ttd)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->tgl_terima = $data->tgl_terima
                ? Carbon::parse($data->tgl_terima)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->tgl_analis = $data->tgl_analis
                ? Carbon::parse($data->tgl_analis)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->tgl_atasan = $data->tgl_atasan
                ? Carbon::parse($data->tgl_atasan)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->tgl_pic = $data->tgl_pic
                ? Carbon::parse($data->tgl_pic)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->tgl_so = $data->tgl_so
                ? Carbon::parse($data->tgl_so)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->batas_perbaikan = $data->batas_perbaikan
                ? Carbon::parse($data->batas_perbaikan)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->r_verifikasi_perbaikan = $data->r_verifikasi_perbaikan
                ? Carbon::parse($data->r_verifikasi_perbaikan)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;
            $data->r_verifikasi_cegah = $data->r_verifikasi_cegah
                ? Carbon::parse($data->r_verifikasi_cegah)
                    ->locale('id')
                    ->isoFormat('D MMMM Y')
                : null;

            // Lanjutkan dengan tindakan lainnya jika data ditemukan
        } else {
            // Tindakan yang diambil jika data tidak ditemukan
        }

        $pdf = Pdf::loadview('hr.dashboard.ktiga.print', compact('data'));
        return $pdf->setPaper('a4', 'portrait')->stream('Ktiga.pdf');
        // return view('hr.dashboard.ktiga.print', compact('data'));
    }

    public function reportktiga()
    {
        return view('hr.dashboard.ktiga.reportktiga.indexreport');
    }

    public function reportktigaprint(Request $request)
    {
        $periodeAwal = date('d-m-Y', strtotime($request->periode_awal));
        $periodeAkhir = date('d-m-Y', strtotime($request->periode_akhir));
        

        $awal = $request->periode_awal;
        $akhir = $request->periode_akhir;
        $jenis = $request->jenis_masalah;
        $tgl_sekarang = Carbon::now()
            ->locale('id')
            ->translatedFormat('l, d F Y');

        $data = ktiga::where('jenis_masalah', 'like', '%' . $jenis . '%')
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderBy('id', 'asc')
            ->get();

        // dd($data->toArray());
        if ($request->export == 'PDF') {
            $pdf = Pdf::loadview('hr.dashboard.ktiga.reportktiga.reportktigaprint', compact('tgl_sekarang', 'data', 'periodeAwal', 'periodeAkhir', 'jenis'));
            return $pdf->setPaper('a4', 'landscape')->stream('Training.pdf');

            } else {

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                // Mengatur lebar kolom
                $sheet->getColumnDimension('A')->setWidth(3);
                $sheet->getColumnDimension('B')->setWidth(10);
                $sheet->getColumnDimension('C')->setWidth(11);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(22);
                $sheet->getColumnDimension('F')->setWidth(7);
                $sheet->getColumnDimension('G')->setWidth(33);
                $sheet->getColumnDimension('H')->setWidth(23);
                $sheet->getColumnDimension('I')->setWidth(23);
                $sheet->getColumnDimension('J')->setWidth(30);
                $sheet->getColumnDimension('K')->setWidth(10);
                $sheet->getColumnDimension('L')->setWidth(10);
                $sheet->getColumnDimension('M')->setWidth(30);
                $sheet->getColumnDimension('N')->setWidth(10);
                $sheet->getColumnDimension('O')->setWidth(15);
                
                // Menambahkan border pada header
                $headerStyle = $sheet->getStyle('A1:o1');
                $headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                $headerStyle->getFont()->setBold(true);
                
                $sheet->setCellValue('A1', 'No');
                $sheet->setCellValue('B1', 'No Urut');
                $sheet->setCellValue('C1', 'Tanggal');
                $sheet->setCellValue('D1', 'Pemohon');
                $sheet->setCellValue('E1', 'Bagian');
                $sheet->setCellValue('F1', 'Jenis');
                $sheet->setCellValue('G1', 'Masalah');
                $sheet->setCellValue('H1', 'Klas Temuan');
                $sheet->setCellValue('I1', 'Analisa sebab');
                $sheet->setCellValue('J1', 'Perbaikan');
                $sheet->setCellValue('K1', 'Batas Perbaikan');
                $sheet->setCellValue('L1', 'Verifikasi Perbaikan');
                $sheet->setCellValue('M1', 'Pencegahan');
                $sheet->setCellValue('N1', 'Verifikasi Pencegahan');
                $sheet->setCellValue('O1', 'Hasil Verifikasi');
        
                
                $row = 2;
                $no = 1;
                foreach ($data as $item) {
                    $sheet->setCellValue('A' . $row, $no++);
                    $sheet->setCellValue('B' . $row, $item->no_urut);
                    $sheet->setCellValue('C' . $row, $item->tanggal);
                    $sheet->setCellValue('D' . $row, $item->pemohon);
                    $sheet->setCellValue('E' . $row, $item->bagian);
                    $sheet->setCellValue('F' . $row, $item->jenis_masalah);
                    $sheet->setCellValue('G' . $row, $item->masalah);
                    $sheet->setCellValue('H' . $row, $item->klas_temuan);
                    $sheet->setCellValue('I' . $row, $item->analisa_sebab);
                    $sheet->setCellValue('J' . $row, $item->perbaikan);
                    $sheet->setCellValue('K' . $row, $item->batas_perbaikan);
                    $sheet->setCellValue('L' . $row, $item->r_verifikasi_perbaikan);
                    $sheet->setCellValue('M' . $row, $item->pencegahan);
                    $sheet->setCellValue('N' . $row, $item->r_verifikasi_cegah);
                    $sheet->setCellValue('O' . $row, $item->hasil_verifikasi);
                    $row++;
                }
                
                // Mengatur border pada seluruh data
                $dataStyle = $sheet->getStyle('A1:O' . ($row - 1));
                $headerRange = 'A1:O1'; // Range sel untuk header
                $dataRange = 'A2:O' . ($row - 1); // Range sel untuk tbody
                
                $headerStyle = $sheet->getStyle($headerRange);
                $dataStyle = $sheet->getStyle($dataRange);
                
                $headerStyle->getAlignment()->setWrapText(false); // Tidak menerapkan wrap text pada header
                $dataStyle->getAlignment()->setWrapText(true); // Menerapkan wrap text pada tbody
                $dataStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                
            
                    // Membuat objek Writer untuk menulis ke file Xlsx
                    $writer = new Xlsx($spreadsheet);
            
                    // Menyimpan file Xlsx
                    $filename = 'Rekap PK3-'. $tgl_sekarang.' .xlsx';
                    $writer->save($filename);
            
                    // Mengirim file sebagai response
                    return response()->download($filename)->deleteFileAfterSend();        }
        

       
    }



}
