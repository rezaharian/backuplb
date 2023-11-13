<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
        font-size: 12px;
    }

    th {
        background-color: lightgray;
    }
</style>

<strong style="font-size:14pt; margin:3px; margin-bottom:10px; ">
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:14%;">
    <div style="text-align:center;">
        LAPORAN FPK3 @if ($jenis)
            JENIS {{ strtoupper($jenis) }}
        @else
            SEMUA JENIS
        @endif
        <br> <span style="font-size:12pt;">PERIODE</span> {{ $periodeAwal }} - {{ $periodeAkhir }}
    </strong>
</div>  
<br>

<body>
    <table>
        <tr>
            <th>No</th>
            <th>No. Urut</th>
            <th>Pengaju</th>
            <th>Bagian</th>
            <th>Tgl. Pengajuan</th>
            <th>Masalah</th>
            <th>Klasifikasi</th>
            <th>Analisa Penyebab</th>
            <th>Tindakan Perbaikan</th>
            <th>Verifikasi Tgl. Perbaikan</th>
            <th>Tindakan Pencegahan</th>
            <th>Verifikasi Tgl. Pencegahan</th>
            <th>Verifikasi Pencegahan</th>
            <th>Status</th>
        </tr>
        @foreach ($data as $key => $item)
        <tr>
               <td>{{ $key + 1 }}</td>
                <td>{{ $item->no_urut }}</td>
                <td>{{ $item->pemohon }}</td>
                <td>{{ $item->bagian }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->masalah }}</td>
                <td>{{ $item->klas_temuan }}</td>
                <td>{{ $item->analisa_sebab }}</td>
                <td>{{ $item->perbaikan }}</td>
                <td>{{ $item->batas_perbaikan }}</td>
                <td>{{ $item->r_verifikasi_perbaikan }}</td>
                <td>{{ $item->pencegahan }}</td>
                <td>{{ $item->r_verifikasi_cegah }}</td>
                <td>{{ $item->hasil_verifikasi }}</td>

            </tr>
        @endforeach
    </table>



<div style="margin-top: 5%;">
    {{ $tgl_sekarang }}
</div>

</body>
