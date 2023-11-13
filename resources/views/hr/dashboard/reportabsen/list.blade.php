@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="bg-light">
        <button class="btn btn-md m-0 border-0 bg-transparent shadow-none" id="prevDataBtn" disabled><i
                class="fas text-primary fa-arrow-left"></i></button>
        <button class="btn btn-md m-0 border-0 bg-transparent shadow-none" id="nextDataBtn"><i
                class="fas text-primary fa-arrow-right"></i></button>

    </div>
    <div>
        @if (empty($no_payroll))
            <div style="display: flex; gap: 10px;" class="my-1">
                <h5 class="text-light fw-bold">PRINT SEMUA DATA:</h5>
                <a target="_blank" href="/hr/dashboard/reportabsen/list_print?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&no_payroll={{ $no_payroll }}">
                    <button class="btn btn-sm btn-warning m-0">Detail</button>
                </a>
                <a target="_blank" href="/hr/dashboard/reportabsen/uraianlembur_print?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&no_payroll={{ $no_payroll }}">
                    <button class="btn btn-sm btn-warning m-0">Lembur</button>
                </a>
                <a target="_blank" href="/hr/dashboard/reportabsen/rekapgaji_print?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&no_payroll={{ $no_payroll }}">
                    <button class="btn btn-sm btn-warning m-0">Gaji</button>
                </a>
            </div>
        @endif
    </div>
    
    <div>

        <div>
            <form action="{{ url('/hr/dashboard/reportabsen/list') }}">
                <div class="card rounded-0 border-primary">

                    <div class="row bg-light">

                        <div class="col-md-3">
                            <label for="tgl_awal">Tanggal Awal:</label>
                            <input class="form-control form-control-sm rounded-0" type="date" id="tgl_awal"
                                name="tgl_awal" value="{{ isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tgl_akhir">Tanggal Akhir:</label>
                            <input class="form-control form-control-sm rounded-0" type="date" id="tgl_akhir"
                                name="tgl_akhir"
                                value="{{ isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="no_payroll">No. Payroll:</label>
                            <input class="form-control form-control-sm rounded-0" type="text" id="no_payroll"
                                name="no_payroll">
                        </div>
                        <div class="col-md-2">
                            <input class="btn btn-sm rounded-0 mt-4" type="submit" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>


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
                                <div class="col-md-3">
                                    @php
                                    $golongan = $data['pegawai']->golongan;
                                    $golongan = trim($golongan);
                                    $noPayroll = intval($data['pegawai']->no_payroll);
                                    $jumlah_hari = 0;
                                
                                    if (in_array($golongan, ['A', 'B', 'C', 'D', 'E', '',]) && $noPayroll < 570) {
                                        $jumlah_hari = $data['jumlah_hari'];
                                    } else {
                                        $jumlah_hari = 0;
                                    }
                                @endphp
                                

                                    <div class="row text-peg">
                                        <div class="col-sm-8">Jumlah Hari / Transport</div>
                                        <div class="col-sm-4">: {{ $jumlah_hari }} Hari</div>
                                    </div>
                                    <div class="row text-peg">
                                        <div class="col-sm-8">Jumlah Lembur</div>
                                        <div class="col-sm-4">:
                                            {{ $data['total_lembur'] }} </div>
                                    </div>
                                    <div class="row text-peg">
                                        <div class="col-sm-8">UML1</div>
                                        <div class="col-sm-4">:
                                            {{ $data['uml1'] }} Hari</div>
                                    </div>
                                    <div class="row text-peg">
                                        <div class="col-sm-8">UML2</div>
                                        <div class="col-sm-4">:
                                            {{ $data['uml2'] }} Hari</div>
                                    </div>

                                </div>
                                @if (!in_array($data['pegawai']->departemen, ['KEUANGAN', 'MARKETING', 'IMPORT/PEMBELIAN', 'DIREKSI', 'HRD']))
                                    <div class="col-md-2">
                                        <div class="row text-peg">
                                            <div class="col-sm-7">Shift 2</div>
                                            <div class="col-sm-5">:
                                                {{ $data['shift2'] }} Hari</div>
                                        </div>
                                        <div class="row text-peg">
                                            <div class="col-sm-7">Shift 3</div>
                                            <div class="col-sm-5">:
                                                {{ $data['shift3'] }} Hari</div>
                                        </div>
                                        <div class="row text-peg">
                                            <div class="col-sm-7">Snack Shift 2</div>
                                            <div class="col-sm-5">:
                                                {{ $data['shift2'] }} Hari</div>
                                        </div>
                                    </div>
                                @endif


                                <div class="col-md-3">
                                    <a target="_Blank"
                                        href="/hr/dashboard/reportabsen/list_print?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&no_payroll={{ $data['pegawai']->no_payroll }}">
                                        <button class="btn btn-sm btn-success">Detail </button>
                                    </a>
                                    <a
                                        href="/hr/dashboard/reportabsen/uraianlembur?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&no_payroll={{ $data['pegawai']->no_payroll }}">
                                        <button class="btn btn-sm btn-success">Lembur</button>
                                    </a>
                                    <a
                                        href="/hr/dashboard/reportabsen/rekapgaji?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&no_payroll={{ $data['pegawai']->no_payroll }}">
                                        <button class="btn btn-sm btn-success">Gaji</button>
                                    </a>

                                </div>
                            </div>
                        </div>

                        <div class="card rounded-0 border-primary">
                            <div class="row">
                                <?php
                                $absen = $data['absen']; // Simpan data absen dalam variabel terpisah
                                $absenChunks = $absen->isNotEmpty() ? array_chunk($absen->toArray(), ceil($absen->count() / 3)) : [];
                                $totalChunks = count($absenChunks);
                                ?>
                                @if ($absenChunks)
                                    @foreach ($absenChunks as $index => $absenChunk)
                                        <div class="column">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Tgl</th>
                                                        <th>Masuk</th>
                                                        <th>Keluar</th>
                                                        <th>Lembur</th>
                                                        <th>Ket</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($absenChunk as $item)
                                                        @php
                                                            $ket = isset($item['ket']) ? $item['ket'] : '';
                                                            $dayOfWeek = date('N', strtotime($item['tanggal'])); // Mendapatkan hari dalam bentuk angka (1-7)
                                                        @endphp
                                                        <tr
                                                            @if ($dayOfWeek == 6 || $dayOfWeek == 7) style="color:rgb(255, 1, 1);" @endif>
                                                            <td>{{ date('d-m-Y', strtotime($item['tanggal'])) }}</td>
                                                            <td>{{ $item['masuk'] }}</td>
                                                            <td>{{ $item['keluar'] }}</td>
                                                            <td>
                                                                @if ($item['lembur'] <= 0)
                                                                    {{-- Tampilkan kolom kosong --}}
                                                                @else
                                                                    {{ $item['lembur'] }}
                                                                @endif
                                                            </td>                                                            <td>{{ $ket }}</td>
                                                        </tr>
                                                    @endforeach
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




        <script>
            const prevDataBtn = document.getElementById('prevDataBtn');
            const nextDataBtn = document.getElementById('nextDataBtn');
            const pegawaiDataContainer = document.getElementById('pegawaiDataContainer');
            let currentIndex = 0;

            function updateButtonState() {
                prevDataBtn.disabled = currentIndex === 0;
                nextDataBtn.disabled = currentIndex === {{ count($pegawaiData) - 1 }};
            }

            function showCurrentData() {
                const dataElements = pegawaiDataContainer.children;
                for (let i = 0; i < dataElements.length; i++) {
                    const dataElement = dataElements[i];
                    if (i === currentIndex) {
                        dataElement.classList.remove('hidden');
                    } else {
                        dataElement.classList.add('hidden');
                    }
                }
            }

            prevDataBtn.addEventListener('click', function() {
                currentIndex--;
                showCurrentData();
                updateButtonState();
            });

            nextDataBtn.addEventListener('click', function() {
                currentIndex++;
                showCurrentData();
                updateButtonState();
            });

            updateButtonState();
        </script>
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
