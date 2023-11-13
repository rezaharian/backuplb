@extends('hr.dashboard.layout.layout')

@section('content')


<div class="dekstop-only">
    <div class="card card-plain card1">
        <div class="card-header pb-0 text-left">
            {{-- <img  src="/image/logos/logo-ext.png" alt="" style="width:20%;"> --}}
            <h4 class="font-weight-bolder text-dark text-center ">
                FORMULIR PELAPORAN K3 DAN KESELAMATAN ASET (FPK3)</h4>
        </div>
        <div class="">
            <form action="/hr/dashboard/ktiga/update/{{ $data->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class=" bg-dark text-light ">
                    <p class="fw-bold m-0">Bagian I : <br>
                        Temuan Masalah/ ketidak sesuaian</p>
                </div>
                <div class="row p-0">
                    <div class="col">   

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="border: 1px solid black; border-right: none;"><label for="example-text-input"
                                            class="form-control-label text-secondary">Diajukan
                                            Oleh</label></td>
                                    <td style="border: 1px solid black; border-left: none; border-right: none;"><input
                                            class="form-control form-control-sm" type="text" name="pemohon" value="{{ $data->pemohon }}"
                                            id="field1">
                                    </td>
                                    <td style="border: 1px solid black; border-right: none; border-left: none"><label
                                            for="example-text-input" class="form-control-label text-secondary">No
                                            Urut</label></td>
                                    <td style="border: 1px solid black; border-left: none; border-right: none;"><input
                                            readonly class="form-control form-control-sm" type="text" name="no_urut"
                                            value="{{ $data->no_urut }}"></td>
                                    <td style="border: 1px solid black; border-right: none; border-left: none"><label
                                            for="example-text-input" class="form-control-label text-secondary">Kode
                                            Masalah</label></td>
                                    <td style="border: 1px solid black; border-left: none;">
                                        <select name="jenis_masalah" class="form-select form-select-sm" id="field1">
                                            <option value="{{ $data->jenis_masalah }}" selected>{{ $data->jenis_masalah }}</option>
                                            <option value="K3" >K3</option>
                                            <option value="Umum">Umum</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid black; border-right: none;">
                                        <label for="example-text-input"
                                            class="form-control-label text-secondary">Bagian</label>
                                    </td>
                                    <td style="border: 1px solid black; border-left: none;">
                                        <select class="form-control form-control-sm" type="text" name="bagian"
                                        id="field1">
                                        <option value="{{ $data->bagian }}" selected>{{ $data->bagian }}</option>
                                        @foreach ($bagian as $item)
                                            <option value="{{ $item->bagian }}">{{ $item->bagian }}</option>
                                        @endforeach
                                    </select>
                                    </td>
                                    <td colspan="2" style="border: 1px solid black; border-right: none; "
                                        class="text-end">
                                        <label for="example-text-input"
                                            class="form-control-label text-secondary">Tanggal</label>
                                    </td>
                                    <td colspan="2" style="border: 1px solid black; border-left: none;">
                                        <input  class="form-control form-control-sm" type="date" name="tanggal" value="{{ $data->tanggal }}">
                                    </td>
                                </tr>
                                <tr>

                                    <td colspan="6" style="border: 1px solid black;">
                                        <textarea class="form-control form-control-sm mb-2" name="masalah" style="height: 150px;">{{ $data->masalah }}</textarea>
                                        <small>Lampirkan dokumen/foto ketidaksesuaian:</small>
                                        <input type="file" id="gambar" name="file_foto" accept="image/*" onchange="previewImage(event)">
                                        <br>
                                        <img id="preview" src="{{ $data->file_foto ? asset('uploads/ktiga/' . $data->file_foto) : '' }}" alt="Preview Gambar"
                                            style="max-width: 200px;">
                                        
                                    </td>
                                </tr>
                                <tr>

                                    <td colspan="1" style="border: 1px solid black;">
                                        <label for=""> Klasifikasi</label>
                                      </td>
                                      <td style="border: 1px solid black;" colspan="5">
                                        <input type="radio" name="klas_temuan" value="Ketidaksesuaian Kritis" id="option1" {{ $data->klas_temuan === 'Ketidaksesuaian Kritis' ? 'checked' : '' }}>
                                        <label for="option1" class="radio-label me-4">Ketidaksesuaian Kritis </label>
                                      
                                        <input type="radio" name="klas_temuan" value="Ketidaksesuaian Mayor" id="option2" {{ $data->klas_temuan === 'Ketidaksesuaian Mayor' ? 'checked' : '' }}>
                                        <label for="option2" class="radio-label me-4">Ketidaksesuaian Mayor</label>
                                      
                                        <input type="radio" name="klas_temuan" value="Ketidaksesuaian Minor" id="option3" {{ $data->klas_temuan === 'Ketidaksesuaian Minor' ? 'checked' : '' }}>
                                        <label for="option3" class="radio-label me-4">Ketidaksesuaian Minor</label>
                                      
                                        <input type="radio" name="klas_temuan" value="Saran perbaikan / Observasi" id="option4" {{ $data->klas_temuan === 'Saran perbaikan / Observasi' ? 'checked' : '' }}>
                                        <label for="option4" class="radio-label">Saran perbaikan / Observasi</label>
                                      </td>
                                      


                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Tanda
                                            tangan pelapor :</label>
                                        <input class="form-control form-control-sm" type="text" name="pemohon1" value="{{ $data->pemohon }}"
                                            id="field1">
                                    </td>
                                    <td style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Tanggal
                                            :</label>
                                        <input class="form-control form-control-sm" type="date" name="tgl_ttd" value="{{ $data->tgl_ttd }}"
                                            id="field1">
                                    </td>
                                    <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Paraf
                                            Penerima Laporan :</label>
                                        <input class="form-control form-control-sm" type="Text" name="penerima" value="{{ $data->penerima }}"
                                            id="field1">
                                    </td>
                                    <td style="border: 1px solid black; "><label for="example-text-input"
                                            class="form-control-label text-secondary">Tanggal :</label>
                                        <input class="form-control form-control-sm" type="date" name="tgl_terima" value="{{ $data->tgl_terima }}"
                                            id="field1">
                                    </td>

                                </tr>
                                <tr class="bg-dark text-light">
                                    <td colspan="6" style="border: 1px solid black; ">
                                        <p class="fw-bold m-0">Bagian II :
                                            Temuan Masalah/ ketidak sesuaian</p>

                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black;" colspan="6">
                                        <textarea class="form-control form-control-sm mb-2" name="analisa_sebab" style="height: 150px;">{{ $data->analisa_sebab }}</textarea>
                                        <div class="row">
                                            <div class="col-md-2">  <small style="text-align: right;">Analis</small><input class="form-control form-control-sm" type="text"  value="{{ $data->analis }}"
                                                name="analis"id="field1"></div>
                                            <div class="col-md-2">   <small style="text-align: right;">Tgl Analis</small><input class="form-control form-control-sm" type="date"  value="{{ $data->tgl_analis }}"
                                                name="tgl_analis"id="field1"></div>
                                            <div class="col-md-10"></div>
                                        </div>
                                      
                                     
                                    </td>
                                </tr>
                                <tr class="bg-dark text-light">
                                    <td colspan="6" style="border: 1px solid black; ">
                                        <p class="fw-bold m-0">Bagian III :
                                            Tindakan Perbaikan</p>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input"
                                            class="form-control-label text-secondary">Tindakan</label>

                                    </td>
                                    <td colspan="1" style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Penanggung
                                            Jawab</label>

                                    </td>
                                    <td colspan="1" style="border: 1px solid black;"><label for="example-text-input"
                                            class="form-control-label text-secondary">Batas Waktu</label>

                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="border: 1px solid black; border-right: none;">
                                        <textarea class="form-control form-control-sm mb-2" name="perbaikan" style="height: 40px;"> {{ $data->perbaikan }}</textarea>
                                    </td>
                                    <td colspan="1" style="border: 1px solid black; border-right: none;">
                                        <input class="form-control form-control-sm" type="text" name="pj_perbaikan"  value="{{ $data->pj_perbaikan }}"
                                            id="field1">
                                    </td>
                                    <td colspan="1" style="border: 1px solid black;">
                                        <input class="form-control form-control-sm" type="date" name="batas_perbaikan" value="{{ $data->batas_perbaikan }}"
                                            id="field1">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid black; border-right: none;">
                                        </label>
                                        <label for="option4" class="radio-label">Rencana veririfikasi Perbaikan
                                            tanggal:</label>
                                    </td>
                                    <td colspan="4" style="border: 1px solid black; ">
                                        </label>
                                        <input class="form-control form-control-sm" type="date" name="r_verifikasi_perbaikan" value="{{ $data->r_verifikasi_perbaikan }}"
                                            id="field1">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Paraf
                                            Ka.Dept./Unit Kerja :</label>
                                        <input class="form-control form-control-sm" type="text" name="atasan" value="{{ $data->atasan }}"
                                            id="field1">
                                    </td>
                                    <td style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Tanggal
                                            :</label>
                                        <input class="form-control form-control-sm" type="date" name="tgl_atasan" value="{{ $data->tgl_atasan }}"
                                            id="field1">
                                    </td>
                                    <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Paraf PPIC
                                            /Penanggung jawab :</label>
                                        <input class="form-control form-control-sm" type="Text" name="pic" value="{{ $data->pic }}"
                                            id="field1">
                                    </td>
                                    <td style="border: 1px solid black; "><label for="example-text-input"
                                            class="form-control-label text-secondary">Tanggal :</label>
                                        <input class="form-control form-control-sm" type="date" name="tgl_pic" value="{{ $data->tgl_pic }}"
                                            id="field1">
                                    </td>
                                </tr>
                                <tr class="bg-dark text-light">
                                    <td colspan="6" style="border: 1px solid black; ">
                                        <p class="fw-bold m-0">Bagian IV :
                                            Tindakan Pencegahan</p>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="border: 1px solid black; border-right: none;">
                                        <label for="example-text-input" class="form-control-label text-secondary">Catatan
                                            tindak lanjut:</label>
                                        <textarea class="form-control form-control-sm mb-2" name="pencegahan" style="height: 60px;">{{ $data->pencegahan }}</textarea>
                                    </td>
                                    <td colspan="4" style="border: 1px solid black;">
                                        <label class="form-control-label text-secondary">Status hasil verifikasi:</label>
                                        <div>
                                            <input type="radio" name="hasil_verifikasi" id="efektif" value="Efektif"
                                                onclick="toggleCatatan(false)" {{ $data->hasil_verifikasi === 'Efektif' ? 'checked' : '' }}>
                                            <label for="efektif">Efektif</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="hasil_verifikasi" id="tidakEfektif" value="Tidak Efektif"
                                                onclick="toggleCatatan(true)" {{ $data->hasil_verifikasi === 'Tidak Efektif' ? 'checked' : '' }}>
                                            <label for="tidakEfektif">Tidak Efektif</label>
                                        </div>
                                        <div id="catatanTidakEfektif" style="display: {{ $data->hasil_verifikasi === 'Tidak Efektif' ? 'block' : 'none' }}">
                                            <label for="catatan">Catatan:</label>
                                            <input class="form-control form-control-sm" type="text" name="catatan_te" id="catatan" value="{{ $data->catatan_te }}">
                                        </div>
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td colspan="3" style="border: 1px solid black; ">
                                        <label for="example-text-input" class="form-control-label text-secondary">Rencana
                                            verifikasi Pencegahan tanggal</label>
                                        </td>
                                        <td colspan="3" style="border: 1px solid black; ">
                                            <input class="form-control form-control-sm" type="date" name="r_verifikasi_cegah" value="{{ $data->r_verifikasi_cegah }}"
                                                id="field1">
                                        </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                            for="example-text-input" class="form-control-label text-secondary">Paraf
                                            Safety Officer :</label>
                                        <input class="form-control form-control-sm" type="text" name="so_pic" value="{{ $data->so_pic }}"
                                            id="field1">
                                    </td>

                                    <td colspan="4" style="border: 1px solid black;"><label for="example-text-input"
                                            class="form-control-label text-secondary">Tanggal</label>
                                        <input class="form-control form-control-sm" type="date" name="tgl_so" value="{{ $data->tgl_so }}"
                                            id="field1">
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div style="text-align: center" class="mt-2">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </td>
                </tr>
                </table>
            </form>

        </div>
    </div>
    </div>
    <div class="mobile-only">
<h1>bentar ya</h1>
    </div>
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .radio-label {
            text-transform: initial;
        }
        /* untuk mengatur desktop atau hp */
        .dekstop-only {
    display: none;
  }

  .mobile-only {
    display: block;
  }

  /* Atur tampilan berdasarkan ukuran layar */
  @media only screen and (min-width: 768px) {
    .dekstop-only {
      display: block;
    }

    .mobile-only {
      display: none;
    }
  }
  .form-control{
    border-radius: 0;
  }
    </style>

    <script>
        // Preview gambar saat dipilih
        const gambar = document.getElementById('gambar');
        const preview = document.getElementById('preview');

        gambar.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    preview.src = reader.result;
                });
                reader.readAsDataURL(file);
            }
        });


        function toggleCatatan(show) {
            var catatanTidakEfektif = document.getElementById("catatanTidakEfektif");
            catatanTidakEfektif.style.display = show ? "block" : "none";
        }

        // gambar
        // Fungsi untuk menampilkan preview gambar
function previewImage(event) {
    var input = event.target;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        // Jika tidak ada gambar yang dipilih, tampilkan gambar dari database
        document.getElementById('preview').src = '{{ $data->file_foto ? asset('uploads/ktiga/' . $data->file_foto) : '' }}';
    }
}
package
    </script>
@endsection