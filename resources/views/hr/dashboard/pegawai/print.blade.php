<style>
    hr {
        width: 100%;
        height: 2px;
        border: none;
        background-color: black;
        margin: 2px auto;
    }

    body {
        font-family: monospace;
        font-size: 8pt;
        text-transform: uppercase;

    }

    .column {
        float: left;
        width: 50%;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>
<title>Data Diri</title>

<body>
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:20%;">
<div style="text-align: center">

    <strong>Data Lengkap Karyawan PT Extrupack <br> Atas Nama : {{ $data->nama_asli }}</strong>
</div>

<div class="row" style="margin-top: 4%">
    <hr>
    <div class="column">
        <table style="font-size:8;">
                <tr>
                    <td>NIK</td>
                    <td>: </td>
                    <td> {{ $data->no_payroll }} </td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $data->nama_asli }}</td>
                </tr>
                <tr>
                    <td>Panggilan</td>
                    <td>:</td>
                    <td>{{ $data->name }}</td>
                </tr>
                <tr>
                    <td>Tgl Masuk</td>
                    <td>:</td>
                    <td>{{ $data->tgl_masuk }}</td>
                </tr>
                <tr>
                    <td>Tgl Keluar</td>
                    <td>:</td>
                    <td>{{ $data->tgl_keluar }}</td>
                </tr>
                <tr>
                    <td>Karyawan</td>
                    <td>:</td>
                    <td>{{ $data->jns_peg }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>:</td>
                    <td>{{ $data->departemen }}</td>
                </tr>
                <tr>
                    <td>Bagian</td>
                    <td>:</td>
                    <td>{{ $data->bagian }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $data->jabatan }}</td>
                </tr>
                <tr>
                    <td>Gol</td>
                    <td>:</td>
                    <td>{{ $data->golongan }}</td>
                </tr>
                <tr>
                    <td>Transport</td>
                    <td>:</td>
                    <td>{{ $data->transport }}</td>
                </tr>
                <tr>
                    <td>Waktu Kerja</td>
                    <td>:</td>
                    <td>{{ $data->gkcod }}</td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:</td>
                    <td>{{ $data->npwp }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $data->email }}</td>
                </tr>
                <tr>
                    <td>BPJS.T.Kerja</td>
                    <td>:</td>
                    <td>{{ $data->bpjs_tk }}</td>
                </tr>
         
            </table>
        </div>
            <div class="column">
                <table style="font-size:8;">
                    <tr>
                        <td>BPJS.T.Kesehatan</td>
                        <td>:</td>
                        <td>{{ $data->bpjs_kes0 }}</td>
                    </tr>
                    <tr>
                        <td>Faskes</td>
                        <td>:</td>
                        <td>{{ $data->faskes }}</td>
                    </tr>
                    <tr>
                        <td>Tempat Lahir</td>
                        <td>: </td>
                        <td> {{ $data->temp_lahir }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $data->tgl_lahir }}</td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td>:</td>
                        <td>{{ $data->kota }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>:</td>
                        <td>{{ $data->telepon }}</td>
                    </tr>
                    <tr>
                        <td>Daerah Asal</td>
                        <td>:</td>
                        <td>{{ $data->daerahasal }}</td>
                    </tr>
                    <tr>
                        <td>Istri/Suami</td>
                        <td>:</td>
                        <td>{{ $data->suami_istr }}</td>
                    </tr>
                    <tr>
                        <td>Kelamin</td>
                        <td>:</td>
                        <td>{{ $data->sex }}</td>
                    </tr>
                    <tr>
                        <td>Gol Darah</td>
                        <td>:</td>
                        <td>{{ $data->gol_darah }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Anak</td>
                        <td>:</td>
                        <td>{{ $data->jml_anak }}</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td>{{ $data->agama }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>{{ $data->sts_nikah }}</td>
                    </tr>
                    <tr>
                        <td>Nama Ayah</td>
                        <td>:</td>
                        <td>{{ $data->ayah }}</td>
                    </tr>
                    <tr>
                        <td>Nama Ibu</td>
                        <td>:</td>
                        <td>{{ $data->ibu }}</td>
                    </tr>
                </table>
            </div>
    </div>
    <div style="background-color: rgb(243, 243, 243); margin-top:3%;" >
        <b style=""> Keluarga :</b>
        <hr>
        <table>
            <thead>
                <tr>
                    <th >No</th>
                    <th >Nama</th>
                    <th >Kelamin</th>
                    <th >TGL Lahir</th>
                    <th >Pendidikan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_keluarga as $item)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kelamin }}</td>
                        <td style=" text-align:center;">{{ $item->tgl_lahir }}</td>
                        <td style=" text-align:center;">{{ $item->pendidikan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div style="background-color: rgb(243, 243, 243); margin-top:3%;" >
        <b style=""> Pendidikan :</b>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Tingkat</th>
                    <th scope="col">Sekolah</th>
                    <th scope="col">Tempat</th>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Tahun Ijazah</th>
                    <th scope="col">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_pendidikan as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->tingkat }}</td>
                        <td>{{ $item->sekolah }}</td>
                        <td>{{ $item->tempat }}</td>
                        <td>{{ $item->jurusan }}</td>
                        <td>{{ $item->tahun_izs }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="background-color: rgb(243, 243, 243); margin-top:3%;" >
        <b style=""> Pelatihan :</b>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Pelatihan</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_pelatihan as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->course_nam }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="background-color: rgb(243, 243, 243); margin-top:3%;" >
        <b style=""> Pengalaman Kerja :</b>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Perusahaan</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">jabatan</th>
                    <th scope="col">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_exp as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->perusahaan }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="background-color: rgb(243, 243, 243); margin-top:3%;" >
        <b style=""> Kontrak Kerja :</b>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">No Kontrak</th>
                    <th scope="col">Perpanjang</th>
                    <th scope="col">Berakhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_kontrak as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->no_kontrak }}</td>
                        <td>{{ $item->Perpanjang }}</td>
                        <td>{{ $item->berakhir }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</body>
