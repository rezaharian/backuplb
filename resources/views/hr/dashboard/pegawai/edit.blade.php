@extends('hr.dashboard.layout.layout')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="alert alert-info text-center">
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif
    <ul class="nav nav-tabs fw-bold" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#data-utama-plane"
                type="button" role="tab" aria-controls="data-utama-plane" aria-selected="true">Data Utama</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data-pribadi-plane"
                type="button" role="tab" aria-controls="data-pribadi-plane" aria-selected="false">Data Pribadi</button>
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
        @if ($pegawai->jns_peg == 'TETAP')
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
    <form class=" px-2  " action="{{ url('/hr/dashboard/pegawai/update', $pegawai->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="data-utama-plane" role="tabpanel" aria-labelledby="home-tab"
                tabindex="0">
                <div class="container">
                    <div class="card rounded-0 px-2 border-primary mt-2">
                        <div class="row mt-2 bg- ">
                            <div class="col md-4">
                                <table style="font-size:10pt;" class="table table-sm  fw-bold text-secondary">
                                    <tr>
                                        <td>NIK</td>
                                        <td>: </td>
                                        <td><input type="text" name="no_payroll" value="{{ $pegawai->no_payroll }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td><input type="text" name="nama_asli" value="{{ $pegawai->nama_asli }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Panggilan</td>
                                        <td>:</td>
                                        <td><input type="text" name="name" value="{{ $pegawai->name }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Tgl Masuk</td>
                                        <td>:</td>
                                        <td><input type="date" name="tgl_masuk" value="{{ $pegawai->tgl_masuk }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Tgl Keluar</td>
                                        <td>:</td>
                                        <td><input type="date" name="tgl_keluar" value="{{ $pegawai->tgl_keluar }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Pegawai</td>
                                        <td>:</td>
                                        <td><select name="jns_peg"  class="form-control form-control-sm"
                                                id="" >
                                                <option selected value="{{ $pegawai->jns_peg }}">{{ $pegawai->jns_peg }}
                                                </option>
                                                <option value="TRAINING">TRAINING</option>
                                                <option value="KONTRAK">KONTRAK</option>
                                                <option value="TETAP">TETAP</option>
                                                <option value="BULANAN">BULANAN</option>
                                                <option value="PENSIUN">PENSIUN</option>
                                                <option value="HARIAN">HARIAN</option>
                                                <option value="SATPAM">SATPAM</option>
                                                <option value="KEBERSIHAN">KEBERSIHAN</option>
                                            </select></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col md-4">
                                <table style="font-size:10pt;" class="table table-sm fw-bold text-secondary">
                                    <tr>
                                        <td>Departemen</td>
                                        <td>:</td>
                                        <td><select name="departemen" class="form-control form-control-sm"
                                                id="">
                                                <option selected value="{{ $pegawai->departemen }}">
                                                    {{ $pegawai->departemen }}</option>
                                                @foreach ($dept as $bagd)
                                                    <option value="{{ $bagd->departemen }}">{{ $bagd->departemen }}
                                                    </option>
                                                @endforeach
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Bagian</td>
                                        <td>:</td>
                                        <td><select class="form-control form-control-sm" name="bagian" id="">
                                                <option selected value="{{ $pegawai->bagian }}">{{ $pegawai->bagian }}
                                                </option>
                                                @foreach ($bag as $bagb)
                                                    <option value="{{ $bagb->bagian }}">{{ $bagb->bagian }}</option>
                                                @endforeach
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <td><input type="text" name="jabatan" value="{{ $pegawai->jabatan }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Gol</td>
                                        <td>:</td>
                                        <td>
                                            <select type="text" name="golongan" class="form-control form-control-sm">
                                                <option selected value="{{ $pegawai->golongan }}">
                                                    {{ $pegawai->golongan }}</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C1">C1</option>
                                                <option value="C2">C2</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                                <option value="F">F</option>
                                                <option value="G1">G1</option>
                                                <option value="G2">G2</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Transport</td>
                                        <td>:</td>
                                        <td><input type="text" name="transport" value="{{ $pegawai->transport }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Waktu Kerja</td>
                                        <td>:</td>
                                        <td><select type="text" name="gkcod" 
                                                class="form-control form-control-sm">
                                                <option selected value="{{ $pegawai->gkcod }}">{{ $pegawai->gkcod }}</option>
                                                <option value="S00">S00</option>
                                                <option value="S13">S13</option>
                                            </select></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col md-4">
                                <table style="font-size:10pt;" class="table table-sm fw-bold text-secondary">
                                    <tr>
                                        <td>NPWP</td>
                                        <td>:</td>
                                        <td><input type="text" name="npwp" value="{{ $pegawai->npwp }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td><input type="email" name="email" value="{{ $pegawai->email }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>BPJS.T.Kerja</td>
                                        <td>:</td>
                                        <td><input type="text" name="bpjs_tk" value="{{ $pegawai->bpjs_tk }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>BPJS.T.Kesehatan</td>
                                        <td>:</td>
                                        <td><input type="text" name="bpjs_kes0" value="{{ $pegawai->bpjs_kes0 }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Faskes</td>
                                        <td>:</td>
                                        <td><input type="text" name="faskes" value="{{ $pegawai->faskes }}"
                                                class="form-control form-control-sm"></td>
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

                        <div class="row mt-2">

                            <div class="col md-4">

                                <table style="font-size:10pt;" class="table table-sm  fw-bold text-secondary">
                                    <tr>
                                        <td>Alamat</td>
                                        <td>: </td>
                                        <td><input type="text" name="alamat" value="{{ $pegawai->alamat }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Tempat Lahir</td>
                                        <td>: </td>
                                        <td><input type="text" name="temp_lahir" value="{{ $pegawai->temp_lahir }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td>:</td>
                                        <td><input type="date" name="tgl_lahir" value="{{ $pegawai->tgl_lahir }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Kota</td>
                                        <td>:</td>
                                        <td><input type="text" name="kota" value="{{ $pegawai->kota }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Telepon</td>
                                        <td>:</td>
                                        <td><input type="text" name="telepon" value="{{ $pegawai->telepon }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Daerah Asal</td>
                                        <td>:</td>
                                        <td><input type="text" name="daerahasal" value="{{ $pegawai->daerahasal }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Istri/Suami</td>
                                        <td>:</td>
                                        <td><input type="text" name="suami_istr" value="{{ $pegawai->suami_istr }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col md-4">
                                <table style="font-size:10pt;" class="table table-sm  fw-bold text-secondary">


                                    <tr>
                                        <td>Kelamin</td>
                                        <td>:</td>
                                        <td><select name="sex" class="form-control form-control-sm" id="">
                                                <option selected value="{{ $pegawai->sex }}">{{ $pegawai->sex }}</option>
                                                <option value="PRIA">PRIA</option>
                                                <option value="WANITA">WANITA</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Gol Darah</td>
                                        <td>:</td>
                                        <td><select name="gol_darah" class="form-control form-control-sm" id="">
                                                <option selected value="{{ $pegawai->gol_darah }}">
                                                    {{ $pegawai->gol_darah }}
                                                </option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="O">O</option>
                                                <option value="AB">AB</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Anak</td>
                                        <td>:</td>
                                        <td><input type="text" name="jml_anak" value="{{ $pegawai->jml_anak }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Agama</td>
                                        <td>:</td>
                                        <td><select name="agama" class="form-control form-control-sm" id="">
                                                <option selected value="{{ $pegawai->agama }}">{{ $pegawai->agama }}
                                                </option>
                                                <option value="ISLAM">ISLAM</option>
                                                <option value="NASRANI">NASRANI</option>
                                                <option value="HINDU">HINDU</option>
                                                <option value="BUDHA">BUDHA</option>
                                                <option value="PROTESTAN">PROTESTAN</option>
                                                <option value="KONGHUCHU">KONGHUCHU</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td><select name="sts_nikah" class="form-control form-control-sm" id="">
                                                <option selected value="{{ $pegawai->agama }}">{{ $pegawai->sts_nikah }}
                                                </option>
                                                <option value="TK">TK</option>
                                                <option value="K0">K0</option>
                                                <option value="K1">K1</option>
                                                <option value="K2">K2</option>
                                                <option value="K3">K3</option>
                                                <option value="K4">K4</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Ayah</td>
                                        <td>:</td>
                                        <td><input type="text" name="ayah" value="{{ $pegawai->ayah }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Ibu</td>
                                        <td>:</td>
                                        <td><input type="text" name="ibu" value="{{ $pegawai->ibu }}"
                                                class="form-control form-control-sm"></td>
                                    </tr>

                                </table>
                            </div>

                            <div class="col-md-2">
                                <tr>
                                    <td>Foto</td>
                                    <td>:</td>
                                    <td><input type="file" name="foto" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="/image/fotos/{{ $pegawai->foto }}" width="100%" target="new">
                                            <img src="/image/fotos/{{ $pegawai->foto }}"
                                                alt="/image/fotos/{{ $pegawai->foto }}" width="100%"></a>
                                        </a>
                                    </td>
                                </tr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- KELUARGA --}}
            <div class="tab-pane fade" id="keluarga-plane" role="tabpanel" aria-labelledby="contact-tab"
                tabindex="0">
                <div class="row">

                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-primary btn-sm my-1" id="btn-tambah">TAMBAH </button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  "
                                style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>Nama</th>
                                    <th>Kelamin</th>
                                    <th>Tgl Lahir</th>
                                    <th>Pendidikan</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-anak-body">
                                    @php $i=0; @endphp
                                    @forelse ($keluarga as $item)
                                        <tr>
                                            <td hidden>{{ $i++ }}</td>
                                            <input class="form-control form-control-sm" type="hidden"
                                                name="item_id_keluarga[]" value="{{ $item->id }}" id=""
                                                readonly>
                                            <td><input class="form-control form-control-sm" type="text" name="nama[]"
                                                    value="{{ $item->nama }}" id=""></td>
                                            <td><select class="form-control form-control-sm" type="text"
                                                    name="kelamin[]" value="" id="">
                                                    <option value="{{ $item->kelamin }}">{{ $item->kelamin }}</option>
                                                    <option value="PRIA">PRIA</option>
                                                    <option value="WANITA">WANITA</option>
                                                </select></td>
                                            <td><input class="form-control form-control-sm" type="date"
                                                    name="tgl_lahir_anak[]" value="{{ $item->tgl_lahir }}"
                                                    id=""></td>
                                            <td><select class="form-control form-control-sm" type="text"
                                                    name="pendidikan[]" value="" id="">
                                                    <option selected value="{{ $item->pendidikan }}">
                                                        {{ $item->pendidikan }}</option>
                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA">SMA</option>
                                                    <option value="SMK">SMK</option>
                                                    <option value="D1">D1</option>
                                                    <option value="D2">D2</option>
                                                    <option value="D3">D3</option>
                                                    <option value="S1">S1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="S3">S3</option>
                                                    <option value="S4">S4</option>
                                                    <option value="S5">S5</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-md m-0"
                                                    href="{{ route('datapegawai.delete_d_keluarga', $item->id) }}"><i
                                                        class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>

                                    @empty
                                        <p>belum ada data</p>
                                    @endforelse


                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>
                <div class="row px-4">
                    <div class="col-md-8">

                    </div>

                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
                    integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
                </script>
                <link rel="stylesheet" type="text/css"
                    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
                <script type="text/javascript"></script>

                <script>
                    $(function() {
                        var count = {{ $jmlh_keluarga }};

                        $('#btn-tambah').on('click', function() {
                            count += 1;
                            $('#tbl-anak-body').append(`
                        <tr>
                        <td hidden>` + count + `</td>             
                        <td>
                            <input class="form-control form-control-sm" name="nama[` + (count - 1) + `]"  >
                        </td>
                        <td>
                            <select type="text" name="kelamin[` + (count - 1) + `]" class="form-control form-control-sm" >
                                    <option value="PRIA">PRIA</option>    
                                    <option value="WANITA">WANITA</option>    
                         </select>
                        </td>
                        <td>
                            <input type="date" name="tgl_lahir_anak[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <select type="text" name="pendidikan[` + (count - 1) + `]" class="form-control form-control-sm" >
                                    <option value="SD">SD</option>    
                                    <option value="SMP">SMP</option>    
                                    <option value="SMA">SMA</option>    
                                    <option value="SMK">SMK</option>    
                                    <option value="D1">D1</option>    
                                    <option value="D2">D2</option>    
                                    <option value="D3">D3</option>    
                                    <option value="S1">S1</option>    
                                    <option value="S2">S2</option>    
                                    <option value="S3">S3</option>    
                                    <option value="S4">S4</option>    
                                    <option value="S5">S5</option>    
                         </select>
                        </td>
                        <td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
                    </tr>
                    `);


                            $('.removeItem').on('click', function() {
                                $(this).closest("tr").remove();
                                count -= 1;

                            })
                        })
                    })
                </script>
            </div>
            {{-- PENDIDIKAN --}}
            <div class="tab-pane fade" id="pendidikan-plane" role="tabpanel" aria-labelledby="contact-tab"
                tabindex="0">
                <div class="row">

                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-primary btn-sm my-1" id="btn-tambah-pendidikan">TAMBAH
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  "
                                style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>Tingkat</th>
                                    <th>Sekolah</th>
                                    <th>Tempat</th>
                                    <th>Jurusan</th>
                                    <th>Tahun Ijazah</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-pendidikan-body">
                                    @php $i=0; @endphp
                                    @forelse ($pendidikan as $item)
                                        <tr>
                                            <td hidden>{{ $i++ }}</td>
                                            <td hidden>
                                                <input class="form-control form-control-sm" type=""
                                                    name="item_id_pendidikan[]" value="{{ $item->id }}"
                                                    id="" readonly>
                                            </td>
                                            <td hidden>
                                                <input class="form-control form-control-sm" type=""
                                                    name="id_pdd[]" value="{{ $id_pdd }}[{{ $i }}]"
                                                    id="">
                                            </td>

                                            <td><select class="form-control form-control-sm" type="text"
                                                    name="tingkat[]" value="{{ $item->tingkat }}" id="">
                                                    <option selected value="{{ $item->tingkat }}">{{ $item->tingkat }}
                                                    </option>
                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA">SMA</option>
                                                    <option value="SMK">SMK</option>
                                                    <option value="D1">D1</option>
                                                    <option value="D2">D2</option>
                                                    <option value="D3">D3</option>
                                                    <option value="S1">S1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="S3">S3</option>
                                                    <option value="S4">S4</option>
                                                    <option value="S5">S5</option>
                                                </select></td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="sekolah[]" value="{{ $item->sekolah }}" id=""></td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="tempat[]" value="{{ $item->tempat }}" id=""></td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="jurusan[]" value="{{ $item->jurusan }}" id=""></td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="tahun_izs[]" value="{{ $item->tahun_izs }}" id="">
                                            </td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="keterangan_pendidikan[]" value="{{ $item->keterangan }}"
                                                    id=""></td>
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-md m-0"
                                                    href="{{ route('datapegawai.delete_d_pendidikan', $item->id) }}"><i
                                                        class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>

                                    @empty
                                        <p>belum ada data</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row px-4">
                    <div class="col-md-8">
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
                    integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
                </script>
                <link rel="stylesheet" type="text/css"
                    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
                <script type="text/javascript"></script>

                <script>
                    $(function() {
                        var count = {{ $jmlh_pendidikan }};
                        // var p = {{ $id_pdd }};

                        $('#btn-tambah-pendidikan').on('click', function() {
                            count += 2;

                            $('#tbl-pendidikan-body').append(`
                    <tr>
                        <td hidden>` + count + `</td>             
                        <td hidden >
                            <input class="form-control form-control-sm" value="{{ $id_pdd }}[` + (count - 1) +
                                `]"  name="id_pdd[` + (count - 1) + `]">                       
                        </td>
                     
                        <td>
                            <select class="form-control form-control-sm" name="tingkat[` + (count - 1) + `]">  
                                <option value="SD">SD</option>    
                                    <option value="SMP">SMP</option>    
                                    <option value="SMA">SMA</option>    
                                    <option value="SMK">SMK</option>    
                                    <option value="D1">D1</option>    
                                    <option value="D2">D2</option>    
                                    <option value="D3">D3</option>    
                                    <option value="S1">S1</option>    
                                    <option value="S2">S2</option>    
                                    <option value="S3">S3</option>    
                                    <option value="S4">S4</option>    
                                    <option value="S5">S5</option>    
                         </select>                     
                        </td>
                        <td>
                            <input type="text" name="sekolah[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="text" name="tempat[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="text" name="jurusan[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="text" name="tahun_izs[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="text" name="keterangan_pendidikan[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
                    </tr>
                    `);

                            $('.removeItem').on('click', function() {
                                $(this).closest("tr").remove();
                                count -= 1;
                            })

                        })

                    })
                </script>
            </div>
            {{-- PELATIHAN --}}
            <div class="tab-pane fade" id="pelatihan-plane" role="tabpanel" aria-labelledby="contact-tab"
                tabindex="0">
                <div class="row">

                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-primary btn-sm my-1" id="btn-tambah-pelatihan">TAMBAH
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  "
                                style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>Nama Pelatihan</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-pelatihan-body">
                                    @php $i=0; @endphp
                                    @forelse ($pelatihan as $item)
                                        <tr>
                                            <td hidden>{{ $i++ }}</td>
                                            <input class="form-control form-control-sm" type="hidden"
                                                name="item_id_pelatihan[]" value="{{ $item->id }}" id=""
                                                readonly>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="course_nam[]" value="{{ $item->course_nam }}" id="">
                                            </td>
                                            <td><input class="form-control form-control-sm" type="date"
                                                    name="tanggal_course[]" value="{{ $item->tanggal }}" id="">
                                            </td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="keterangan_course[]" value="{{ $item->keterangan }}"
                                                    id=""></td>
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-md m-0"
                                                    href="{{ route('datapegawai.delete_d_pelatihan', $item->id) }}"><i
                                                        class="fas fa-trash"></i></a>

                                            </td>
                                        </tr>

                                    @empty
                                        <p>belum ada data</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row px-4">
                    <div class="col-md-8">
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
                    integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
                </script>
                <link rel="stylesheet" type="text/css"
                    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
                <script type="text/javascript"></script>

                <script>
                    $(function() {
                        var count = {{ $jmlh_pelatihan }};
                        $('#btn-tambah-pelatihan').on('click', function() {
                            count += 1;
                            $('#tbl-pelatihan-body').append(`
                    <tr>
                        <td hidden>` + count + `</td>             
                        <td hidden>
                            <input class="form-control form-control-sm" name="id_pelatihan[` + (count - 1) + `]">
                        </td>
                        <td>
                            <input class="form-control form-control-sm" name="course_nam[` + (count - 1) + `]">
                        </td>
                        <td>
                            <input type="date" name="tanggal_course[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="text" name="keterangan_course[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
                    </tr>
                    `);

                            $('.removeItem').on('click', function() {
                                $(this).closest("tr").remove();
                                count -= 1;
                            })

                        })

                    })
                </script>

            </div>
            {{-- PENGALAMAN --}}
            <div class="tab-pane fade" id="pengalaman-kerja-plane" role="tabpanel" aria-labelledby="contact-tab"
                tabindex="0">
                <div class="row">

                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-primary btn-sm my-1" id="btn-tambah-pengalaman">TAMBAH
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  "
                                style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>Perusahaan</th>
                                    <th>Alamat</th>
                                    <th>Jabatan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-pengalaman-body">
                                    @php $i=0; @endphp
                                    @forelse ($exp as $item)
                                        <tr>
                                            <td hidden>{{ $i++ }}</td>
                                            <input class="form-control form-control-sm" type="hidden"
                                                name="item_id_exp[]" value="{{ $item->id }}" id=""
                                                readonly>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="perusahaan[]" value="{{ $item->perusahaan }}" id="">
                                            </td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="alamat_exp[]" value="{{ $item->alamat }}" id=""></td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="jabatan_exp[]" value="{{ $item->jabatan }}" id="">
                                            </td>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="keterangan_exp[]" value="{{ $item->keterangan }}"
                                                    id=""></td>
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-md m-0"
                                                    href="{{ route('datapegawai.delete_d_exp', $item->id) }}"><i
                                                        class="fas fa-trash"></i></a>

                                            </td>
                                        </tr>

                                    @empty
                                        <p>belum ada data</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row px-4">
                    <div class="col-md-8">
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
                    integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
                </script>
                <link rel="stylesheet" type="text/css"
                    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
                <script type="text/javascript"></script>

                <script>
                    $(function() {
                        var count = {{ $jmlh_exp }};
                        $('#btn-tambah-pengalaman').on('click', function() {

                            count += 1;

                            $('#tbl-pengalaman-body').append(`
                    <tr>
                        <td hidden>` + count + `</td>    
                        <td hidden>
                            <input class="form-control form-control-sm" name="id_exp[` + (count - 1) + `]">
                        </td>         
                        <td>
                            <input class="form-control form-control-sm" name="perusahaan[` + (count - 1) + `]">
                        </td>
                        <td>
                            <input type="text" name="alamat_exp[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="text" name="jabatan_exp[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="text" name="keterangan_exp[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                  
                        <td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
                    </tr>
                    `);

                            $('.removeItem').on('click', function() {
                                $(this).closest("tr").remove();
                                count -= 1;
                            })


                        })

                    })
                </script>

            </div>
            {{-- KONTRAK --}}
            <div class="tab-pane fade" id="kontrak-plane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <div class="row">

                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-primary btn-sm my-1" id="btn-tambah-kontrak">TAMBAH
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  "
                                style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>NO Kontrak</th>
                                    <th>Perpanjang</th>
                                    <th>Berakhir</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-kontrak-body">
                                    @php $i=0; @endphp
                                    @forelse ($kontrak as $item)
                                        <tr>
                                            <td hidden>{{ $i++ }}</td>
                                            <input class="form-control form-control-sm" type="hidden"
                                                name="item_id_kontrak[]" value="{{ $item->id }}" id=""
                                                readonly>
                                            <td><input class="form-control form-control-sm" type="text"
                                                    name="no_kontrak[]" value="{{ $item->no_kontrak }}" id="">
                                            </td>
                                            <td><input class="form-control form-control-sm" type="date"
                                                    name="perpanjang[]" value="{{ $item->Perpanjang }}" id="">
                                            </td>
                                            <td><input class="form-control form-control-sm" type="date"
                                                    name="berakhir[]" value="{{ $item->berakhir }}" id=""></td>
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-md m-0"
                                                    href="{{ route('datapegawai.delete_d_kontrak', $item->id) }}"><i
                                                        class="fas fa-trash"></i></a>

                                            </td>
                                        </tr>

                                    @empty
                                        <p>belum ada data</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row px-4">
                    <div class="col-md-8">
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
                    integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
                </script>
                <link rel="stylesheet" type="text/css"
                    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
                <script type="text/javascript"></script>

                <script>
                    $(function() {
                        var count = {{ $jmlh_kontrak }};

                        $('#btn-tambah-kontrak').on('click', function() {
                            count += 1;

                            $('#tbl-kontrak-body').append(`
                    <tr>
                        <td hidden>` + count + `</td>             
                        <td>
                            <input class="form-control form-control-sm" name="no_kontrak[` + (count - 1) + `]" >
                        </td>
                        <td>
                            <input type="date" name="perpanjang[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td>
                            <input type="date" name="berakhir[` + (count - 1) + `]" class="form-control form-control-sm" >
                        </td>
                        <td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
                    </tr>
                    `);

                            $('.removeItem').on('click', function() {
                                $(this).closest("tr").remove();
                                count -= 1;
                            })

                        })
                    })
                </script>
            </div>
        </div>
        </div>
    
        <button type="submit" class="btn btn-primary btn-md position-relative top-50 start-50 mt-3"><i
                class="fa-solid fa-save"></i> SIMPAN</button>
                
    </form>
 
    <style>
        .form-control {
            border-radius: 0;
        }
    </style>
@endsection
