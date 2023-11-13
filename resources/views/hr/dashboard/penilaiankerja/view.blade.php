@extends('hr.dashboard.layout.layout')

@section('content')

    <div class="container">
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

        <section>

            <div class="row">
                <div class="col-md-10"></div>

                <div class="text-end col-md-1">
                    <a href="{{ route('hr.penilaiankerja.edit', ['id' => $pkinerja->id]) }}" target=""
                        class="text-primary">
                        <i class="fas fa-pen" id="print-icon"></i>
                    </a>

                </div>
                <div class="text-end col-md-1">

                    <a href="{{ route('hr.penilaiankerja.print', ['id' => $pkinerja->id]) }}" target="_blank"
                        class="text-primary">
                        <i class="fas fa-print" id="print-icon"></i>
                    </a>
                </div>
            </div>



            <div class="container card">
                <div class="row text-center">
                    <h3 class="mt-2 m-0">PERFORMANCE APPRASIAL</h3>
                    <p class="m-0">Period : {{ $pkinerja->periode }}</p><br>
                </div>
                <div class="row ">
                    <div class="col-md-2">
                        <p class="m-0">Name</p>
                        <p class="m-0">Empl. No</p>
                        <p class="m-0">Position / Grade</p>
                        <p class="m-0">Dept / Div</p>
                    </div>
                    <div class="col-md-3">
                        <p class="m-0">: {{ $pkinerja->nama }}</p>
                        <p class="m-0">: {{ $pkinerja->no_payroll }}</p>
                        <p class="m-0">: {{ $pkinerja->bagian }}</p>
                        <p class="m-0">: {{ $pkinerja->jabatan }}</p>
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-2">
                        <p class="m-0">Date Of Assignment</p>
                        <p class="m-0">Hiring Date</p>
                        <p class="m-0">Contract Period</p>
                        <p class="m-0">Reason For Review</p>
                    </div>
                    <div class="col-md-3">
                        <p class="m-0">: . . . . . . . . . . . . </p>
                        <p class="m-0">: {{ $peg->tgl_masuk }}</p>
                        <p class="m-0">: {{ $pkinerja->periode }}</p>
                        <p class="m-0">: {{ $pkinerja->review }}</p>
                    </div>
                </div>
                <div> 
                    <hr>
                    <h5>Penilai :

                        @if ($penilai->un1)
                            <p>{{ $penilai->un1 }} ,
                        @endif
                        @if ($penilai->un2)
                            {{ $penilai->un2 }} ,
                        @endif
                        @if ($penilai->un3)
                            {{ $penilai->un3 }}</p>
                        @endif

                    </h5>
                </div>
                <div class="row mt-3">
                    <hr>
                    <label>A. DISCIPLINE :</label>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-12 m-0">
                                <label for="">ATTENDANCE EVALUATION</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="col-12 m-0">
                                <label for="">DAYS</label>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-12 m-0">
                                <label for="">SCORE</label>

                            </div>
                        </div>
                    </div>
                    <hr class="m-0 p-0">
                    <div class="row m-0">
                        <div class="col-md-6">
                            <div class="col-12 m-0">
                                <label for="">1.Absent without notice (Alpha)</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->mkr }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->mkr_value }}</label>
                            </div>
                        </div>

                    </div>
                    <div class="row m-0">
                        <div class="col-md-6">
                            <div class="col-12 m-0">
                                <label for="">2.Absent with authorized (Izin)</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->ijin }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->ijin_value }}</label>
                            </div>
                        </div>

                    </div>
                    <div class="row m-0">
                        <div class="col-md-6">
                            <div class="col-12 m-0">
                                <label for="">3.Sick</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->sakit }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->sakit_value }}</label>

                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col-md-6">
                            <div class="col-12 m-0">
                                <label for="">4.Late</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->mdt }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->mdt_value }}</label>

                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col-md-6">
                            <div class="col-12 m-0">
                                <label for="">5. SP /Year</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->sp }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-12 m-0">
                                <label>{{ $pkinerja->sp_value }}</label>

                            </div>
                        </div>
                    </div>
                    <div>
                        <?php
                        $jumlah_dicipline = $pkinerja->mkr_value + $pkinerja->ijin_value + $pkinerja->sakit_value + $pkinerja->mdt_value + $pkinerja->sp_value;
                        ?>
                        @php
                            $jumlah_dicipline = ($jumlah_dicipline / 5) * 20; // Hitung $total_b sesuai dengan rumus yang diinginkan
                            $jumlah_dicipline = number_format($jumlah_dicipline, 1); // Memformat $total_b dengan satu angka di belakang koma
                        @endphp
                        <h5 class="bg-primary text-light fw-bold">SCORE : {{ $jumlah_dicipline }}</h5>
                    </div>

                </div>
                <div class="row mt-3">
                    <hr>
                    <label>B. COMPETENCE STANDARD :</label>
                    @php
                        $total_b = 0; // Inisialisasi $total_b sebagai nol
                    @endphp
                    <hr>
                    <div class="row">
                        <div class="col-md-9">PERFORMANCE FACTOR AND EXPLANATION</div>
                        <div class="col-md-3">
                            <div class="row m-0">
                                <div class="col">U1</div>
                                <div class="col">U2</div>
                                <div class="col">U3</div>
                                <div class="col">Jml</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @foreach ($pkinerja_b as $item)
                        <div class="col-md-12 mt-3">
                            <input type="text" class="form-control form-control-sm" placeholder=""
                                style="background-color: transparent; border:none;" value="{{ $item->perf_faktor }}"
                                name="perf_faktor[]" required placeholder="{{ $item->perf_faktor }}" readonly>
                        </div>
                        <div class="col-md-9 m-0">
                            <textarea name="penjelasan[]" style="text-align: justify;  border:hidden" cols="90" rows="4" readonly>{{ $item->penjelasan }}</textarea>
                        </div>
                        <div class="col-md-2 m-0">
                            <div class="row">
                                <div class="col">{{ $item->nilai1 }}</div>
                                <div class="col">{{ $item->nilai2 }}</div>
                                <div class="col">{{ $item->nilai3 }}</div>
                            </div>
                        </div>
                        <div class="col-md-1 m-0">
                            <?php
                            $jumlah_b = $item->nilai1 + $item->nilai2 + $item->nilai3;
                            $total_b += $jumlah_b; // Tambahkan $jumlah_b ke $total_b
                            ?>

                            <h5> {{ $jumlah_b }} </h5>
                        </div>
                    @endforeach

                    <div>
                        <h5 class="bg-primary text-light fw-bold">SCORE : {{ $pkinerja->score_b }}</h5>
                    </div>
                </div>
                <div class="row">
                    <hr>
                    <label>C. ASPEK PENILAIAN TEAM BY MANAGEMENT OBJECKTIVE</label>
                    <div class="row">

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
                        <hr>

                        @foreach ($kompeten as $item)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-12 mt-1">
                                        <label>{{ $item->penjelasan }}</label>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                    <h5 class="bg-primary text-light fw-bold">SCORE : {{ $pkinerja->score_c }}</h5>
                    <hr>
                    <label>D. TOTAL EVALUTION</label>

                    <div class="card m-2 border-primary">
                        <div class="row">
                            <div class="col-md-10">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Range</th>
                                            <th>Criteria</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>89 - 100</td>
                                            <td>Outstanding</td>
                                            <td>A</td>
                                        </tr>
                                        <tr>
                                            <td>90 - 97</td>
                                            <td>Good Performance</td>
                                            <td>B</td>
                                        </tr>
                                        <tr>
                                            <td>80 - 89</td>
                                            <td>Standard Performance</td>
                                            <td>C</td>
                                        </tr>
                                        <tr>
                                            <td>61 - 79</td>
                                            <td>Need Improvement</td>
                                            <td>D</td>
                                        </tr>
                                        <tr>
                                            <td>&lt; 60</td>
                                            <td>Unacceptable</td>
                                            <td>E</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div
                                class="col-md-2 bg-primary fw-bold text-light d-flex justify-content-center align-items-center">
                                <h3 class="text-light fw-bold">{{ $pkinerja->total_score }}</h3>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </section>

    @endsection
