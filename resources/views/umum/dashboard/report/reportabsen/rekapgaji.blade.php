@extends('umum.dashboard.layout.layout')

@section('content')

    <div>




        <div class="separator"></div>

        <div id="pegawaiDataContainer">
            @foreach ($pegawaiData as $index => $data)
                <div id="pegawaiData-{{ $index }}" class="mt-2 {{ $index > 0 ? 'hidden' : '' }}">

                    <div class="mt-2">
                        <div class="section text-light fw-bold">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row text-peg">
                                        <div class="col-sm-2">NIK</div>
                                        <div class="col-sm-10">: {{ $data['pegawai']->no_payroll }}</div>
                                    </div>
                                    <div class="row text-peg">
                                        <div class="col-sm-2">Nama</div>
                                        <div class="col-sm-10">: {{ $data['pegawai']->nama_asli }} -
                                            {{ $data['pegawai']->bagian }}
                                        </div>
                                    </div>
                                    <div class="row text-peg">
                                        <div class="col-sm-2">Periode</div>
                                        <div class="col-sm-10">: {{ $tgl_awal }} - {{ $tgl_akhir }}</div>
                                    </div>
                                </div>
                         
                                <div class="col-md-1">
                                    @php
                                        $golongan = $data['pegawai']->golongan;
                                        $noPayroll = intval($data['pegawai']->no_payroll);
                                        $jumlah_hari = 0;
                                        
                                        if (in_array($golongan, ['A', 'B', 'C', 'D', 'E', ' ', null]) && $noPayroll < 570) {
                                            $jumlah_hari = $data['jumlah_hari'];
                                        } else {
                                            $jumlah_hari = 0;
                                        }
                                    @endphp
                                    </div>
                                         <div class="col-md-5">
                                
                                            <a target="_Blank"  href="/umum/dashboard/report/reportabsen/rekapgaji_print?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&no_payroll={{ $data['pegawai']->no_payroll }}">
                                                <button class="btn btn-sm btn-success">Print Rekap Gaji</button>
                                            </a>
        
                                        </div>
                                            

                             
                            </div>
                        </div>
                    </div>

                    <div class="card rounded-0 border-primary">
                        <div class="row">
                            <?php
                            $absen = $data['absen']; // Simpan data absen dalam variabel terpisah
                            $absenChunks = $absen->isNotEmpty() ? array_chunk($absen->toArray(), ceil($absen->count())) : [];
                            $totalChunks = count($absenChunks);
                            ?>
                            @if ($absenChunks)
                                @foreach ($absenChunks as $index => $absenChunk)
                                    <div class="column">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Trn_B</th>
                                                    <th>Lembur</th>
                                                    <th>UML1</th>
                                                    <th>UML2</th>
                                                    <th>Shift2</th>
                                                    <th>Shift3</th>
                                                    <th>Snack Shift2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td>  {{ $jumlah_hari }}</td>
                                                <td>  {{ $total_lembur }}</td>
                                                <td>  {{ $uml1 }}</td>
                                                <td>  {{ $uml2 }}</td>
                                                <td>  {{ $shift2 }}</td>
                                                <td>  {{ $shift3 }}</td>
                                                <td>  {{ $shift2 }}</td>
                                              </tr>
                                            </tbody>
                                        </table>
                                        @if ($index < $totalChunks - 1)
                                            <hr> <!-- Tambahkan garis pembatas antara array_chunk -->
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="column">
                                    <p>Tidak ada data absen.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                </div>
        </div>
        @endforeach
    </div>




   
    <style>
        .hidden {
            display: none;
        }

        .row {
            display: flex;
            background-color: rgb(0, 132, 255);
        }

        .column {
            flex: 1;
            padding: 0 10px;
            background-color: white;
        }

        table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 10px;
            /* Adjust the margin as needed */
        }

        .text-peg {
            font-size: 13px;
        }

        .separator {
            margin-top: 20px;
            /* Adjust the margin as needed */
            border-top: 1px solid #ddd;
        }
    </style>
@endsection
