@extends('hr.dashboard.layout.layout')

@section('content')
    <ul class="nav nav-tabs fw-bold custom-tabs " id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link123 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#data-utama-plane"
                role="tab" aria-controls="data-utama-plane" aria-selected="true">Data Utama</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link123" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data-pribadi-plane"
                role="tab" aria-controls="data-pribadi-plane" aria-selected="false">Data Pribadi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link123" id="contact-tab" data-bs-toggle="tab" data-bs-target="#keluarga-plane"
                role="tab" aria-controls="keluarga-plane" aria-selected="false">Keluarga</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link123" id="contact-tab" data-bs-toggle="tab" data-bs-target="#pendidikan-plane"
                role="tab" aria-controls="pendidikan-plane" aria-selected="false">Pendidikan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link123" id="contact-tab" data-bs-toggle="tab" data-bs-target="#pelatihan-plane"
                role="tab" aria-controls="pelatihan-plane" aria-selected="false">Pelatihan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link123" id="contact-tab" data-bs-toggle="tab"
                data-bs-target="#pengalaman-kerja-plane" role="tab" aria-controls="pengalaman-kerja-plane"
                aria-selected="false">Pengalaman Kerja</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link123" id="contact-tab" data-bs-toggle="tab" data-bs-target="#kontrak-plane"
                role="tab" aria-controls="kontrak-plane" aria-selected="false">Kontrak</button>
        </li>
    </ul>


    <form action="{{ url('/hr/dashboard/pegawai/store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card  card-plain rounded-0 border-primary mt-2 p-1">
        <div class="tab-content  " id="myTabContent">
            <div class="tab-pane fade show active" id="data-utama-plane" role="tabpanel" aria-labelledby="home-tab"
                tabindex="0">
                <div class="container ">
                    <div class="row mt-4 justify-content-center ">
                        <div class="col-md-6 ">
                            <table style="font-size:10pt; " class="table table-sm  text-secondary">
                                <tr>
                                    <td class="fw-bold">NIK</td>
                                    <td>: </td>
                                    <td><input type="text" name="no_payroll" value="{{ $nik_c }}"
                                            class="form-control form-control-sm" readonly></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama</td>
                                    <td>:</td>
                                    <td><input type="text" name="nama_asli" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Panggilan</td>
                                    <td>:</td>
                                    <td><input type="text" name="name" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tgl Masuk</td>
                                    <td>:</td>
                                    <td><input type="date" name="tgl_masuk" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tgl Keluar</td>
                                    <td>:</td>
                                    <td><input type="date" name="tgl_keluar" class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jenis Pegawai</td>
                                    <td>:</td>
                                    <td><select required name="jns_peg" class="form-control form-control-sm"
                                            id="">
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
                                <tr>
                                    <td class="fw-bold">Departemen</td>
                                    <td>:</td>
                                    <td><select name="departemen" class="form-control form-control-sm" id="">
                                            <option selected value=""></option>
                                            @foreach ($dept as $bagd)
                                                <option value="{{ $bagd->departemen }}">{{ $bagd->departemen }}</option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Bagian</td>
                                    <td>:</td>
                                    <td><select class="form-control form-control-sm" name="bagian" id="">
                                            <option selected value=""></option>
                                            @foreach ($bag as $bagb)
                                                <option value="{{ $bagb->bagian }}">{{ $bagb->bagian }}</option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jabatan</td>
                                    <td>:</td>
                                    <td><input type="text" name="jabatan" class="form-control form-control-sm"></td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-6 ">

                            <table style="font-size:10pt; " class="table table-sm text-secondary">

                                <tr>
                                    <td class="fw-bold">Gol</td>
                                    <td>:</td>
                                    <td><select type="text" name="golongan" class="form-control form-control-sm">
                                            <option selected value=""></option>
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
                                    <td class="fw-bold">Transport</td>
                                    <td>:</td>
                                    <td><input type="text" name="transport" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Waktu Kerja</td>
                                    <td>:</td>
                                    <td><select type="text" name="gkcod" class="form-control form-control-sm">
                                        <option value="S00">S00</option>
                                        <option value="S13">S13</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">NPWP</td>
                                    <td>:</td>
                                    <td><input type="text" name="npwp" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email</td>
                                    <td>:</td>
                                    <td><input type="email" name="email" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">BPJS.T.Kerja</td>
                                    <td>:</td>
                                    <td><input type="text" name="bpjs_tk" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">BPJS.T.Kesehatan</td>
                                    <td>:</td>
                                    <td><input type="text" name="bpjs_kes0" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Faskes</td>
                                    <td>:</td>
                                    <td><input type="text" name="faskes" class="form-control form-control-sm"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="data-pribadi-plane" role="tabpanel" aria-labelledby="profile-tab"
                tabindex="0">
                <div class="container">
                    <div class="row mt-4">

                        <div class="col md-6">

                            <table style="font-size:10pt;" class="table table-sm  text-secondary">
                                <tr>
                                    <td class="fw-bold">Alamat</td>
                                    <td>: </td>
                                    <td><input type="text" name="alamat" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tempat Lahir</td>
                                    <td>: </td>
                                    <td><input type="text" name="temp_lahir" class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal</td>
                                    <td>:</td>
                                    <td><input type="date" name="tgl_lahir" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kota</td>
                                    <td>:</td>
                                    <td><input type="text" name="kota" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Telepon</td>
                                    <td>:</td>
                                    <td><input type="text" name="telepon" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Daerah Asal</td>
                                    <td>:</td>
                                    <td><input type="text" name="daerahasal" class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Istri/Suami</td>
                                    <td>:</td>
                                    <td><input type="text" name="suami_istr" class="form-control form-control-sm">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col md-6">
                            <table style="font-size:10pt;" class="table table-sm  text-secondary">
                                <tr>
                                    <td class="fw-bold">Kelamin</td>
                                    <td>:</td>
                                    <td><select name="sex" class="form-control form-control-sm" id="">
                                            <option selected value=""></option>
                                            <option value="PRIA">PRIA</option>
                                            <option value="WANITA">WANITA</option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Gol Darah</td>
                                    <td>:</td>
                                    <td><select name="gol_darah" class="form-control form-control-sm" id="">
                                            <option selected value=""></option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jumlah Anak</td>
                                    <td>:</td>
                                    <td><input type="text" name="jml_anak" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Agama</td>
                                    <td>:</td>
                                    <td><select name="agama" class="form-control form-control-sm" id="">
                                            <option selected value=""></option>
                                            <option value="ISLAM">ISLAM</option>
                                            <option value="NASRANI">NASRANI</option>
                                            <option value="HINDU">HINDU</option>
                                            <option value="BUDHA">BUDHA</option>
                                            <option value="PROTESTAN">PROTESTAN</option>
                                            <option value="KONGHUCHU">KONGHUCHU</option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status</td>
                                    <td>:</td>
                                    <td><select name="sts_nikah" class="form-control form-control-sm" id="">
                                            <option selected value=""></option>
                                            <option value="TK">TK</option>
                                            <option value="K0">K0</option>
                                            <option value="K1">K1</option>
                                            <option value="K2">K2</option>
                                            <option value="K3">K3</option>
                                            <option value="K4">K4</option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Ayah</td>
                                    <td>:</td>
                                    <td><input type="text" name="ayah" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Ibu</td>
                                    <td>:</td>
                                    <td><input type="text" name="ibu" class="form-control form-control-sm"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Foto</td>
                                    <td>:</td>
                                    <td><input type="file" name="foto" class="form-control form-control-sm"></td>
                                </tr>

                            </table>
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
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>Nama</th>
                                    <th>Kelamin</th>
                                    <th>Tgl Lahir</th>
                                    <th>Pendidikan</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-anak-body">
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
                        var count = 0;

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
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
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
                        var count = 0;

                        $('#btn-tambah-pendidikan').on('click', function() {
                            count += 1;

                            $('#tbl-pendidikan-body').append(`
                    <tr>
                        <td hidden>` + count + `</td>             
                        <td hidden>
                            <input class="form-control form-control-sm" value="` + (count - 1) +
                                `" name="id_pendidikan[` + (count - 1) + `]">                       
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
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>Nama Pelatihan</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-pelatihan-body">
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
                        var count = 0;
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
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>Perusahaan</th>
                                    <th>Alamat</th>
                                    <th>Jabatan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-pengalaman-body">
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
                        var count = 0;
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
                            <table class="table table-sm  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                    <th hidden>No.</th>
                                    <th>NO Kontrak</th>
                                    <th>Perpanjang</th>
                                    <th>Berakhir</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="tbl-kontrak-body">
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
                        var count = 0;

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
        </div>
        <div class="col-md-12 text-center btnSave mt-2 ">
            <button type="submit" class="btn btn-primary btn-sm border-success m-0">Simpan</button>
        </div>
    </form>
    </div>

    </div>

    <style>
        .custom-tabs {
            /* Customize the appearance of the tabs container */
            background-color: #f1f1f1;
            border-radius: 0px;
            padding: 0px;
        }

        .custom-tabs .nav-item {
            /* Customize the appearance of each tab item */
            position: relative;
        }

        .custom-tabs .nav-link {
            /* Customize the appearance of the tab links */
            font-weight: bold;
            font-size: 16px;
            padding: 10px 20px;
            background-color: #f1f1f1;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .custom-tabs .nav-link.active,
        .custom-tabs .nav-link:hover {
            /* Customize the appearance of the active and hovered tab links */
            background-color: #d1d1d1;
        }

        .custom-tabs .nav-link::before {
            /* Add the underline effect */
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #000000;
            /* Change the color as per your preference */
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .custom-tabs .nav-link.active::before,
        .custom-tabs .nav-link:hover::before {
            /* Show the underline effect on active and hovered tab links */
            transform: scaleX(1);
        }

        .form-control{
            border-radius: 0;
        }
   
        .card-plain {
        opacity: 0;
        animation-name: fade-in;
        animation-duration: 0.3s;
        animation-fill-mode: forwards;
        animation-timing-function: ease-in-out;
        animation-delay: 0.3s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
@endsection
