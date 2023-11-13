

    <style>
        .table-bordered {
            border: 1px solid #000000;
        }
    
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000000;
        }
    </style>
    







    
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
        TAHUN {{ $thn }} - {{ $bln }}</h4> 

        <div>
            <div style="">Nama :  {{ $data->nama_asli}} - {{ $data->no_payroll }} </div>
        </div>
        
        @if (count($data_d) > 0)
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Kode Absen</th>
                        <th>Kelompok Absen</th>
                        <th>Ambil Cuti Thn</th>
                        <th>Keterangan</th>
                    </thead>
                    <tbody id="" style="font-size: 11pt;">
                        @foreach ($data_d as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->tgl_absen }}</td>
                                <td>{{ $item->jns_absen }}</td>
                                <td>{{ $item->dsc_absen }}</td>
                                <td>{{ $item->thn_jns }}</td>
                                <td>{{ $item->keterangan }}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data yang tersedia.</p>
        @endif
        
</div>
