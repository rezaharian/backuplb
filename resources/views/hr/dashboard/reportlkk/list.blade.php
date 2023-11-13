@extends('hr.dashboard.layout.layout')

@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <div class="container">
        <div class="section">
            <div class="row ">
                <h5 class="text-center m-0">KARYAWAN MASUK KERJA PADA  {{ date('d-m-Y', strtotime($tanggal)) }} </h5>
                <div class="col-md-6">

                    {{-- <div class="row">
                        <div class="col-md-5">No Payroll</div>
                        <div class="col-md-6"> : {{ $peg->no_payroll }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">Nama </div>
                        <div class="col-md-6"> : {{ $peg->nama_asli }}</div>
                    </div> --}}
                </div>
                <div class="col-md-6 ">
                    <div class="col" style="text-align: right;">
                        <form action="{{ route('hr.reportlkk.print') }}" method="GET" target="_blank">
                            @csrf
                            <input class="form-control form-control-sm rounded-0" type="date" id="tanggal"name="tanggal"
                                value="{{ $tanggal }}" hidden>
                            <input class="form-control form-control-sm rounded-0" type="text" id="shift"name="shift"
                                value="{{ $shift }}" hidden>
                            <input class="form-control form-control-sm rounded-0" type="text" id="jenis"name="jenis"
                                value="{{ $jenis }}" hidden>
                            <button class="btn btn-primary rounded-0 m-0" type="submit">
                                <i class="fas fa-print m-0 "></i> Print
                            </button>
                        </form>
                    </div>
                </div>

            </div>



            @if (count($data) > 0)
                <div class="row  m-0">
                    <div class="card  p-0 m-0 border-primary rounded-0">
                        <div style="height:450px;overflow:auto;">
                            <table id="myTable2" class="align-items-center   table1" style="font-size: 10pt;">
                                <thead
                                    class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
                                    <tr style=" background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                                        class="text-light">
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
