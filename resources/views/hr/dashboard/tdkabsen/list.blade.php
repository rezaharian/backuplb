@extends('hr.dashboard.layout.layout')

@section('content')



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
    
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    
    @if (Session::has('success'))
        <div class="alert alert-info" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    
    <script>
        $('.alert').delay(3000).fadeOut('slow');
    </script>
    
    
        <div class="col-md-4">
            <div class="card card-plain card1 rounded-0 border-primary ">
                <div class="card-header pb-0 text-left  rounded-0 bg-primary">
                    <h4 class="font-weight-bolder text-light">Tambah Koreksi Absen</h4>
                </div>
                <div class="card-body">
                    <form action="/hr/dashboard/tdkabsen/create" method="POST">
                        @csrf
                        <table class="table ">

                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Kode
                                    </label>
                                </td>
                                <td>
                                    <input readonly class="form-control form-control-sm" type="text" name="ta_cod"
                                        value="{{ $kode }}">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="example-text-input"
                                        class="form-control-label  text-secondary">Tanggal</label>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="date" name="ta_tgl"
                                        max="{{ date('Y-m-d') }}" required>
                                </td>
                            </tr>


                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">NIK
                                    </label>
                                </td>
                                <td>
                                    <select class="nama_asli form-control font-weight-bolder  " name="npayroll"
                                        type="text" id="no_payroll" onChange="getcustomerid(this);" required></select>
                                    <input class="no_payroll form-control" type="text" id="nop" name="no_payroll"
                                        required="required" hidden>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label   text-secondary">Nama
                                    </label>
                                </td>
                                <td>
                                    <input class="no_payroll form-control form-control-sm " name="nama_asli" type="text"
                                        id="nik" required="required" readonly placeholder="">
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Ralat Hadir
                                    </label>
                                </td>
                                <td><select class="no_payroll form-control form-control-sm" type="text" name="pdsaat"
                                        id="option">
                                        <option value="semua">Semua</option>
                                        <option value="masuk">Masuk</option>
                                        <option value="pulang">Pulang</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Masuk
                                    </label>
                                </td>
                                <td><input class="form-control form-control-sm" type="time" name="masuk"
                                        id="field1">
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label  text-secondary">Pulang
                                    </label>
                                </td>
                                <td><input class="form-control form-control-sm" type="time" name="keluar"
                                        id="field2">
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>

                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                </td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-8">

            <div class="row  ">
                <div class="col-md-1 text-right">
                    <small hidden class="font-weight-bolder text-secondary">Cari Nama Karyawan</small>
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
                        document.getElementById('nop').value = nama_asli;

                    }
                </script>


            </div>

            <div class="card card-plain card1  rounded-0 border-primary">
                <div style="height:522px;overflow:auto;">
                    <table class="  align-items-center mb-0  ">
                        <thead
                            class=" bg-primary text-center text-uppercase  text-light shadow font-weight-bolder sticky-top  ">
                            <tr style="font-size: 8pt; background-color:rgb(5, 109, 255);" class="text-light">
                                <th style="width:1%;" scope="col">No</th>
                                <th style="width:8%;" scope="col">Kode </th>
                                <th style="width:4%;" scope="col">Nik</th>
                                <th style="width:18%;" scope="col">Nama</th>
                                <th style="width:9%;" scope="col">Tanggal</th>
                                <th style="width:5%;" scope="col">Ralat</th>
                                <th style="width:3%;" scope="col">Masuk</th>
                                <th style="width:3%;" scope="col">Pulang</th>
                                <th style="width:5%;" scope="col">Status</th>
                                <th style="width:10%;" scope="col">Opt</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9pt;" class="text-secondary ">
                            @foreach ($tdkabsen as $item)
                                <tr style="background-color: {{ $loop->iteration % 2 == 0 ? '#f2f2f2' : '#fff' }};">
                                    <th>{{ $loop->iteration }}</th>
                                    <td style="text-align: center;">{{ $item->ta_cod }}</td>
                                    <td>{{ $item->no_payroll }}</td>
                                    <td>{{ $item->nama_asli }}</td>
                                    <td>{{ $item->ta_tgl }}</td>
                                    <td>{{ $item->pdsaat }}</td>
                                    <td>{{ $item->masuk }}</td>
                                    <td>{{ $item->pulang }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td class="text-center">
                                        <div>
                                            <a href="{{ route('hr.tdkabsen.delete', $item->id) }}"
                                                onclick="return confirm('Apakah Anda Yakin ?');"
                                                class="btn btn-sm m-0 text-danger"> <i class="fa-solid fa-trash"></i> </a>
                                            <a href="{{ route('hr.tdkabsen.edit', $item->id) }}"
                                                class="btn btn-sm m-0 text-primary"> <i class="fa-solid fa-pen"></i> </a>
                                    </td>
                </div>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
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


    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $('.nama_asli').select2({
            placeholder: '',
            ajax: {
                url: '/autocompleted_tdkabsen',
                dataType: 'json',
                delay: 50,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.no_payroll,
                                nik: item.nama_asli,
                                id: item.nama_asli,
                                nop: item.no_payroll
                                // id: item.id,
                            }
                        })
                    };
                },
                cache: false
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#field1").prop("required", true);
            $("#field2").prop("required", true);
            $("#option").change(function() {
                var selectedOption = $(this).val();

                if (selectedOption == "semua") {
                    $("#field1").removeAttr("disabled").prop("required", true);
                    $("#field2").removeAttr("disabled").prop("required", true);
                } else if (selectedOption == "masuk") {
                    $("#field2").attr("disabled", "disabled").prop("required", false);
                    $("#field1").removeAttr("disabled").prop("required", true);
                    $("#field2").val("");
                } else if (selectedOption == "pulang") {
                    $("#field1").attr("disabled", "disabled").prop("required", false);
                    $("#field2").removeAttr("disabled").prop("required", true);
                    $("#field1").val("");
                } else {
                    $("#field1").attr("disabled", "disabled").prop("required", false);
                    $("#field2").attr("disabled", "disabled").prop("required", false);
                }
            });
        });
    </script>

<style>
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
