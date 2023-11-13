@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card card-plain slide-right rounded-0 border-primary">

                <div class="card-header pb-0 rounded-0 bg-primary ">
                    <h4 class="font-weight-bolder text-light">Edit Korekasi Absen</h4>
                </div>
                <form action="{{ url('/hr/dashboard/tdkabsen/update', $tdkabsen->id) }}" method="POST">
                    @csrf
                    <table class="table ">

                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Tanggal </label>
                            </td>
                            <td>
                                <input class="form-control form-control-sm" type="date" name="ta_tgl"
                                max="{{ date('Y-m-d') }}"  value="{{ $tdkabsen->ta_tgl }}" required>
                               
                            </td>
                        </tr>


                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">NIK
                                </label>
                            </td>
                            <td>
                                <select class="nama_asli form-control font-weight-bolder  " name="npayroll" type="text"
                                    id="no_payroll" onChange="getcustomerid(this);" aria-placeholder="">
                                </select>
                                <input class="no_payroll form-control" type="text" id="nop" name="no_payroll"
                                    required="required" value="{{ $tdkabsen->no_payroll }}" hidden>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label   text-secondary">Nama
                                </label>
                            </td>
                            <td>
                                <input class="no_payroll form-control form-control-sm " name="nama_asli" type="text"
                                    id="nik" required="required" readonly placeholder=""
                                    value="{{ $tdkabsen->nama_asli }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Ralat Hadir
                                </label>
                            </td>
                            <td><select class="no_payroll form-control form-control-sm" type="text" name="pdsaat"
                                    id="option">
                                    <option selected value="{{ $tdkabsen->pdsaat }}">{{ $tdkabsen->pdsaat }}</option>
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
                            <td><input class="form-control form-control-sm" type="time" name="masuk" id="field1"
                                    value="{{ $tdkabsen->masuk }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Pulang
                                </label>
                            </td>
                            <td><input class="form-control form-control-sm" type="time" name="pulang" id="field2"
                                    value="{{ $tdkabsen->pulang }}">
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
        <div class="col md-3">
            

        </div>
    </div>


    <div class="row  ">
        <div class="col-md-1 text-right">
            <small hidden class="font-weight-bolder text-secondary">Cari Nama Karyawan</small>
            <div class="col-md-2">
                <input class="no_payroll form-control" type="text" id="nama_asli" required="required" readonly hidden>
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
