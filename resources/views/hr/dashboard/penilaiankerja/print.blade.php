<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-size: 9pt;


        }

        .column {
            display: inline-block;
            /* width: 15%; Atur lebar kolom sesuai kebutuhan Anda */
            border: none;
            padding: 10px;
            text-align: left;
            box-sizing: border-box;
            /* Membuat padding termasuk dalam lebar */
        }

        .rm {
            display: inline-block;
            /* width: 15%; Atur lebar kolom sesuai kebutuhan Anda */
            border: none;
            padding: 3px;
            text-align: left;
            box-sizing: border-box;
            /* Membuat padding termasuk dalam lebar */
        }

        .dkriteria {
            display: inline-block;
            width: 33%;
            /* Adjust column width as needed */
            border: none;
            padding: 0px;
            text-align: left;
            box-sizing: border-box;
            vertical-align: top;
            /* Align columns at the top */
        }

        .bcd {
            display: inline;
            /* width: 15%; Atur lebar kolom sesuai kebutuhan Anda */
            border: none;
            text-align: left;
            box-sizing: border-box;
            /* Membuat padding termasuk dalam lebar */
            vertical-align: top;
            /* Menyelaraskan konten dengan bagian atas sel */
        }


        .column1 {
            display: inline-block;
            width: 25%;
            /* border: 1px solid #000; Menambahkan border dengan ketebalan 1px dan warna hitam (#000) */
            padding: 4px;
            text-align: left;
            box-sizing: border-box;
            font-size: 12px;
            margin: 0;
            height: 70px;
        }

        /* Mengatur margin atas dan bawah pada <p> dan <h5> menjadi 0 */
        .column1 p,
        .column1 h4 {
            margin: 0;
        }


        /* table */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 1px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            /* Optional: Add background color to header row */
        }

        .rating-box {
            border: 1px solid #000;
            /* Garis pinggir kotak */
            padding: 2px 1px;
            /* Jarak antara angka dan pinggir kotak */
            border-radius: 5px;
            /* Sudut kotak yang dibulatkan */
        }

        .jdl {
            background-color: #f2f2f2;
            /* Optional: Add background color to header row */
        }
    </style>
</head>

<body>
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:20%; margin:0%;">
    @if ($peg->foto)
        <img src="{{ public_path('/image/fotos/' . $peg->foto) }}" alt=""
            style="width:4cm; position: absolute; top: 0; right: 0;">
    @endif
    <div style="text-align: center">
        <h2 style="margin: 0%;">PERFORMANCE APPRASIAL</h2>
        <p style="margin: 0%;">Period : {{ \Carbon\Carbon::parse($pkinerja->awal)->format('d-m-Y') }} -
            {{ \Carbon\Carbon::parse($pkinerja->akhir)->format('d-m-Y') }}</p>
    </div>
    <div style="margin-top: 5%">
        <span class="column ">
            Name <br> Empl. No <br>Position / Grade <br>Dept / Div
        </span>
        <span class="column"> : {{ $pkinerja->nama }} <br> : {{ $pkinerja->no_payroll }} <br> : {{ $pkinerja->bagian }}
            <br>: {{ $pkinerja->jabatan }} </span>
        <span class="column"></span>
        <span class="column">Date Of Assignment <br> Diring Date <br> Contract Period <br> Status</span>
        <span class="column"> : . . . . . . . . . . . . . . . . . . <br> : {{ $peg->tgl_masuk }} <br> :
            {{ $kontrak_period }} <br> :
            {{ $peg->jns_peg }}</span>
    </div>
    <div style="border: 1px solid black; padding: 10px; font-size: 12pt; font-size:12pt;">

        <span class="column " style="width: 50%;  padding:0%;">
            <label style="width: 100px; display: inline-block;">Review :</label>
            <input type="checkbox" checked id="permanent" name="review" value="Permanent"
                style="vertical-align: middle;">
            <label for="permanent" style="vertical-align: middle;">{{ $pkinerja->review }}</label>
        </span>
        <span class="column" style="border: 1px solid black; padding:1pt; font-weight:bold;">
            Total Score : {{ $pkinerja->total_score }} </span>


        <hr style="margin: 0%;">
        <div>
            <div class="column">
                <p>I. CATATAN : {{ $pkinerja->remark }}</p>

            </div>
        </div>
    </div>



    <br>
    <div>
        <table>
            <tr>
                <th colspan="3">A. DISCIPLINE :</th>
            </tr>
            <tr>
                <td style="text-align: center;"> ATTENDANCE EVALUATION</td>
                <td style="text-align: center;">DAYS</td>
                <td style="text-align: center;">SCORE</td>
            </tr>
            <tr>
                <td>1. Absent without notice (Alpha)</td>
                <td style="text-align: center;">{{ $pkinerja->mkr }}</td>
                <td style="text-align: center;">{{ $pkinerja->mkr_value }}</td>
            </tr>
            <tr>
                <td>2. Absent with authorized (Izin)</td>
                <td style="text-align: center;">{{ $pkinerja->ijin }}</td>
                <td style="text-align: center;">{{ $pkinerja->ijin_value }}</td>
            </tr>
            <tr>
                <td>3. Sick</td>
                <td style="text-align: center;">{{ $pkinerja->sakit }}</td>
                <td style="text-align: center;">{{ $pkinerja->sakit_value }}</td>
            </tr>
            <tr>
                <td>4. Late</td>
                <td style="text-align: center;">{{ $pkinerja->mdt }}</td>
                <td style="text-align: center;">{{ $pkinerja->mdt_value }}</td>
            </tr>
            <tr>
                <td>5. SP /Year</td>
                <td style="text-align: center;">{{ $pkinerja->sp }}</td>
                <td style="text-align: center;">{{ $pkinerja->sp_value }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                    $jumlah_dicipline = $pkinerja->mkr_value + $pkinerja->ijin_value + $pkinerja->sakit_value + $pkinerja->mdt_value + $pkinerja->sp_value;
                    ?>
                    @php
                        $jumlah_dicipline = ($jumlah_dicipline / 5) * 20; // Hitung $total_b sesuai dengan rumus yang diinginkan
                        $jumlah_dicipline = number_format($jumlah_dicipline, 1); // Memformat $total_b dengan satu angka di belakang koma
                    @endphp
                    <h3 style="margin: 0%;"> Total Score / 5 x 20 </h3>
                </td>
                <td>
                    <div class="colum">
                        <h3 class="bg-primary text-light fw-bold " style="margin: 1%; padding: 0; text-align: center;">
                            SCORE :
                            {{ $jumlah_dicipline }}</h3>
                    </div>
                </td>

            </tr>
        </table>
    </div>
    <br>


    <div>
        @php
            $total_b = 0; // Inisialisasi $total_b sebagai nol
        @endphp
        <table border="1">
            <thead>
                <tr>
                    <th colspan="5">B. COMPETENCE STANDARD :</th>
                </tr>
                <tr>
                    <th colspan="5">Kriteria Penilaian : <span class="rating-box"> 1.Rendah | 2.Kurang | 3.Cukup |
                            4.Baik | 5.Sangat Baik </span></th>
                </tr>
                <tr>
                    <th style="text-align: center;">PERFORMANCE FACTORS</th>
                    <th style="text-align: center;">EXPLANATION</th>
                    <th style="text-align: center;">U1</th>
                    <th style="text-align: center;">U2</th>
                    <th style="text-align: center;">U3</th>
                </tr>
            </thead>
            <tbody style="font-size: 10pt;">
                @php
                    $groupedData = [];
                    $total_b_group = 0;
                @endphp
                @foreach ($pkinerja_b as $item)
                    @php
                        $groupedData[$item->perf_faktor][] = $item;
                        $total_b_group += $item->nilai1 + $item->nilai2 + $item->nilai3;
                    @endphp
                @endforeach

                @foreach ($groupedData as $key => $group)
                    @foreach ($group as $item)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ count($group) }}">
                                    <div class="bcd">{{ $loop->parent->iteration }} .</div>
                                    <div class="bcd">{{ $item->perf_faktor }}</div>
                                </td>
                            @endif
                            <td style="text-align: justify;">{{ $item->penjelasan }}</td>
                            <td style="text-align: center;">{{ $item->nilai1 }}</td>
                            <td style="text-align: center;">{{ $item->nilai2 }}</td>
                            <td style="text-align: center;">{{ $item->nilai3 }}</td>

                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="">
                        <h3 style="margin: 0%;">TOTAL SCORE / 14 x 20</h3>
                    </td>
                    <td colspan="4" style="text-align: center;">
                        <h3 class="column" style="margin: 0%; text-align:center;">
                            SCORE : {{ $pkinerja->score_b }}</h3>

                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <br>
    <div style="margin-bottom: 2%;">

        <table>
            <thead>
                <th colspan="2">C. ASPEK PENILAIAN TEAM BY MANAGEMENT OBJECTIVE</th>
                <tr>
                    <th style="text-align: center;">Major Job</th>
                    <th style="text-align: center;">Managament</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kompeten as $item)
                    <tr>
                        <td>{{ $item->penjelasan }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td><b>MAXIMUM SCORE (1-4) = 100 </b></td>
                    <td>
                        <h3 class="bg-primary text-light fw-bold " style="text-align: center; margin:1%"> SCORE
                            :{{ $pkinerja->score_c }}</h3>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <br>
    @php
        $criteria = '';
        $grade = '';

        $total_score = $pkinerja->total_score;

        if ($total_score > 97 && $total_score <= 100) {
            $criteria = 'Outstanding';
            $grade = 'A';
        } elseif ($total_score > 89 && $total_score <= 97) {
            $criteria = 'Good Performance';
            $grade = 'B';
        } elseif ($total_score > 79 && $total_score <= 89) {
            $criteria = 'Standard Performance';
            $grade = 'C';
        } elseif ($total_score > 61 && $total_score <= 79) {
            $criteria = 'Need Improvement';
            $grade = 'D';
        } elseif ($total_score > 100) {
            $criteria = 'Ada yg salah , tidak boleh lebih dari 100 !!';
            $grade = 'Z';
        } else {
            $criteria = 'Unacceptable';
            $grade = 'E';
        }
    @endphp

    <div class="COL">
        <div class="jdl" colspan="2" style="font-weight: italic;  font-size:8pt;">
            Note : <br> 98-100 = Outstanding (A) | 90-97 = Good Performance (B) | 80-89 = Standard Performance (C) |
            61-79 = Need Improvement (D) | < 60=Unaceptable (E). </div>
                <div class="dkriteria">

                    <table>
                        <thead>
                            <th colspan="2">D. TOTAL EVALUATION</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Score</th>

                                <td style="text-align:center; font-size:13pt; font-weight:bold;">
                                    {{ $pkinerja->total_score }}
                                </td>
                            </tr>
                            <tr>
                                <th>Grade</th>
                                <td style="text-align:center; font-size:13pt; font-weight:bold;"> {{ $grade }}
                                </td>
                            </tr>
                            <tr>
                                <th>Criteria</th>
                                <td style="text-align:center; font-size:13pt; font-weight:bold;"> {{ $criteria }}
                                </td>

                            </tr>
                            <tr>
                                <th>Note:</th>
                                <td style="width: 120; height: 75;"></td>
                                <!-- Sesuaikan lebar dan tinggi sesuai kebutuhan -->
                            </tr>

                        </tbody>
                    </table>

                </div>
                <div class="dkriteria">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: center;">Recommendation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 0.6%;">* Permanent</td>
                                <td><!-- Second column content goes here --></td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6%;">* Terminated</td>
                                <td><!-- Second column content goes here --></td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6%;">* Extend Contract</td>
                                <td><!-- Second column content goes here --></td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6%;">* Promote to grade</td>
                                <td><!-- Second column content goes here --></td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6%;">* Promote to position</td>
                                <td><!-- Second column content goes here --></td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6%;">* Down to Grade</td>
                                <td><!-- Second column content goes here --></td>
                            </tr>
                            <tr>
                                <td style="padding: 0.6%;">* Down to position</td>
                                <td><!-- Second column content goes here --></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="dkriteria">
                    <table>
                        <thead>
                            <th colspan="2" style="text-align: center;">Remarks</th>
                        </thead>


                        <tr>
                            <td>
                                <span class="rm">- Basic Salary</span>
                            </td>
                            <td>
                                <span class="rm"> : Rp. . . . . . . . . ,-</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="rm">- Allowance Salary</span>
                            </td>
                            <td>
                                <span class="rm"> : Rp. . . . . . . . . ,-</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="rm"> <b>- Current Salary / Total </b></span>
                            </td>
                            <td>
                                <span class="rm"> : Rp. . . . . . . . . ,-</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="rm">- Basic Salary</span>
                            </td>
                            <td>
                                <span class="rm"> : Rp. . . . . . . . . ,-</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="rm">- Allowance Salary</span>
                            </td>
                            <td>
                                <span class="rm"> : Rp. . . . . . . . . ,-</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <span class="rm"> <b> New Salary </b></span>
                            </th>
                            <th>
                                <span class="rm"> : Rp. . . . . . . . . ,-</span>
                            </th>
                        </tr>
                        <tr>
                            <th>Note:</th>
                            <td style="width: ;  height: 30px;"></td>
                            <!-- Sesuaikan lebar dan tinggi sesuai kebutuhan -->
                        </tr>



                    </table>
                </div>
        </div>
        <hr>


        <br>
        <div style="margin-top: 3%; font-size:10pt;">
            <span class="column1" style="width:23%;">
                <p>Reviewed By</p>
                <h4>Departement Manager,</h4>
            </span>
            <span class="column1" style="width:23%;">
                <p>Reviewed By</p>
                <h4>HR&GA Manager,</h4>
            </span>
            <span class="column1" style="width:23%;">
                <p>Approved By</p>
                <h4>General Manager / Director,</h4>
            </span>
            <span class="column1" style="width:23%;">
                <p>Recived By</p>
                <h4>Employe,</h4>
            </span>
        </div>
        <div style="margin-top: 5%;">
            <span class="column1" style="width:23%;">
                <h4>Date</h4>
            </span>
            <span class="column1" style="width:23%;">
                <h4>Date</h4>
            </span>
            <span class="column1" style="width:23%;">
                <h4>Date</h4>
            </span>
            <span class="column1" style="width:23%;">
                <h4>Date</h4>
            </span>
        </div>




</body>

</html>
