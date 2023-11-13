@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <style>
        /* Gaya untuk elemen select */
        select {
            padding: 5px;
            /* Padding agar lebih nyaman dilihat */
            font-size: 14px;
            /* Ukuran huruf */
            border: 1px solid #ccc;
            /* Garis border */
            border-radius: 4px;
            /* Rounding border */
            background-color: #f2f2f2;
            /* Warna latar belakang */
            color: #333;
            /* Warna teks */
            width: 100%;
            /* Lebar elemen sesuai dengan parent */
        }

        /* Gaya untuk elemen select saat dihover */
        select:hover {
            border-color: #666;
            /* Warna border saat dihover */
        }
        .form-control, .card, .btn {
            border-radius: 0%;

        }
    </style>
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
            <div class="alert alert-info text-center px-5">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

    </div>
    {{-- <section>
        <div class="row text-center">
            <h3>PERFORMANCE ATTENDANCE</h3>
        </div>
    </section> --}}
    <section class="text-muted">
        <div class="row">
            <div class="col-md-5 card m-0 border-primary p-1">
                <form class="form-row" method="post" action="{{ route('tr.hr.penilaiankerja.store') }}">
                    @csrf
                    <div class="col-12 mt-1 " hidden>
                        <input type="text" class="form-control form-control-sm" placeholder="kode" name="kode"
                            required value="{{ $kode }}">
                    </div>
                    <div class="row">
                        <label for="" class="text-muted">Periode Absen:</label>
                        <div class="col">
                            <input type="date" class="form-control form-control-sm text-muted" placeholder="awal" id="awal"
                            name="awal" required >
                        </div>
                        <div class="col">
                            <input type="date" class="form-control form-control-sm text-muted" placeholder="akhir" id="akhir"
                            name="akhir" required >
                        </div>
                    </div>
                    <div class="col-12 mt-1">
                        <select class="form-control form-control-sm select2" name="no_payroll" required>
                            <option selected value=""></option>
                            @foreach ($peg as $item)
                                <option value="{{ $item->no_payroll }}">{{ $item->no_payroll }} | {{ $item->nama_asli }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mt-1" hidden>
                        <input type="text" class="form-control form-control-sm" placeholder="Nama" id="nama"
                            name="nama" required readonly>
                    </div>
                    <div class="row my-1">
                        <div class="col">
                            <input type="text" class="form-control form-control-sm" placeholder="Bagian" id="bagian"
                                name="bagian" required readonly>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control form-control-sm" placeholder="Jabatan" id="jabatan"
                            name="jabatan" readonly>
                        </div>
                    </div>

                    {{-- REVIEW --}}
                    <div class="card mt-1 p-1">

                        <div class="col-12 mt-1">
                            <label>Review:</label>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <input type="checkbox" id="permanent" name="review" value="Permanent">
                                    <label for="permanent">Permanent</label><br>

                                    <input type="checkbox" id="terminated" name="review" value="Terminated">
                                    <label for="terminated">Terminated</label><br>

                                    <input type="checkbox" id="extend_contract" name="review" value="Extend Contract">
                                    <label for="extend_contract">Extend Contract</label><br>

                                </div>
                                <div class="col">
                                    <input type="checkbox" id="promote_grade" name="review" value="Promote Grade">
                                    <label for="promote_grade">Promote Grade</label><br>

                                    <input type="checkbox" id="annual_review" name="review" value="Annual Review">
                                    <label for="annual_review">Annual Review</label><br>

                                    <input type="checkbox" id="probation_period" name="review" value="Probation Period">
                                    <label for="probation_period">Probation Period</label><br>
                                </div>
                                <div class="col">

                                    <input type="checkbox" id="promotion" name="review" value="Promotion">
                                    <label for="promotion">Promotion</label><br>

                                    <input type="checkbox" id="down_grade" name="review" value="Down Grade">
                                    <label for="down_grade">Down Grade</label><br>

                                    <input type="checkbox" id="other" name="review" value="Other">
                                    <label for="other">Other</label><br>
                                </div>
                            </div>
                        </div>

                        <script>
                            // jQuery script to ensure only one checkbox is checked at a time
                            $('input[type="checkbox"]').click(function() {
                                $('input[type="checkbox"]').not(this).prop('checked', false);
                            });
                        </script>
                    </div>




                    {{-- END REVIEW --}}

                    {{-- DISCIPLINE --}}
                    <div class="card mt-1 p-1">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-12 mt-1">
                                    <label for="">ATTENDANCE EVALUATION</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <label for="">DAYS</label>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <label for="">SCORE</label>

                                </div>
                            </div>
                        </div>
                        <hr class="m-0 p-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-12 mt-1">
                                    <label for="">1.Absent without notice (Alpha)</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="mkr" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=" "
                                        name="mkr_value" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-12 mt-1">
                                    <label for="">2.Absent with authorized (Izin)</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="ijin" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=" "
                                        name="" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-12 mt-1">
                                    <label for="">3.Sick</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="sakit" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=" "
                                        name="sakit_value" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-12 mt-1">
                                    <label for="">4.Late</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=" "
                                        name="mdt" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder="  "
                                        name="mdt_value" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-12 mt-1">
                                    <label for="">5.SP/Year</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="sp">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="number" class="form-control form-control-sm" placeholder="" max="5"
                                        name="sp_value">
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="col card border-primary px-1 ms-1" style="height:510px;overflow:auto; width:auto;">
                <div class=" ">
                    <h6 class="text-center text-light fw-bold bg-primary p-2">BERIKAN PENILAIAN SESUAI DENGAN INDIKATOR SECARA OBJEKTIF !!</h6>
                    <hr>
                    <label for="" class="text-center">COMPETENCE STANDARD</label>
                    <div class="row" style="font-size: 10pt;">
                        @foreach ($kompeten as $item)
                        <div class="card bordered border-primary p-2">

                                <div class="col-md-12 m-0">
                                    <input type="text" class="form-control form-control-sm fw-bold" placeholder=""
                                        style="background-color: transparent; border:none;"
                                        value="{{ $item->perf_faktor }}" name="perf_faktor[]" required
                                        placeholder="{{ $item->perf_faktor }}" readonly>
                                </div>
                                <div class="col-md-12 m-0">
                                    <textarea class="fw-bold" name="penjelasan[]" style="text-align: justify;  border:hidden" cols="78" rows="4" readonly>{{ $item->penjelasan }}</textarea>
                                </div>
                                <div class="row">

                                    <div class="col-md-4 m-0">
                                        <select id="nilai1" class="form-control form-control-sm" name="nilai1[]">
                                            <option value="0">Nilai</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 m-0" hidden>
                                        
                                        <select id="nilai2" class="form-control form-control-sm" name="nilai2[]" h>
                                            <option value="0">Nilai</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 m-0" hidden>
                                        <select id="nilai3" class="form-control form-control-sm" name="nilai3[]">
                                            <option value="0">Nilai</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>


                        </div>
                                                @endforeach
                    </div>
                    <label for="" class="text-center mt-3">C. ASPEK PENILAIAN TEAM BY MANAGEMENT OBJECTIVE</label>
                    <hr>
                    <div class="row">

                        <div class="col-12 mt-1">
                            <select class="form-control form-control-sm select3" name="bagian1" id="bagian">
                                <option selected value=""></option>
                                @foreach ($kompeten2 as $item)
                                    <option value="{{ $item->bagian }}">{{ $item->bagian }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="col-12 mt-1">
                                    <label for="">MAJOR JOB</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="col-12 mt-1">
                                    <label for="">PERFORMANCE ACH</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <label for="">MGR</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-12 mt-1">
                                    <label for="">MGMT</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-12 mt-1" id="penjelasan-container">

                                <div class="col-12 mt-1">
                                    <input class="form-control form-control-sm bg-transparant border-0" placeholder="" id="penjelasan"
                                        name="penjelasan1">
                                </div>
                            </div>
                        </div>
           





                        {{-- @foreach ($kompeten2 as $item)
                            <div class="row">
                                <div class="col-md-5 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        style="background-color: transparent; border:none;"
                                        value="{{ $item->penjelasan }}" name="major_job[]" required
                                        placeholder="{{ $item->penjelasan }}" readonly>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <textarea type="text" class="form-control form-control-sm" style="background-color: transparent"
                                        name="perform_ach[]" required placeholder=""></textarea>
                                </div>
                                <div class="col-md-2 mt-1">
                                    <input type="text" class="form-control form-control-sm"
                                        style="background-color: transparent" name="nilai1c[]" required placeholder="">
                                </div>
                                <div class="col-md-2 mt-1">
                                    <input type="text" class="form-control form-control-sm"
                                        style="background-color: transparent" name="nilai2c[]" required placeholder="">
                                </div>
                            </div>
                        @endforeach --}}
                    </div>


                    <div class="row">
                        <div class="col">
                            <label for=".">Score </label>
                            <input type="text" class="form-control form-control-sm" name="score_c">
                        </div>

                    </div>
                    <label class="mt-1">Catatan :</label>
                    <div class="row">
                        <div class="col-md-12">

                            <textarea class="textarea form-control " name="remark" id="" cols="10" rows="10"></textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>





                </div>
            </div>
        </div>
 
        </form>




    </section>



    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            var select2 = $('.select2').select2({
                placeholder: "No Payroll Atau Nama",
                allowClear: true
            });


            $('.select2').next('.select2-container').find('.select2-selection').css('border-radius', '0');


            // Menambahkan event listener ketika pilihan "no_payroll" berubah
            select2.on('change', function() {
                var selectedNoPayroll = $(this).val();

                if (selectedNoPayroll) {
                    $.ajax({
                        url: '/kelengkapanpegawaitr/' + selectedNoPayroll,
                        type: 'GET',
                        success: function(data) {
                            if (data) {
                                $('#nama').val(data.nama_asli);
                                $('#bagian').val(data.bagian);
                                $('#jabatan').val(data.jabatan);

                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            var select3 = $('.select3').select2({
                placeholder: "Pilih bagian",
                allowClear: true
            });

            select3.on('change', function() {
                var selectBagian = $(this).val();
                console.log('Selected Bagian:', selectBagian);

                if (selectBagian) {
                    $.ajax({
                        url: '/kelengkapandataperbagian/' + selectBagian,
                        type: 'GET',
                        success: function(data) {
                            console.log('Response Data:', data);
                            if (data && data.penjelasan.length > 0) {
                                $('#penjelasan-container')
                                    .empty(); // Menghapus konten sebelumnya
                                data.penjelasan.forEach(function(penjelasan, index) {
                                    // Menambahkan input teks untuk setiap penjelasan
                                    var inputHtml =
                                        '<div class="input-group mb-0 bg-light">' +
                                        '<p class="input-group-text form-control-sm border-0 m-0 bg-light">' +
                                        (index +
                                            1) + '</p>' +
                                        '<input type="text" class="form-control form-control-sm border-0" value="' +
                                        penjelasan + '" >' +
                                        '</div>';
                                    $('#penjelasan-container').append(inputHtml);

                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error:', textStatus, errorThrown);
                        }
                    });
                }
            });



        }); // Don't forget this closing parenthesis and semicolon
    </script>

<script>
    // Mendapatkan elemen input tanggal dengan id 'awal'
    var awalInput = document.getElementById('awal');
    // Mendapatkan elemen input tanggal dengan id 'akhir'
    var akhirInput = document.getElementById('akhir');

    // Mendapatkan tahun saat ini
    var currentYear = new Date().getFullYear();
    // Mengatur nilai default 'awal' ke awal tahun
    awalInput.value = currentYear + "-01-01";

    // Mendapatkan tanggal hari ini
    var today = new Date().toISOString().split('T')[0];
    // Mengatur nilai default 'akhir' ke tanggal hari ini
    akhirInput.value = today;
</script>



@endsection
