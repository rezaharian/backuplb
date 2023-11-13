@extends('umum.dashboard.layout.layout')

@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid rgb(168, 166, 166);
        padding: 5px;
    }
</style>

<div class="container">
    <h6 class="text-center">{{ strtoupper('RINGKASAN ABSENSI KARYAWAN PT.EXTRUPACK') }} <br> DARI BULAN {{ strtoupper($bulanAwal) }} S.D. {{ strtoupper($bulanAkhir) }}  {{ $tahun }}</h6>

    <div class="card text-muted">
        {{-- <p class="m-1 " style="font-size: 10pt">Bagian : {{ $peg->bagian }}</p> --}}
        <div class="table-responsive">
            <table id="myTable" class=" m-1  text-center bordered  table-sm" style="font-size: 8pt; max-width: 100%;">
                <thead>
                    <tr>
                        <th class="m-0 p-0">No</th>
                        <th class="m-0 p-0">Reg</th>
                        <th class="m-0 p-0">Nama</th>
                        <th class="m-0 p-0">Msk Kerja</th>
                        <th class="m-0 p-0">SK</th>
                        <th class="m-0 p-0">SD</th>
                        <th class="m-0 p-0">H</th>
                        <th class="m-0 p-0">I</th>
                        <th class="m-0 p-0">IPC</th>
                        <th class="m-0 p-0">IC</th>
                        <th class="m-0 p-0">M</th>
                        <th class="m-0 p-0">Lmbt(x)</th>
                        <th class="m-0 p-0">Lmbt(jam)</th>
                        <th class="m-0 p-0">IPA(x)</th>
                        <th class="m-0 p-0">IPA(jam)</th>
                        <th class="m-0 p-0">DL</th>
                        <th class="m-0 p-0">Cuti Besar</th>
                        <th class="m-0 p-0">SCTB</th>
                        <th class="m-0 p-0">SCB</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['pegawai']->no_payroll }}</td>
                        <td>{{ $data['pegawai']->nama_asli }}</td>
                        <td>{{ date('d-m-Y', strtotime($data['pegawai']->tgl_masuk)) }}</td>
                        <td>{{ $data['SK'] }}</td>
                        <td>{{ $data['SD'] }}</td>
                        <td>{{ $data['H'] }}</td> 
                        <td>{{ $data['I'] }}</td> 
                        <td>{{ $data['IPC'] }}</td> 
                        <td>{{ $data['IC'] }}</td> 
                        <td>{{ $data['M'] }}</td> 
                        <td>{{ $data['lmbtx'] }}</td> 
                        <td>{{ $data['lmbtjm'] }}</td> 
                        <td>{{ $data['ipax'] }}</td> 
                        <td>{{ $data['ipajam'] }}</td> 
                        <td>{{ $data['dl'] }}</td> 
                        <td>{{ $data['icb'] }}</td> 
                        <td>{{ $data['SCTB'] }}</td> 
                        <td>{{ $data['SCB'] }}</td> 
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable, #myTable2').DataTable({
            "order": [
                [1, "asc"]
            ], // Mengurutkan berdasarkan kolom Tanggal (indeks kolom 4) secara descending
            "pageLength": 20, // Menetapkan entri default menjadi 100
            "lengthChange": false // Menyembunyikan pilihan "Show [n] entries"
        });
    });
</script>




<style>
    ::-webkit-scrollbar {
        width: 0.1em;
        /* Ubah lebar scrollbar sesuai kebutuhan */
        height: 0.1em;
        /* Ubah tinggi scrollbar sesuai kebutuhan */
    }

    ::-webkit-scrollbar-thumb {
        background-color: transparent;
    }

    div.dataTables_wrapper input[type="search"] {
        border: 1px solid blue !important;
        margin-top: 2pt;
    }


    table {
        border-collapse: collapse;
        width: 100%;
    }

    table td,
    table th {
        padding: 8px;
    }

    table tffh {
        background-color: #f2f2f2;
        /* Warna latar belakang header dapat disesuaikan */
    }

    table tbody tr:hover {
        background-color: #f5f5f5;
        /* Warna latar belakang baris tbody saat hover */
    }

    table tbody tr:hover td {
        background-color: #ebebeb;
        /* Warna latar belakang sel-sel dalam baris tbody saat hover */
    }


    table td {
        padding: 1em;
        line-height: 0;

        /* Sesuaikan dengan kebutuhan Anda */
    }

    .form-control {
        border-radius: 0;
    }

    .card-plain {
        opacity: 0;
        animation-name: fade-in;
        animation-duration: 0.3s;
        animation-fill-mode: forwards;
        animation-timing-function: ease-in-out;
        animation-delay: 0.3s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@endsection
