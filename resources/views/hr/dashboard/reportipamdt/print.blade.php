
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
        TAHUN</h4>

        <div>
            <div style="">Nama :  {{ $peg->nama_asli}} - {{ $peg->no_payroll }} </div>
        </div>
        
        @if (count($absenDataL) > 0)
        <div >
            <div >
                <div style="height:450px;overflow:auto;">
                    <table id="myTable2">
                        <thead>
                            <tr style="font-size: 9pt; background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                                class="text-light">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Norm M</th>
                                <th>Norm K</th>
                                <th>Mnt IPA</th>
                                <th>Mnt DT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absenDataL as $absen)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y', strtotime($absen->tanggal)) }}</td>
                                    <td>{{ $absen->keterangan }}</td>
                                    <td>{{ $absen->masuk }}</td>
                                    <td>{{ $absen->keluar }}</td>
                                    <td>{{ $absen->norm_m }}</td>
                                    <td>{{ $absen->norm_k }}</td>
                                    <td>{{ $absen->mnt_dt }}</td>
                                    <td>{{ $absen->mnt_ipa }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6"></td>
                                <td>Jumlah Menit</td>
                                <td>{{ $jumlah_mnt_dt }}</td>
                                <td>{{ $jumlah_mnt_ipa }}</td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td>Jumlah Hari</td>
                                <td>{{ $jumlah_hari_dt }}</td>
                                <td>{{ $jumlah_hari_ipa }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    @else
        <p>Tidak ada data yang tersedia.</p>
    @endif
        
</div>
