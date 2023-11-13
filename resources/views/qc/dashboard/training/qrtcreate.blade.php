@extends('qc.dashboard.layout.layout')

@section('content')
    <div class="container  px-4">


        <form action="{{ url('/qc/training/qtrstore') }}" method="POST">
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

                    <table class="table ">

                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">NO Doc </label>
                            </td>
                            <td><input class="form-control form-control-sm " type="text" name="train_cod"
                                    value="{{ $no }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Tempat </label>
                            </td>
                            <td><input class="form-control form-control-sm" type="text" name="tempat" value=""
                                    required></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Pemateri </label>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="pemateri"
                                    aria-label="Default select example">
                                    <option selected>Pilih Pemateri</option>
                                    @foreach ($pemateri as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                   
                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Pelatihan </label>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" type="text" name="pltran_nam" value="" required></textarea>
                            </td>

                        </tr>
                    </table>
                </div>
                <div class="col-md-5">

                    <table class="table">


                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Tipe </label></td>
                            <td>
                                <select class="form-select form-select-sm" name="tipe" id="package"
                                    aria-label="Default select example">
                                    <option selected value="Pelatihan">Pelatihan</option>
                                    <option value="Sosialisasi">Sosialisasi</option>
                                </select>
                            </td>
                        </tr>

                        <tr hidden>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Kompetensi
                                </label></td>
                            <td>
                                <select class="form-select form-select-sm" name="kompetensi"
                                    aria-label="Default select example">
                                    <option selected>Pilih Kompetensi</option>
                                    @foreach ($kompe_d as $item)
                                        <option value="{{ $item->kompetensi }}">{{ $item->kompetensi }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Tanggal </label>
                            </td>
                            <td><input class="form-control form-control-sm" type="date" name="train_dat" value=""
                                    required></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Jam </label></td>
                            <td>
                                <div class="row">
                                    <div class="col-md-5">
                                        <input class="form-control form-control-sm md-2" type="time" name="jam"
                                            value="" required>
                                    </div>s/d
                                    <div class="col-md-5">
                                        <input class="form-control form-control-sm" type="time" name="sdjam"
                                            value="" required>
                            </td>
                </div>
            </div>


            </tr>
            <tr>
                <td><label for="example-text-input" class="form-control-label">Materi Pelatihan </label></td>
                <td>
                    <textarea class="form-control" type="text" name="train_tema" value="" required></textarea>
                </td>
            </tr>
            </table>



    </div>

  
    <div class="row mt-2 ">
         
        <div class="col-md-1 fw-bolder text-secondary">
            File : 
        </div>
        <div class="col-md-2">
                <input type="file" name="file" class="form-control form-control-sm" >
        </div>

        <div class="col-md-3"></div>
        <div class="col-md-2">
            <input class="no_payroll form-control form-control-sm " type="text" id="nik" required="required"
                readonly placeholder="NIK">
        </div>
        <div class="col-md-3 ">
            <select class="nama_asli form-control font-weight-bolder  " type="text" id="no_payroll"
                onChange="getcustomerid(this);"></select>
        </div>
        <div class="col-md-1 text-right">
            <small hidden class="font-weight-bolder text-secondary">Cari Nama Karyawan</small>
            <button type="button" class="btn btn-primary btn-sm " id="btn-tambah">TAMBAH </button>
            <div class="col-md-2">
                <input class="no_payroll form-control" type="text" id="nama_asli" required="required" readonly
                    hidden>
            </div>

        </div>
   
        <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
        <script type="text/javascript">
        function getcustomerid(element) {
            var no_payroll = element.options[element.selectedIndex].value; // get selected option customer ID value
            var nama_asli = element.options[element.selectedIndex].text; // get Customer email   
            var nik = element.options[element.selectedIndex].value; // get Customer email   
            document.getElementById('no_payroll').value = no_payroll;
            document.getElementById('nama_asli').value = nama_asli;
            document.getElementById('nik').value = nik;
            
       
            }

        </script>

        
        </div>
      

    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                    <th hidden>No.</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Nilai Pretest</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                    <th hidden>Cek HR</th>
                    <th>Action</th>
                </thead>
                <tbody id="tbl-barang-body">
                </tbody>
            </table>
        </div>
    </div>



    </div>
    <div class="row px-4">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 ">
            <div class="row mt-2">
                <div class="col-md-2">

                </div>
                <div class="col-md-4" hidden>
                    <label for="example-text-input" class="form-control-label">Verisikasi HR :</label>
                </div>
                <div class="col-md-4" hidden>
                    <select class="form-select form-select-sm" name="approve_h" aria-label="Default select example "  >
                        <option selected value="NO">NO</option>
                        <option value="YES">YES</option>
                    </select>
                </div>
                <div class="col-md-8 " >
                </div>
                <div class="col-md-2 btnSave  text-center item-center">
                    <button type="submit" class="btn btn-primary btn-sm border-success ">Simpan</button>
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
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script type="text/javascript">

    </script>

    <script>
        $(function() {
            var count = 0;
        
            // if (count == 0) {
            //     $('.btnSave').hide();
            // }
            $('#btn-tambah').on('click', function() {
              var r =    document.getElementById('nama_asli').value;
              var s =    document.getElementById('no_payroll').value;
              document.getElementById('nama_asli').value = '';
              document.getElementById('no_payroll').value = '';
                
              count += 1;

$('#tbl-barang-body').append(`
<tr>
<td hidden>` + count + `</td>
<td  >
    <input type="text" class="form-control form-control-sm" value="` + s + `" id="no_payroll` + (
        count - 1) + `" name="no_payroll[` + (count - 1) + `]" value="" required="required" readonly>
</div>              
<td>
    <input class="form-control form-control-sm" value="` + r + `" id="nama_asli` + (count - 1) +
    `" name="nama_asli[` + (count - 1) + `]"  
    required="required" readonly>

</td>
<td>
    <input type="text" name="nilai_pre[` + (count - 1) + `]" class="form-control form-control-sm" >
</td>
<td>
    <input type="text" name="nilai[` + (count - 1) + `]" class="form-control form-control-sm" >
</td>
<td>
    <input type="text" name="keterangan[` + (count - 1) + `]" class="form-control form-control-sm" >
</td>
<td hidden>
    <select class="form-select form-select-sm" name="approve[` + (count - 1) + `]" aria-label="Default select example" >
        <option selected value=""></option>
        <option value="Y">Y</option>
    </select>
</td>
<td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
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

  <link rel="stylesheet" type="text/css"
  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


        <script type="text/javascript">
        $('.nama_asli').select2({
        placeholder: 'Cari Nama Karyawan',
        ajax: {
            url: '/qtrautocomplete',
            dataType: 'json',
            delay: 50,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama_asli,
                            nik: item.no_payroll,
                            id: item.no_payroll,
                            // id: item.id,
                          
                        }
                    })
                };
            },
            cache: false
        }
    });

        
        </script>
  
    </div>

@endsection
