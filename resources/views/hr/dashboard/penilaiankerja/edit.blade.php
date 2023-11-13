@extends('hr.dashboard.layout.layout')

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
        .form-control,
        .card,
        .btn {
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
        @if (Session::has('danger'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('danger') }}
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
                <form class="form-row" method="post"
                    action="{{ route('hr.penilaiankerja.update', ['id' => $pkinerja->id]) }}">
                    @csrf
                    <div class="col-12 mt-1 " hidden>
                        <input type="text" class="form-control form-control-sm" placeholder="kode" name="kode"
                            required value="{{ $kode }}">
                    </div>
                    <div class="row">
                        <label for="" class="text-muted">Periode Absen:</label>
                        <div class="col">
                            <input type="date" class="form-control form-control-sm text-muted" placeholder="awal"
                                id="awal" name="awal" value="{{ $pkinerja->awal }}" required readonly >
                        </div>
                        <div class="col">
                            <input type="date" class="form-control form-control-sm text-muted" placeholder="akhir"
                                id="akhir" name="akhir" value="{{ $pkinerja->akhir }}" required readonly>
                        </div>
                    </div>
                        <div class="col-12 mt-1">
                            <input type="text" class="form-control form-control-sm" placeholder="no_payroll"
                                id="no_payroll" name="no_payroll" required readonly value="{{ $peg->no_payroll }}">
                        </div>
                        <div class="col-12 my-1">
                            <input type="text" class="form-control form-control-sm" placeholder="Nama" id="nama"
                                name="nama" required readonly value="{{ $peg->nama_asli }}">
                        </div>
                    <div class="row my-1">
                        <div class="col">
                            <input type="text" class="form-control form-control-sm" placeholder="Bagian" id="bagian"
                                name="bagian" required readonly value="{{ $peg->bagian }}">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control form-control-sm" placeholder="Jabatan" id="jabatan"
                                name="jabatan" readonly value="{{ $peg->jabatan }}">
                        </div>
                    </div>


                    {{-- REVIEW --}}
                    <div class="card mt-1 p-1">
                        <div class="col-12 mt-1">
                            <label>Review:</label>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <input type="checkbox" id="permanent" name="review" value="Permanent"
                                        {{ $pkinerja->review == 'Permanent' ? 'checked' : '' }}>
                                    <label for="permanent">Permanent</label><br>

                                    <input type="checkbox" id="terminated" name="review" value="Terminated"
                                        {{ $pkinerja->review == 'Terminated' ? 'checked' : '' }}>
                                    <label for="terminated">Terminated</label><br>

                                    <input type="checkbox" id="extend_contract" name="review" value="Extend Contract"
                                        {{ $pkinerja->review == 'Extend Contract' ? 'checked' : '' }}>
                                    <label for="extend_contract">Extend Contract</label><br>
                                </div>
                                <div class="col">
                                    <input type="checkbox" id="promote_grade" name="review" value="Promote Grade"
                                        {{ $pkinerja->review == 'Promote Grade' ? 'checked' : '' }}>
                                    <label for="promote_grade">Promote Grade</label><br>

                                    <input type="checkbox" id="annual_review" name="review" value="Annual Review"
                                        {{ $pkinerja->review == 'Annual Review' ? 'checked' : '' }}>
                                    <label for="annual_review">Annual Review</label><br>

                                    <input type="checkbox" id="probation_period" name="review" value="Probation Period"
                                        {{ $pkinerja->review == 'Probation Period' ? 'checked' : '' }}>
                                    <label for="probation_period">Probation Period</label><br>
                                </div>
                                <div class="col">
                                    <input type="checkbox" id="promotion" name="review" value="Promotion"
                                        {{ $pkinerja->review == 'Promotion' ? 'checked' : '' }}>
                                    <label for="promotion">Promotion</label><br>

                                    <input type="checkbox" id="down_grade" name="review" value="Down Grade"
                                        {{ $pkinerja->review == 'Down Grade' ? 'checked' : '' }}>
                                    <label for="down_grade">Down Grade</label><br>

                                    <input type="checkbox" id="other" name="review" value="Other"
                                        {{ $pkinerja->review == 'Other' ? 'checked' : '' }}>
                                    <label for="other">Other</label><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        // jQuery script to ensure only one checkbox is checked at a time
                        $('input[type="checkbox"]').click(function() {
                            $('input[type="checkbox"]').not(this).prop('checked', false);
                        });
                    </script>





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
                                        name="mkr" value=" {{ $pkinerja->mkr }} ">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=" "
                                        name="mkr_value" value=" {{ $pkinerja->mkr_value }}" readonly>
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
                                        name="ijin" value="{{ $pkinerja->ijin }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=" "
                                        name="ijin_value" value="{{ $pkinerja->ijin_value }}" readonly>
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
                                        name="sakit" value="{{ $pkinerja->sakit }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=" "
                                        name="sakit_value" value="{{ $pkinerja->sakit_value }}" readonly>
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
                                        name="mdt" value="{{ $pkinerja->mdt }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder="  "
                                        name="mdt_value" value="{{ $pkinerja->mdt_value }}" readonly>
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
                                    <input type="text" class="form-control form-control-sm" placeholder="SP"
                                        name="sp" required value="{{ $pkinerja->sp }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder="SP VALUE"
                                        name="sp_value" required value="{{ $pkinerja->sp_value }}">
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col card border-primary px-1 ms-1" style="height:535px;overflow:auto; width:auto;">
                <div class=" ">

                    <h6 class="text-center text-light fw-bold bg-primary p-2">BERIKAN PENILAIAN SESUAI DENGAN INDIKATOR
                        SECARA OBJEKTIF !!</h6>
                    <hr>
                    <label for="" class="text-center">COMPETENCE STANDARD</label>
                    <div class="sticky-top">
                        <div class="row" style="font-size: 10pt;">
                            <div class="row">
                                <p class="m-0  bg-light fw-bold "> Total Score :   {{ $pkinerja->total_score }} </p>
                                <p class="m-0  bg-light  "> Penilai : </p>
                                <div class="col bg-dark text-light fw-bold p-2 m-1 mt-0">
                                    @if ($penilai->un1)
                                        {{ $penilai->un1 }}
                                    @endif
                                </div>
                                <div class="col bg-dark text-light fw-bold p-2 m-1 mt-0">
                                    @if ($penilai->un2)
                                        {{ $penilai->un2 }}
                                    @endif
                                </div>
                                <div class="col bg-dark text-light fw-bold p-2 m-1 mt-0">
                                    @if ($penilai->un3)
                                        {{ $penilai->un3 }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="font-size: 10pt;">
                        @foreach ($pkinerja_b as $item)
                        <div class="card bordered border-primary p-1">

                            <div class="col-md-12 m-0">
                                <input type="text" class="form-control form-control-sm fw-bold" placeholder=""
                                    style="background-color: transparent; border:none;" value="{{ $item->perf_faktor }}"
                                    name="perf_faktor[]" required placeholder="{{ $item->perf_faktor }}" readonly>
                            </div>
                            <div class="col-md-12 m-0">
                                <textarea class="fw-bold" name="penjelasan[]" style="text-align: justify;  border:hidden" cols="80" rows="4" readonly>{{ $item->penjelasan }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4 m-0">
                                    <select id="nilai1" class="form-control form-control-sm" name="nilai1[]">
                                        <option value="0" {{ $item->nilai1 == 0 ? 'selected' : '' }}>Nilai
                                        </option>
                                        <option value="1" {{ $item->nilai1 == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ $item->nilai1 == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ $item->nilai1 == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ $item->nilai1 == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ $item->nilai1 == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                </div>
                                <div class="col-md-4 m-0">
                                    <select id="nilai2" class="form-control form-control-sm" name="nilai2[]">
                                        <option value="0" {{ $item->nilai2 == 0 ? 'selected' : '' }}>Nilai
                                        </option>
                                        <option value="1" {{ $item->nilai2 == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ $item->nilai2 == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ $item->nilai2 == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ $item->nilai2 == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ $item->nilai2 == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                </div>
                                <div class="col-md-4 m-0">
                                    <select id="nilai3" class="form-control form-control-sm" name="nilai3[]">
                                        <option value="0" {{ $item->nilai3 == 0 ? 'selected' : '' }}>Nilai
                                        </option>
                                        <option value="1" {{ $item->nilai3 == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ $item->nilai3 == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ $item->nilai3 == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ $item->nilai3 == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ $item->nilai3 == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                       
                            @endforeach
                    </div>
                    <label for="" class="text-center mt-1">C. ASPEK PENILAIAN TEAM BY MANAGEMENT OBJ</label>
                    <hr>
                    <div class="row">
                        <div class="col-12 mt-1">
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
                        @foreach ($kompeten as $item)
                            <div class="row">
                                <div class="col-md-5 mt-1">
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        style="background-color: transparent; border:none;"
                                        value="{{ $item->penjelasan }}" name="major_job[]" required
                                        placeholder="{{ $item->penjelasan }}" readonly>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <textarea type="text" class="form-control form-control-sm" style="background-color: transparent"
                                        name="perform_ach[]" required disabled placeholder=""></textarea>
                                </div>
                                <div class="col-md-2 mt-1">
                                    <input type="text" class="form-control form-control-sm"
                                        style="background-color: transparent" name="nilai1c[]" required disabled
                                        placeholder="">
                                </div>
                                <div class="col-md-2 mt-1">
                                    <input type="text" class="form-control form-control-sm"
                                        style="background-color: transparent" name="nilai2c[]" required disabled
                                        placeholder="">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for=".">Score </label>
                            <input type="text" class="form-control" name="score_c" value="{{ $pkinerja->score_c }}">
                        </div>
                    </div>
                    <label class="mt-1">Catatan :</label>
                    <div class="row">
                        <div class="col-md-12">

                            <textarea class="textarea form-control " name="remark" id="" cols="10" rows="10">{{ $pkinerja->remark }}</textarea>
                        </div>
                    </div>
         
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </div>
            </div>
            </form>
    </section>


    {{-- 
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            var select2 = $('.select2').select2({
                placeholder: "Pilih No Payroll",
                allowClear: true
            });

            // Menambahkan event listener ketika pilihan "no_payroll" berubah
            select2.on('change', function() {
                var selectedNoPayroll = $(this).val();

                if (selectedNoPayroll) {
                    $.ajax({
                        url: '/kelengkapandatapegawai/' + selectedNoPayroll,
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
                                        '<p class="input-group-text form-control-sm border-0 m-0 bg-light">' + (index +
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
    </script> --}}





@endsection
