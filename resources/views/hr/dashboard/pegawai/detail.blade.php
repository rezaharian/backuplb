@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">

        <div class="col-md-4 mb-1">
            <a href="{{ route('datapegawai.delete', $data->id) }}" onclick="return confirm('Apakah Anda Yakin ?');"
                class="btn btn-md btn-danger m-0"><i class="fas fa-trash"></i></a>

            <a href="{{ route('datapegawai.edit', $data->id) }}" class="btn btn-md btn-primary m-0">
                <i class="fas fa-pen"></i></a>
            <a target="_blank" href="{{ route('datapegawai.print', $data->id) }}" class="btn btn-md btn-primary m-0">
                <i class="fas fa-print"></i></a>
        </div>
        @if ($data->tgl_keluar == null)
            <div class="col-md-6">
            </div>
        @else
            <div class="col-md-4">
            </div>
            <div class="col-md-4 bg-danger text-center text-light fw-bold ">
                <div>
                    <h2>SUDAH KELUAR</h2>
                </div>
            </div>
        @endif

        <div class="col-md-4">
        </div>
    </div>
    <ul class="nav nav-tabs fw-bold" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#data-utama-plane"
                type="button" role="tab" aria-controls="data-utama-plane" aria-selected="true">Data Utama</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data-pribadi-plane"
                type="button" role="tab" aria-controls="data-pribadi-plane" aria-selected="false">Data
                Pribadi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#keluarga-plane" type="button"
                role="tab" aria-controls="keluarga-plane" aria-selected="false">Keluarga</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#pendidikan-plane" type="button"
                role="tab" aria-controls="pendidikan-plane" aria-selected="false">Pendidikan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#pelatihan-plane" type="button"
                role="tab" aria-controls="pelatihan-plane" aria-selected="false">Pelatihan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#pengalaman-kerja-plane"
                type="button" role="tab" aria-controls="pengalaman-kerja-plane" aria-selected="false">Pengalaman
                Kerja</button>
        </li>

        @if ($data->jns_peg == 'TETAP')
            <li class="nav-item" role="presentation" @disabled(true)>
                <button disabled class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#kontrak-plane"
                    type="button" role="tab" aria-controls="kontrak-plane" aria-selected="false">Kontrak</button>
            </li>
        @else
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#kontrak-plane"
                    type="button" role="tab" aria-controls="kontrak-plane" aria-selected="false">Kontrak</button>
            </li>
        @endif

    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="data-utama-plane" role="tabpanel" aria-labelledby="home-tab"
            tabindex="0">
            <div class="text-capitalize">
                <div class="card rounded-0 px-2 border-primary mt-2">

                    <div class="row mt-4">
                        <div class="col md-4">
                            <table style="font-size:10pt;" class="table table-sm">
                                <tr>
                                    <td><strong>NIK</strong></td>
                                    <td>: </td>
                                    <td> {{ $data->no_payroll }} </td>
                                </tr>
                                <tr>
                                    <td><strong>Nama</strong></strong></td>
                                    <td>:</td>
                                    <td>{{ $data->nama_asli }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Panggilan</strong></strong></td>
                                    <td>:</td>
                                    <td>{{ $data->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tgl Masuk</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->tgl_masuk }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tgl Keluar</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->tgl_keluar }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Karyawan</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->jns_peg }}</td>
                                </tr>

                            </table>
                        </div>
                        <div class="col md-4">
                            <table style="font-size:10pt;" class="table table-sm">
                                <tr>
                                    <td><strong>Departemen</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->departemen }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bagian</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->bagian }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jabatan</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->jabatan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gol</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->golongan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transport</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->transport }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Kerja</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->gkcod }}</td>
                                </tr>

                            </table>
                        </div>
                        <div class="col md-4">
                            <table style="font-size:10pt;" class="table table-sm">
                                <tr>
                                    <td><strong>NPWP</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->npwp }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>BPJS.T.Kerja</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->bpjs_tk }}</td>
                                </tr>
                                <tr>
                                    <td><strong>BPJS.T.Kesehatan</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->bpjs_kes0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Faskes</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->faskes }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane fade" id="data-pribadi-plane" role="tabpanel" aria-labelledby="profile-tab"
            tabindex="0">
            <div class="container">
                <div class="card rounded-0 px-2 border-primary mt-2">
                    <div class="row mt-4">

                        <div class="col  md-3">
                            <table style="font-size:10pt;" class="table table-sm">
                                <tr>
                                    <td><strong>Tempat Lahir</td>
                                    <td>: </td>
                                    <td> {{ $data->temp_lahir }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</td>
                                    <td>: </td>
                                    <td> {{ $data->alamat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->tgl_lahir }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kota</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->kota }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->telepon }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Daerah Asal</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->daerahasal }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Istri/Suami</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->suami_istr }}</td>
                                </tr>
                       
                            </table>
                        </div>
                        <div class="col  md-3">
                            <table style="font-size:10pt;" class="table table-sm">
                             
                                <tr>
                                    <td><strong>Kelamin</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->sex }}</td>
                                </tr>
                                   <tr>
                                    <td><strong>Gol Darah</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->gol_darah }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Anak</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->jml_anak }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Agama</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->agama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->sts_nikah }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Ayah</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->ayah }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Ibu</strong></td>
                                    <td>:</td>
                                    <td>{{ $data->ibu }}</td>
                                </tr>

                            </table>
                        </div>
                        <div class="col  md-3">
                            <table style="font-size:10pt;" class="table table-sm">
                                <tr>
                                    <td><b>Foto</b></td>
                                    <td>:</td>
                                    <td>
                                        <div class="col-md-6">
                                            <a href="/image/fotos/{{ $data->foto }}" width="100%" target="new">
                                                <img src="/image/fotos/{{ $data->foto }}"
                                                    alt="/image/fotos/{{ $data->foto }}" width="100%"></a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="keluarga-plane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
            <div class="container">
                <div class="card rounded-0 px-4 border-primary mt-2">
                    <div class="row">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kelamin</th>
                                    <th scope="col">TGL Lahir</th>
                                    <th scope="col">Pendidikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_keluarga as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->kelamin }}</td>
                                        <td>{{ $item->tgl_lahir }}</td>
                                        <td>{{ $item->pendidikan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pendidikan-plane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
            <div class="container">
                <div class="card rounded-0 px-4 border-primary mt-2">
                <div class="row">
                    <table class="table table-sm">
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
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pelatihan-plane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
            <div class="container">
                <div class="card rounded-0 px-4 border-primary mt-2">

                <div class="row">
                    <table class="table table-sm">
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
            </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pengalaman-kerja-plane" role="tabpanel" aria-labelledby="contact-tab"
            tabindex="0">
            <div class="container">
                <div class="card rounded-0 px-4 border-primary mt-2">

                <div class="row">
                    <table class="table table-sm">
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
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="kontrak-plane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
            <div class="container">
                <div class="card rounded-0 px-4 border-primary mt-2">

                <div class="row">
                    <table class="table table-sm">
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
            </div>
            </div>
        </div>
    </div>
    <style>
        table{
            font-size: 10pt;
            
        }
    </style>
@endsection
