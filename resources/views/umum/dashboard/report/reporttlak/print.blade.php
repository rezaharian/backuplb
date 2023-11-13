<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10pt;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 3px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
<title>Report TL AK LKTMH</title>
<div class="body">
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width: 17%;">

    <h5 style="text-align: center; margin: 0;">{{ $jns }}</h5>
    <p style="text-align: center; margin: 0;">{{ $tgl_awal }} - {{ $tgl_akhir }}</p>

    @if (count($absentlak) > 0)
        <div style="margin: 0;">
            <div>
                <table id="myTable2" style="font-size: 8pt;" border="1">
                    @if ($jns == 'LAPORAN KARYAWAN TIDKA MASUK HARIAN')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $prevNoPayroll = null;
                            $groupIteration = 1;
                        @endphp
                
                        @foreach ($absentlak as $absen)
                            @if ($absen['no_payroll'] != $prevNoPayroll)
                                @if ($prevNoPayroll !== null)
                                    </tr>
                                @endif
                
                                <tr>
                                    <td colspan="7" class="fw-bold"><b>No Payroll: {{ $absen['no_payroll'] }}</b></td>
                                </tr>
                                @php
                                    $prevNoPayroll = $absen['no_payroll'];
                                    $groupIteration = 1;
                                @endphp
                            @endif
                
                            <tr>
                                <td>{{ $groupIteration }}</td>
                                <td>{{ date('d-m-Y', strtotime($absen['tanggal'])) }}</td>
                                <td>{{ $absen['nama_asli'] }}</td>
                                <td>{{ $absen['bagian'] }}</td>
                                <td>{{ $absen['masuk'] ?? '-' }}</td>
                                <td>{{ $absen['keluar'] ?? '-' }}</td>
                                <td>{{ $absen['dsc_absen'] ?? '-' }}</td>
                            </tr>
                
                            @php
                                $groupIteration++;
                            @endphp
                
                        @endforeach
                        </tr>
                    </tbody>
                    @else
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No Payroll</th>
                                <th>Nama</th>
                                <th>Bagian</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absentlak as $absen)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y', strtotime($absen['tanggal'])) }}</td>
                                    <td>{{ $absen['no_payroll'] }}</td>
                                    <td>{{ $absen['nama_asli'] }}</td>
                                    <td>{{ $absen['bagian'] }}</td>
                                    <td>{{ $absen['masuk'] ?? '-' }}</td>
                                    <td>{{ $absen['keluar'] ?? '-' }}</td>
                                    <td>{{ $absen['dsc_absen'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    @endif

                </table>
            </div>
        </div>
    @else
        <p>Tidak ada data yang tersedia.</p>
    @endif

</div>
