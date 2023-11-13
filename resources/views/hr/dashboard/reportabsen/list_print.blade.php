<div class="body">
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:17%;">

    <h4 style="text-align: center">DATA DETAIL ABSENSI</h4>
    <div id="pegawaiDataContainer">
        @foreach ($pegawaiData as $index => $data)
            <div id="pegawaiData-{{ $index }}" class="mt-2 {{ $index > 0 ? 'hidden' : '' }}">
                <div class="mt-0">
                    <div class="section text-light fw-bold">
                        <div class="column">
                            <div><p>---</p></div>
                            <p>NIK :{{ $data['pegawai']->no_payroll }} | Nama : {{ $data['pegawai']->nama_asli }} -
                                {{ $data['pegawai']->bagian }}

                                | Periode : {{ $tgl_awal }} - {{ $tgl_akhir }}</p>

                            <p>Jumlah Hari / Transport: {{ $data['jumlah_hari'] }} Hari | Jumlah Lembur :
                                {{ $data['total_lembur'] }}
                                | UML1:   {{ $data['uml1'] }} | HariUML2:  {{ $data['uml2'] }} Hari</p>

                            @if (!in_array($data['pegawai']->departemen, ['KEUANGAN', 'MARKETING', 'IMPORT/PEMBELIAN', 'DIREKSI', 'HRD']))
                                <p>Shift 2 :   {{ $data['shift2'] }} Hari | Shift 3 :   {{ $data['shift3'] }} Hari | Snack
                                    Shift 2 :   {{ $data['shift2'] }} Hari</p>
                            @endif
                        </div>
                      

                    </div>
            
                    </div>
                </div>


                <div class="card rounded-0 border-primary">
                    <?php
                    $absen = $data['absen']; // Simpan data absen dalam variabel terpisah
                    $absenChunks = $absen->isNotEmpty() ? array_chunk($absen->toArray(), ceil($absen->count() / 3)) : [];
                    $totalChunks = count($absenChunks);
                    $nomorUrut = 1; // Inisialisasi nomor urut
                    ?>

                    @if ($absenChunks)
                        @foreach ($absenChunks as $index => $absenChunk)
                            <table class="table" style="margin-right: 1%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
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
                                        <tr @if ($dayOfWeek == 6 || $dayOfWeek == 7) style="color:rgb(255, 1, 1);" @endif>
                                            <td>{{ $nomorUrut }}</td> <!-- Menggunakan variabel nomorUrut -->
                                            <td>{{ date('d-m-Y', strtotime($item['tanggal'])) }}</td>
                                            <td>{{ $item['masuk'] }}</td>
                                            <td>{{ $item['keluar'] }}</td>
                                            <td>
                                                @if ($item['lembur'] <= 0)
                                                {{-- Tampilkan kolom kosong --}}
                                                @else
                                                    {{ $item['lembur'] }}
                                                @endif
                                            </td>                                     
                                            <td>{{ $ket }}</td>
                                        </tr>
                                        <?php $nomorUrut++; ?>
                                        <!-- Menambahkan nomor urut setiap kali iterasi -->
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($index < $totalChunks - 1)
                            @endif
                        @endforeach
                    @else
                        <p>Tidak ada data absen.</p>
                    @endif

                </div>
        @endforeach
    </div>



    <style>
        .body {
            font-size: 8pt;
        }

        .card table {
            margin-bottom: 20px;
            float: left;

        }

        /* Gaya tabel */
        table {
            margin-top: 2%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 4px;
            text-align: left;

        }

        th {
            background-color: rgb(174, 232, 251);
            color: rgb(0, 0, 0);
            font-weight: bold;


        }

        /* sdsd */


        .data-label {
            flex: 1;
            font-weight: bold;
        }

        .data-value {}
    </style>
