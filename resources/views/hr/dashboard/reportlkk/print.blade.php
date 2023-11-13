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
<title>Report TL AK</title>
<div class="body">
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width: 17%;">

    <h5 class="text-center m-0">KARYAWAN MASUK KERJA PADA  {{ date('d-m-Y', strtotime($tanggal)) }} </h5>


    @if (count($data) > 0)
    <div style="margin: 0;">
        <div >
            <table id="myTable2" style="font-size: 10pt;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bagian</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $absen)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $absen->bagian }}</td>
                        <td>{{ $absen->jumlah }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-center"><b>Total</b></td>
                        <td><b>{{ $total }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p>Tidak ada data yang tersedia.</p>
    @endif

</div>
