@extends('hr.dashboard.layout.layout')

@section('content')

    <div class="container  px-4">


        <form action="{{ url('/hr/dashboard/training/tr/store') }}" method="POST">
            @csrf
            <div class="row">
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
                    <div class="alert alert-success text-center">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
                <div class="col-md-4">

                    <table class="table">

                        <tr>
                            <td><label for="example-text-input" class="form-control-label">NO Pelatihan  </label></td>
                            <td><input class="form-control" type="text" name="train_cod" value="{{ $no }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tempat  </label></td>
                            <td><input class="form-control" type="text" name="tempat" value="" required ></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Pemateri  </label></td>
                            <td><input class="form-control" type="text" name="pemateri" value="" required ></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Pelatihan  </label></td>
                            <td>
                                <textarea class="form-control" type="text" name="pltran_nam" value="" required ></textarea>
                            </td>

                        </tr>
                    </table>
                </div>
                <div class="col-md-6">

                    <table class="table">


                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tipe  </label></td>
                            <td>
                                <select class="form-select" name="tipe" id="package"
                                    aria-label="Default select example">
                                    <option selected value="Training">Training</option>
                                    <option value="Sosialisasi">Sosialisasi</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Kompetensi  </label></td>
                            <td>
                                <select class="form-select" name="kompetensi" aria-label="Default select example">
                                    <option selected>Pilih Kompetensi</option>
                                    @foreach ($kompe_d as $item)
                                        <option value="{{ $item->kompetensi }}">{{ $item->kompetensi }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <td><label for="example-text-input" class="form-control-label">tanggal  </label></td>
                            <td><input class="form-control" type="date" name="train_dat" value="" required ></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Jam  </label></td>
                            <td>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input class="form-control md-2" type="time" name="jam" value="" required >
                                    </div>s/d
                                    <div class="col-md-4">
                                        <input class="form-control" type="time" name="sdjam" value="" required >
                            </td>
                </div>
            </div>


            </tr>
            <tr>
                <td><label for="example-text-input" class="form-control-label">Materi Pelatihan  </label></td>
                <td>
                    <textarea class="form-control" type="text" name="train_tema" value="" required ></textarea>
                </td>
            </tr>
            </table>
            <div style="max-width: 500px;" class="container mt-5">
                <select class="nama_asli form-control" ></select>       
            </div>

           
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah">TAMBAH </button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-hover  " style="width:100%;">
                <thead class="bg-secondary text-light">
                    <th>No.</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                    <th>Cek HR</th>
                    <th>Action</th>
                </thead>
                <tbody id="tbl-barang-body" >
                </tbody>
            </table>


        </div>
    </div>

  

    </div>
    <div class="row px-4">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 ">

            <label for="example-text-input" class="form-control-label">Verisikasi HR :</label>
            <select class="form-select" name="approve_h" aria-label="Default select example">
                <option selected value="NO">NO</option>
                <option value="YES">YES</option>
            </select>
            <div class="row btnSave m-1 text-center item-center" style="display:none;">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-success btn-lg border-danger">SIMPAN </button>
                </div>
            </div>
        </div>
    </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
        integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>

<script>
  
        $(function() {
            var count = 0;

            if (count == 0) {
                $('.btnSave').hide();
            }
            $('#btn-tambah').on('click', function() {
                count += 1;
                $('#tbl-barang-body').append(`
            <tr>
                <td>` + count + `</td>
                <td>
                    <input type="text"   name="no_payroll[` + (count - 1) + `]" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="nama_asli[` + (count - 1) + `]" class="nama_asli form-control" required> 
                    </td>
                <td>
                    <input type="text" name="nilai[` + (count - 1) + `]" class="form-control" >
                </td>
                <td>
                    <input type="text" name="keterangan[` + (count - 1) + `]" class="form-control" >
                </td>
                <td>
                    <select class="form-select" name="approve[` + (count - 1) + `]" aria-label="Default select example">
                        <option selected value=""></option>
                        <option value="Y">Y</option>
                    </select>
                </td>
                <td><button class="btn removeItem btn-sm btn-danger ">hapus</button></td>
            </tr>
        `);

                if (count > 0) {
                    $('.btnSave').show();
                }

                $('.removeItem').on('click', function() {
                    $(this).closest("tr").remove();
                    count -= 1;
                    if (count == 0) {
                        $('.btnSave').hide();
                    }
                })
            })
        })
        
    </script>
    <script>
        $('#package').on('change', function() {
            const selectedPackage =
                $('#package').val();
            $('#selected').text(selectedPackage);
        });
    </script>


  <link rel="stylesheet" type="text/css"
  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


        <script type="text/javascript">
        $('.nama_asli').select2({
        placeholder: 'Select pegawai',
        ajax: {
            url: '/autocomplete',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama,
                            id: item.id,
                          
                        }
                    })
                };
            },
            cache: true
        }
    });

        
        </script>
 
    </div>
    
@endsection
