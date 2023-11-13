<div class="separator"></div>
<img src="{{ public_path ("/image/logos/logo-ext.png") }}" alt="" style="width:20%;">

<h4 style="text-align: center">DATA URAIAN LEMBUR</h4>
<div class="body">
    <div id="pegawaiDataContainer" class="pegawaiData">
        @foreach ($pegawaiData as $index => $data)
            @php
                $total_lembur = $data['total_lembur'];
            @endphp
            <div id="pegawaiData-{{ $index }}" class="{{ $index > 0 ? 'hidden' : '' }}">
                <div class="section">
                    <table style="border-collapse: collapse; border: none;">
                        <tr style="margin-bottom: 1px;">
                            <td style="padding: 1px; border: none;">NIK</td>
                            <td style="padding: 1px; border: none;">: {{ $data['pegawai']->no_payroll }}</td>
                        </tr>
                        <tr style="margin-bottom: 1px;">
                            <td style="padding: 1px; border: none;">Nama</td>
                            <td style="padding: 1px; border: none;">: {{ $data['pegawai']->nama_asli }} - {{ $data['pegawai']->bagian }}</td>
                        </tr>
                        <tr style="margin-bottom: 1px;">
                            <td style="padding: 1px; border: none;">Periode</td>
                            <td style="padding: 1px; border: none;">: {{ $tgl_awal }} - {{ $tgl_akhir }}</td>
                        </tr>
                    </table>
                </div>
                

                <div class="card" >
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th style="text-align: center;">Tgl</th>
                                <th style="text-align: center;">Presensi Masuk</th>
                                <th style="text-align: center;">Presensi Keluar</th>
                                <th style="text-align: center;">Lembur Start</th>
                                <th style="text-align: center;">Lembur End</th>
                                <th style="text-align: center;">Jam Lembur</th>
                                <th style="text-align: center;">Jumlah Lembur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($data['absen'] as $item)
                            @if (!empty($item['lembur']) && !empty($item['start']) && !empty($item['end']))
                            <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item['tanggal'])) }}</td>
                                        <td>{{ $item['masuk'] }}</td>
                                        <td>{{ $item['keluar'] }}</td>
                                        <td>{{ $item['start'] }}</td>
                                        <td>{{ $item['end'] }}</td>
                                        <td>{{ $item['lembur'] }}</td>
                                        <td>{{ $item['lembur_total'] }}</td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="7" style="text-align: right;"><strong>Total Jumlah Lembur</strong></td>
                                <td><b>{{ $total_lembur }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .body{
        font-size: 9;
    }
    /* Gaya umum */
    .pegawaiData {
        margin-top: 0px;
    }

    .section {
        margin-bottom: 10px;
        padding-left: 30px;
        padding-right: 30px;
    }

    .card {
        padding-left: 30px;
        padding-right: 30px;
        background-color: #ffffff;
        border-radius: 5px;
        margin-bottom: 20px;
        margin-top: 0px;
    }

    /* Gaya tabel */
    table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border: 1px solid black;
        color: black;
    }

    th {
        background-color: rgb(255, 255, 255);
        color: rgb(0, 0, 0);
        font-weight: bold;
    }
</style>
