
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10pt;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
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
<title>ReportICB</title>
<div class="body">
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:17%;">

    <h4 style="text-align: center">DATA ABSENSI KARYAWAN PT. EXTRUPACK
        TAHUN {{ $tahun }}</h4>

        <div>
            <div style="">Nama :  {{ $peg->nama_asli}} - {{ $peg->no_payroll }} </div>
        </div>
        
        @if (count($gabungData) > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Ambil Cuti</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gabungData as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data['tgl_absen'] }}</td>
                            <td>{{ $data['jns_absen'] }}</td>
                            <td>{{ $tahun }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data yang tersedia.</p>
        @endif
        
</div>
