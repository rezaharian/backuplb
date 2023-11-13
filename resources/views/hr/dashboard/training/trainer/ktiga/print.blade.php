<style>
    hr {
        width: 2500%;
        height: 2px;
        border: none;
        background-color: black;
        margin: 2px auto;
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        /* font-style: bold; */
        /* text-transform: uppercase; */

    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 9pt;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 5px;
        text-align: left;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    textarea {
        border: none;
        resize: none;
    }

    .checkbox-label {
        display: inline-flex;
        align-items: center;
        margin-right: 10px;
    }

    .form-group {
        display: flex;
        align-items: center;
    }

    .form-group label {
        margin-right: 10px;
    }

    .head-text {
        font-size: 12pt;

    }
    .header-text{
        font-style: bold;
    }
</style>

<body>
    <div style="border: 1px solid black; padding-left:3%;  ">

        <img  src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:18%;">
        <div style="text-align: center;">
            <p class="header-text">FORMULIR PELAPORAN K3 DAN KESELAMATAN ASET (FPK3)</p>
        </div>
    </div>

    <div>

        <table class="bordered-table">
           
            <tr style="background: black; color:white;">
                <td colspan="6" style="border: 1px solid black; ">
                    <strong class="head-text">Bagian I : Temuan Masalah/ ketidak sesuaian</strong>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; border-right: none;">
                    <strong for="example-text-input">Diajukan Oleh</strong>
                </td>
                <td style="border: 1px solid black; border-left: none; border-right: none;">
                    <strong for="example-text-input"> : {{ $data->pemohon }}</strong>
                </td>
                <td style="border: 1px solid black; border-right: none; border-left: none; text-align:right;">
                    <strong for="example-text-input">No</strong>
                </td>
                <td style="border: 1px solid black; border-left: none; border-right: none;">
                    <strong for="example-text-input"> : {{ $data->no_urut }}</strong>
                </td>
                <td style="border: 1px solid black; border-right: none; border-left: none; text-align:right;">
                    <strong for="example-text-input">Kode Masalah</strong>
                </td>
                <td style="border: 1px solid black; border-left: none; border-right: none;">
                    <strong for="example-text-input"> : {{ $data->jenis_masalah }}</strong>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; border-right: none;">
                    <label for="example-text-input">Departemen /Unit Kerja</label>
                </td>
                <td colspan="2" style="border: 1px solid black; border-left: none; ">
                    <label for="example-text-input"> : {{ $data->bagian }}</label>
                </td>
                <td colspan="" style="border: 1px solid black; border-right: none; ">
                    <label for="example-text-input">Tanggal</label>
                </td>
                <td colspan="2" style="border: 1px solid black; border-left: none; border-right: none;">
                    <label for="example-text-input"> : {{ $data->tanggal }}</label>
                </td>
            </tr>
            <tr>

                <td colspan="6" style="border: 1px solid black;">
                    <textarea name="masalah" style="height: 70px; font-family: 'Times New Roman', Times, serif;">{{ $data->masalah }}</textarea>
                    <small>Lampiran dokumen/foto ketidaksesuaian:</small>
                    {{-- <img src="{{ public_path('/image/ktiga/'. $data->file_foto ) }}" alt="" style="width:20%;"> --}}


                </td>
            </tr>
            <tr>

                <td colspan="1" style="border: 1px solid black;">
                    <label for=""> Klasifikasi Temuan</label>
                </td>
                <td style="border: 1px solid black;" colspan="5">
                    <input type="checkbox" style="margin-left: 6%;" name="klas_temuan" value="Ketidaksesuaian Kritis"
                        id="option1" {{ $data->klas_temuan === 'Ketidaksesuaian Kritis' ? 'checked' : '' }}>
                    <label for="option1" class="checkbox-label">Ketidaksesuaian Kritis</label>

                    <input type="checkbox" style="margin-left: 6%;" name="klas_temuan" value="Ketidaksesuaian Mayor"
                        id="option2" {{ $data->klas_temuan === 'Ketidaksesuaian Mayor' ? 'checked' : '' }}>
                    <label for="option2" class="checkbox-label">Ketidaksesuaian Mayor</label>

                    <input type="checkbox" style="margin-left: 6%;" name="klas_temuan" value="Ketidaksesuaian Minor"
                        id="option3" {{ $data->klas_temuan === 'Ketidaksesuaian Minor' ? 'checked' : '' }}>
                    <label for="option3" class="checkbox-label">Ketidaksesuaian Minor</label>

                    <input type="checkbox" style="margin-left: 6%;" name="klas_temuan"
                        value="Saran perbaikan / Observasi" id="option4"
                        {{ $data->klas_temuan === 'Saran perbaikan / Observasi' ? 'checked' : '' }}>
                    <label for="option4" class="checkbox-label">Saran perbaikan / Observasi</label>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; border-right: none;">
                    <label for="example-text-input">Tanda tangan pelapor :</label> <br>
                    <label for="example-text-input">{{ $data->pemohon }}</label>
                </td>
                <td style="border: 1px solid black; border-right: none;">
                    <label for="example-text-input">Tanggal :</label> <br>
                    <label for="example-text-input">{{ $data->tgl_ttd }}</label>
                </td>
                <td colspan="2" style="border: 1px solid black; border-right: none;">
                    <label for="example-text-input">Paraf Penerima Laporan :</label><br>
                    <label for="example-text-input">{{ $data->penerima }}</label>
                </td>
                <td style="border: 1px solid black; ">
                    <label for="example-text-input">Tanggal :</label><br>
                    <label for="example-text-input">{{ $data->tgl_terima }}</label>
                </td>
                </td>

            </tr>
            <tr style="background: black; color:white;">
                <td colspan="6" style="border: 1px solid black; ">
                    <strong class="head-text">Bagian II :  Analisa Penyebab Timbulnya Masalah /
                        ketidaksesuaian</strong>
                </td>
            </tr>
            <tr>

                <td colspan="6" style="border: 1px solid black;">
                    <textarea name="-" style="height: 60px; font-family: 'Times New Roman', Times, serif;">{{ $data->analisa_sebab }}</textarea>
                    <div style="text-align: right;">
                        {{ $data->analis }} , {{ $data->tgl_analis }}
                    </div>

                </td>
            </tr>
            </tr>
            <tr style="background: black; color:white;">
                <td colspan="6" style="border: 1px solid black; ">
                    <strong class="head-text">Bagian III : Tindakan Perbaikan</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid black; border-right: none;"><label
                        for="example-text-input">Tindakan</label>

                </td>
                <td colspan="1" style="border: 1px solid black; border-right: none;"><label
                        for="example-text-input">Penanggung
                        Jawab</label>

                </td>
                <td colspan="1" style="border: 1px solid black;"><label for="example-text-input">Batas
                        Waktu</label>

                </td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid black; border-right: none;">
                    <textarea name="perbaikan" style="height: 60px; font-family: 'Times New Roman', Times, serif;" > {{ $data->perbaikan }}</textarea>
                </td>
                <td colspan="1" style="border: 1px solid black; border-right: none;">
                    <label> {{ $data->pj_perbaikan }}</label>
                </td>
                <td colspan="1" style="border: 1px solid black;">
                    <label> {{ $data->batas_perbaikan }} </label>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; border-right: none;">
                    <label for="option4">Rencana veririfikasi Perbaikan
                        tanggal:</label>
                </td>
                <td colspan="4" style="border: 1px solid black; ">
                    <label> {{ $data->r_verifikasi_perbaikan }} </label>

                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; border-right: none;">
                    <label for="example-text-input">Paraf Ka.Dept./Unit Kerja :</label> <br>
                    <label value="" id="field1">{{ $data->atasan }} </label>
                </td>
                <td style="border: 1px solid black; border-right: none;">
                    <label for="example-text-input">Tanggal :</label> <br>
                    <label value="" id="field1"> {{ $data->tgl_atasan }} </label>
                </td>
                <td colspan="2" style="border: 1px solid black; border-right: none;">
                    <label for="example-text-input">Paraf PPIC /Penanggung jawab :</label><br>
                    <label value="" id="field1">{{ $data->pic }} </label>
                </td>
                <td style="border: 1px solid black; ">
                    <label for="example-text-input">Tanggal :</label> <br>
                    <label value="" id="field1">{{ $data->tgl_pic }} </label>
                </td>
            </tr>
            <tr style="background: black; color:white;">
                <td colspan="6" style="border: 1px solid black; ">
                    <strong class="head-text">Bagian IV : Tindakan Pencegahan</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid black; ">
                    <label for="example-text-input">Catatan
                        tindak lanjut:</label>
                    <textarea name="pencegahan" style="height: 60px; font-family: 'Times New Roman', Times, serif;">{{ $data->pencegahan }}</textarea>
                </td>
                <td colspan="2" style="border: 1px solid black; ">
                    <label>Status hasil verifikasi:</label>
                    <div>
                        <input type="checkbox" name="hasil_verifikasi" id="efektif" value="Efektif"
                            onclick="toggleCatatan(false)"
                            {{ $data->hasil_verifikasi === 'Efektif' ? 'checked' : '' }}>
                        <label for="efektif">Efektif</label>
                    </div>
                    <div>
                        <input type="checkbox" name="hasil_verifikasi" id="tidakEfektif" value="Tidak Efektif"
                            onclick="toggleCatatan(true)"
                            {{ $data->hasil_verifikasi === 'Tidak Efektif' ? 'checked' : '' }}>
                        <label for="tidakEfektif">Tidak Efektif</label>
                    </div>
                    <div id="catatanTidakEfektif"
                        style="display: {{ $data->hasil_verifikasi === 'Tidak Efektif' ? 'block' : 'none' }}">
                        <label for="catatan">*</label>
                        <label> {{ $data->catatan_te }} </label>
                    </div>
                </td>
            </tr>
       
            <tr>
                <td colspan="3" style="border: 1px solid black; ">
                    <label>Rencana verifikasi Pencegahan tanggal</label>
                </td>
                <td colspan="3" style="border: 1px solid black; ">
                    <label for="">{{ $data->r_verifikasi_cegah }}</label>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; border-right: none;"><label
                        for="example-text-input">Paraf
                        Safety Officer :</label><br><br><br><br>
                    <label for="">{{ $data->so_pic }}</label>
                </td>

                <td colspan="4" style="border: 1px solid black;"><label
                        for="example-text-input">Tanggal</label><br><br><br><br>
                    <label for="">{{ $data->tgl_so }}</label>
                </td>

            </tr>



        </table>


    </div>
 
    @if ($data->file_foto)
    <div class="halaman2" style="margin-top: 10%;">
     <h4 >
         Lampiran Dokumen/ photo ketidak sesuaian:    
    </h4>

        
    <div style="text-align: center;">
        <img src="{{ public_path('/uploads/ktiga/' . $data->file_foto) }}" alt="" style="width: 50%;">
    </div>
    
    @else
        <small>*Tidak ada Lamsmalliran Dokumen / Photo</small>
    @endif


    </div>
</body>
