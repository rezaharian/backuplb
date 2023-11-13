@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <form action="{{ url('/hr/dashboard/overt/store/') }}" method="POST" enctype="multipart/form-data">
            <div class="card border-primary rounded-0 pt-1 card1">
                @csrf

                <div class="row ">
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
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Tanggal
                                    </label></td>
                                <td>
                                    <input type="date" class="form-control form-control-sm" name="ot_dat">
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Hari
                                    </label>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm" name="ot_day" id="">
                                        <option value=""></option>
                                        @foreach ($hari as $item)
                                            <option value="{{ $item }}"> {{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian
                                    </label> </td>
                                <td>
                                    <select class="form-control form-control-sm" name="ot_bag" id="">
                                        <option value=""></option>
                                        @foreach ($bag as $item)
                                            <option value="{{ $item->bagian }}"> {{ $item->bagian }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Pekerjaan
                                    </label>
                                </td>
                                <td><input class="form-control form-control-sm" type="text" name="keterangan"
                                        value="">
                                </td>
                            </tr>


                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-primary rounded-0 pt-1 card1">

                <div class="row p-1 m-0 ">
                    <div class="col-md-2">
                        <input class="no_payroll form-control form-control-sm " type="text" id="nik"
                            required="required" readonly placeholder="NIK">
                    </div>
                    <div class="col-md-3 ">
                        <select class="nama_asli form-control font-weight-bolder  " type="text" id="no_payroll"
                            onChange="getcustomerid(this);"></select>
                    </div>
                    <div class="col-md-1 text-right">
                        <small hidden class="font-weight-bolder text-secondary">Cari Nama Karyawan</small>
                        <button type="button" class="btn btn-primary btn-sm " id="btn-tambah">TAMBAH </button>
                        <div class="col-md-2">
                            <input class="no_payroll form-control" type="text" id="nama_asli" required="required"
                                readonly hidden>
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
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="row">
                    <div class="col">


                        <table class="table table-sm align-items-center mb-0 table-striped table-hover  "
                            style="width:100%;">
                            <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                                <th hidden>No.</th>
                                <th hidden>kd</th>
                                <th>Nama Karyawan</th>
                                <th>Mulai</th>
                                <th>Akhir</th>
                                <th>Lemburan</th>
                                <th>No SPK</th>
                                <th>Line</th>
                                <th>Tugas</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="tbl-barang-body">
                            </tbody>
                        </table>
                    </div>
                </div>



            </div>
    </div>
    <div class=" btnSave  text-center item-center mt-2">
        <button type="submit" class="btn btn-primary btn-sm border-success">Simpan</button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript"></script>
    <script>
        $(function() {
            var count = 0;

            // if (count == 0) {
            //     $('.btnSave').hide();
            // }
            $('#btn-tambah').on('click', function() {
                var r = document.getElementById('nama_asli').value;
                var s = document.getElementById('no_payroll').value;
                document.getElementById('nama_asli').value = '';
                document.getElementById('no_payroll').value = '';
                count += 1;
                $('#tbl-barang-body').append(`
        <tr>
        <td hidden>` + count + `</td>            
        <td hidden>
            <input type="text" name="ot_cod_d[` + (count - 1) + `]" value="{{ $ot_cod_d }}[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>
         <td>
            <input required class="form-control  form-control-sm" name="nama_asli[` + (count - 1) +
                    `]" id="" value="` + r + `">
                           
        </td>
        <td>
            <input type="time" name="ot_hrb[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>
        <td>
            <input type="time" name="ot_hre[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>
        <td>
            <input type="text" name="catatan[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>
        <td>
            <input type="text" name="spk_nomor[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>
        <td>
            <select class="form-control form-control-sm" name="line[` + (count - 1) + `]" id="">
                            <option value=""></option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                            <option value="VI">VI</option>
                            <option value="VII">VII</option>
                            <option value="VIII">VIII</option>
                            <option value="IX">IX</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                            <option value="XIII">XIII</option>
                            <option value="XIV">XIV</option>
                            <option value="XV">XV</option>
                            <option value="XVI">XVI</option>
                            <option value="XVII">XVII</option>
                            <option value="XVIII">XVIII</option>
                            <option value="XIX">XIX</option>
                            <option value="XX">XX</option>
                            <option value="XXXI">XXXI</option>
                         
                 </select>        
            </td>
        <td>
            <input type="text" name="tugas[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>

        <td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
    </tr>
    `);

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
                url: '/autocompleted',
                dataType: 'json',
                delay: 50,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
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
    <script>
        // Mendefinisikan daftar hari
        var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

        // Mendaftarkan event listener untuk elemen tanggal
        var inputDate = document.querySelector("input[name='ot_dat']");
        inputDate.addEventListener("change", function() {
            var selectedDate = new Date(this.value);
            var dayOfWeek = days[selectedDate.getDay()];
            var selectDay = document.querySelector("select[name='ot_day']");
            selectDay.value = dayOfWeek;
        });
    </script>


    <style>
        thead {
            font-size: 10pt;
        }

        thead th {
            padding: 10px;
        }

        /*  */
        .form-control {
            border-radius: 0;
        }

        .card1,
        .card2 {
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
