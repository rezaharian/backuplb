@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')
    <div class="dekstop-only">
        <div class="card card-plain card1">
            <div class="card-header pb-0 text-left">
                <img src="/image/logos/logo-ext.png" alt="" style="width:20%;">
                <h4 class="font-weight-bolder text-dark text-center ">
                    FORMULIR PELAPORAN K3 DAN KESELAMATAN ASET (FPK3)</h4>
            </div>
            <div class="">
                <form action="/trainer/ktiga/store" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row p-0">
                        <div class="col">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr class="bg-dark text-light">
                                        <td colspan="6" style="border: 1px solid black; ">
                                            <p class="fw-bold m-0">Bagian I :
                                                Temuan Masalah/ ketidak sesuaian</p>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid black; border-right: none;"><label
                                                for="example-text-input" class="form-control-label text-secondary">Diajukan
                                                Oleh</label></td>
                                        <td style="border: 1px solid black; border-left: none; border-right: none;"><input
                                                class="form-control form-control-sm" type="text" name="pemohon"
                                                id="field1">
                                        </td>
                                        <td style="border: 1px solid black; border-right: none; border-left: none"><label
                                                for="example-text-input" class="form-control-label text-secondary">No
                                                Urut</label></td>
                                        <td style="border: 1px solid black; border-left: none; border-right: none;"><input
                                                readonly class="form-control form-control-sm" type="text" name="no_urut"
                                                value="{{ $kode }}"></td>
                                        <td style="border: 1px solid black; border-right: none; border-left: none"><label
                                                for="example-text-input" class="form-control-label text-secondary">Kode
                                                Masalah</label></td>
                                        <td style="border: 1px solid black; border-left: none;">
                                            <select name="jenis_masalah" class="form-select form-select-sm" id="field1">
                                                <option value="K3" selected>K3</option>
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
                                                @foreach ($bagian as $item)
                                                    <option value="" selected></option>
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
                                            <input class="form-control form-control-sm" type="date" name="tanggal"
                                                value="">
                                        </td>
                                    </tr>
                                    <tr>

                                        <td colspan="6" style="border: 1px solid black;">
                                            <textarea class="form-control form-control-sm mb-2" name="masalah" style="height: 150px;"></textarea>
                                            <small>Lampirkan dokumen/photo ketidak sesuaian :
                                                <input type="file" id="gambar" name="file_foto" accept="image/*"><br>
                                                <img id="preview" src="" alt="Preview Gambar"
                                                    style="max-width: 200px;">

                                            </small>
                                        </td>
                                    </tr>
                                    {{-- <div style="background-color: black;">

                                        <tr>

                                            <td colspan="1" style="border: 1px solid black;">
                                                <label for=""> Klasifikasi</label>
                                            </td>
                                            <td style="border: 1px solid black;" colspan="5">
                                                <input type="radio" name="klas_temuan" value="Ketidaksesuaian Kritis"
                                                    id="option1" disabled>
                                                <label for="option1" class="radio-label me-4">Ketidaksesuaian Kritis
                                                </label>

                                                <input type="radio" name="klas_temuan" value="Ketidaksesuaian Mayor"
                                                    id="option2" disabled>
                                                <label for="option2" class="radio-label  me-4">Ketidaksesuaian
                                                    Mayor</label>

                                                <input type="radio" name="klas_temuan" value="Ketidaksesuaian Minor"
                                                    id="option3" disabled>
                                                <label for="option3" class="radio-label  me-4">Ketidaksesuaian
                                                    Minor</label>

                                                <input type="radio" name="klas_temuan"
                                                    value="Saran perbaikan / Observasi" id="option4" disabled>
                                                <label for="option4" class="radio-label">Saran perbaikan /
                                                    Observasi</label>
                                            </td>


                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Tanda
                                                    tangan pelapor :</label>
                                                <input class="form-control form-control-sm" type="text"
                                                    name="pemohon1" id="field1" disabled>
                                            </td>
                                            <td style="border: 1px solid black; border-right: none;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Tanggal
                                                    :</label>
                                                <input class="form-control form-control-sm" type="date" name="tgl_ttd"
                                                    id="field1" disabled>
                                            </td>
                                            <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Paraf
                                                    Penerima Laporan :</label>
                                                <input class="form-control form-control-sm" type="Text"
                                                    name="penerima" id="field1" disabled>
                                            </td>
                                            <td style="border: 1px solid black; "><label for="example-text-input"
                                                    class="form-control-label text-secondary">Tanggal :</label>
                                                <input class="form-control form-control-sm" type="date"
                                                    name="tgl_terima" id="field1" disabled>
                                            </td>

                                        </tr>
                                        <tr class="bg-dark text-light">
                                            <td colspan="6" style="border: 1px solid black; ">
                                                <p class="fw-bold m-0">Bagian II :
                                                    Analisa Penyebab Timbulnya Masalah / ketidaksesuaian</p>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black;" colspan="6">
                                                <textarea class="form-control form-control-sm mb-2" name="analisa_sebab" style="height: 150px;" disabled></textarea>
                                                <div class="row">
                                                    <div class="col-md-2"> <label style="text-align: right;"
                                                            class="form-control-label text-secondary">Analis</label><input
                                                            class="form-control form-control-sm" type="text"
                                                            name="analis"id="field1" disabled></div>
                                                    <div class="col-md-2"> <label style="text-align: right;"
                                                            class="form-control-label text-secondary">Tgl
                                                            Analis</label><input class="form-control form-control-sm"
                                                            type="date" name="tgl_analis"id="field1" disabled>
                                                    </div>
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
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Penanggung
                                                    Jawab</label>

                                            </td>
                                            <td colspan="1" style="border: 1px solid black;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Batas Waktu</label>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4" style="border: 1px solid black; border-right: none;">
                                                <textarea class="form-control form-control-sm mb-2" name="perbaikan" style="height: 40px;" disabled></textarea>
                                            </td>
                                            <td colspan="1" style="border: 1px solid black; border-right: none;">
                                                <input class="form-control form-control-sm" type="text"
                                                    name="pj_perbaikan" id="field1" disabled>
                                            </td>
                                            <td colspan="1" style="border: 1px solid black;">
                                                <input class="form-control form-control-sm" type="date"
                                                    name="batas_perbaikan" id="field1" disabled>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border: 1px solid black; border-right: none;">
                                                </label>
                                                <label for="option4"class="form-control-label text-secondary">Rencana
                                                    veririfikasi Perbaikan
                                                    tanggal:</label>
                                            </td>
                                            <td colspan="4" style="border: 1px solid black; ">
                                                </label>
                                                <input class="form-control form-control-sm" type="date"
                                                    name="r_verifikasi_perbaikan" id="field1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Paraf
                                                    Ka.Dept./Unit Kerja :</label>
                                                <input class="form-control form-control-sm" type="text" name="atasan"
                                                    id="field1" disabled>
                                            </td>
                                            <td style="border: 1px solid black; border-right: none;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Tanggal
                                                    :</label>
                                                <input class="form-control form-control-sm" type="date"
                                                    name="tgl_atasan" id="field1" disabled>
                                            </td>
                                            <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Paraf PIC
                                                    /Penanggung jawab :</label>
                                                <input class="form-control form-control-sm" type="Text" name="pic"
                                                    id="field1" disabled>
                                            </td>
                                            <td style="border: 1px solid black; "><label for="example-text-input"
                                                    class="form-control-label text-secondary">Tanggal :</label>
                                                <input class="form-control form-control-sm" type="date" name="tgl_pic"
                                                    id="field1" disabled>
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
                                                <label for="example-text-input"
                                                    class="form-control-label text-secondary">Catatan
                                                    tindak lanjut:</label>
                                                <textarea class="form-control form-control-sm mb-2" name="pencegahan" style="height: 60px;" disabled></textarea>
                                            </td>
                                            <td colspan="4" style="border: 1px solid black;">
                                                <label class="form-control-label text-secondary">Status hasil
                                                    verifikasi:</label>
                                                <div>
                                                    <input type="radio" name="hasil_verifikasi" id="efektif"
                                                        value="Efektif" onclick="toggleCatatan(false)" disabled>
                                                    <label for="efektif">Efektif</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="hasil_verifikasi" id="tidakEfektif"
                                                        value="Tidak Efektif" onclick="toggleCatatan(true)" disabled>
                                                    <label for="tidakEfektif">Tidak Efektif</label>
                                                </div>
                                                <div id="catatanTidakEfektif" style="display: none;">
                                                    <label for="catatan">Catatan:</label>
                                                    <input class="form-control form-control-sm" type="text"
                                                        name="catatan_te" id="catatan" disabled>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="border: 1px solid black; ">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="border: 1px solid black; ">
                                                <label for="example-text-input"
                                                    class="form-control-label text-secondary">Rencana
                                                    verifikasi Pencegahan tanggal</label>
                                            </td>
                                            <td colspan="3" style="border: 1px solid black; ">
                                                <input class="form-control form-control-sm" type="date"
                                                    name="r_verifikasi_cegah" id="field1" disabled>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Paraf
                                                    Safety Officer :</label>
                                                <input class="form-control form-control-sm" type="text" name="so_pic"
                                                    id="field1" disabled>
                                            </td>

                                            <td colspan="4" style="border: 1px solid black;"><label
                                                    for="example-text-input"
                                                    class="form-control-label text-secondary">Tanggal</label>
                                                <input class="form-control form-control-sm" type="date" name="tgl_so"
                                                    id="field1" disabled>
                                            </td>

                                        </tr>

                                    </div> --}}
                                </table>
                        </div>
                    </div>
                    <div style="text-align: center" class="mt-2">
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                    </tr>
            </div>
            </table>
            </form>

        </div>
    </div>
    </div>
    <div class="mobile-only">
      
            </div>
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .radio-label {
            text-transform: initial;
        }

        /* untuk mengatur desktop atau hp */
        /* .dekstop-only {
            display: none;
        }

        .mobile-only {
            display: block;
        } */

        /* Atur tampilan berdasarkan ukuran layar */
        @media only screen and (min-width: 768px) {
            .dekstop-only {
                display: block;
            }

            .mobile-only {
                display: none;
            }
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
    </script>
@endsection
