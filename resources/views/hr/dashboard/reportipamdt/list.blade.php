@extends('hr.dashboard.layout.layout')

@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <div class="container">
        <div class="section">
            <div class="row ">
                <h5 class="text-center">DATA ABSENSI KARYAWAN PT. EXTRUPACK
                    TAHUN
                </h5>
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-5">No Payroll</div>
                        <div class="col-md-6"> : {{ $peg->no_payroll }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">Nama </div>
                        <div class="col-md-6"> : {{ $peg->nama_asli }}</div>
                    </div>
                </div>
                <div class="col-md-6 ">

                    <div class="col" style="text-align: right;">
                        <form action="{{ route('hr.reportipamdt.print') }}" method="GET" target="_blank">
                            @csrf

                            <input class="form-control form-control-sm rounded-0" type="text"
                                value="{{ $peg->no_payroll }}" id="no_payroll"name="no_payroll" hidden>
                            <input class="form-control form-control-sm rounded-0" type="date" id="awal"name="awal"
                                value="{{ $tgl_awal }}" hidden>
                            <input class="form-control form-control-sm rounded-0" type="date" id="akhir"name="akhir"
                                value="{{ $tgl_akhir }}" hidden>
                                <button class="btn btn-primary rounded-0 mt-0" type="submit">                                
                                    <i class="fas fa-print "></i> Print
                                </button>
                        </form>
                    </div>
                </div>

            </div>



            @if (count($absenDataL) > 0)
                <div class="row mt-0">
                    <div class="card  p-0 m-0 border-primary rounded-0">
                        <div style="height:450px;overflow:auto;">
                            <table id="myTable2" class="align-items-center   table1">
                                <thead
                                    class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
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
                                    <tr class="fw-bold bg-secondary text-light">
                                        <td colspan="6"></td>
                                        <td>Jumlah Menit</td>
                                        <td>{{ $jumlah_mnt_dt }}</td>
                                        <td>{{ $jumlah_mnt_ipa }}</td>
                                    </tr>
                                    <tr class="fw-bold bg-secondary text-light">
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
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable, #myTable2').DataTable({
            "order": [
                [1, "asc"]
            ], // Mengurutkan berdasarkan kolom Tanggal (indeks kolom 4) secara descending
            "pageLength": 100, // Menetapkan entri default menjadi 100
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
