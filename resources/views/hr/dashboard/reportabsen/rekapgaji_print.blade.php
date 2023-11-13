<div class="separator"></div>
<img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:20%;">

<h4 style="text-align: center">DATA REKAP GAJI</h4>
<p style="text-align: center">Periode  {{ $tgl_awal }} -
    {{ $tgl_akhir }} </p>
<div class="body">
    <div id="pegawaiDataContainer">

        <div class="card rounded-0 border-primary">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 50%;">Nama - NIK</th>
                        <th style="width: 10%;">Trn_B</th>
                        <th style="width: 10%;">Lembur</th>
                        <th style="width: 10%;">UML1</th>
                        <th style="width: 10%;">UML2</th>
                        <th style="width: 10%;">Shift2</th>
                        <th style="width: 10%;">Shift3</th>
                        <th style="width: 10%;">Snack Shift2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pegawaiData as $index => $data)
                        <tr>
                            <td style="line-height: 0.5;">{{ $data['pegawai']->no_payroll }} - {{ $data['pegawai']->nama_asli }}</td>
                            <td style="line-height: 0.5;">
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
                                {{ $jumlah_hari }}
                            </td>
                            <td style="line-height: 0.5;">{{ $data['total_lembur'] }}</td>
                            <td style="line-height: 0.5;">{{ $data['uml1'] }}</td>
                            <td style="line-height: 0.5;">{{ $data['uml2'] }}</td>
                            <td style="line-height: 0.5;">{{ $data['shift2'] }}</td>
                            <td style="line-height: 0.5;">{{ $data['shift3'] }}</td>
                            <td style="line-height: 0.5;">{{ $data['shift2'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        
        
    </div>
</div>
<style>
    .body {
        font-size: 7;
    }

    /* Gaya umum */
    .pegawaiData {
        margin-top: 0px;
    }

    .section {
        margin-bottom: 0px;
        padding-left: 14px;
        padding-right: 10px;
    }

    .row {
        margin-bottom: 10px;
    }

    .text-peg {
        margin-bottom: 5px;
    }

    .card {
        padding-left: 30px;
        padding-right: 30px;
        background-color: #ffffff;
        border-radius: 5px;
        margin-bottom: 20px;
        margin-top: 0px;    }

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
