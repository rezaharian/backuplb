@extends('hr.dashboard.layout.layout')

@section('content')
    <!-- resources/views/fingerprint/index.blade.php -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    @if (session('success'))
        <div class="alert alert-info" id="success-message">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('success-message').style.display = 'none';
            }, 3000);
        </script>
    @endif

    <div class="loading-overlay" style="display: none;">
        <div class="loading-icon">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
        <br><hr>
        <div class="loading-text">
            Harap tunggu...
        </div>
    </div>
    
    

    <div class="card px-2 rounded-0 border-primary">
        <form id="export-form2" action="{{ route('fingerprint.filter') }}" method="GET" class="form-inline filter-form">
            <div class="row form-row align-items-center">
                <div class="col-md-6">
                    <p>Data Finger Print :
                        @if ($aw == '01/01/1970')
                        @elseif($aw != '01/01/1970')
                            <b> dari {{ $aw }} sampai {{ $ak }} </b>
                        @endif
                        <br> Terakhir Export sampai : <b> {{ $tgl_terakhir_export }} </b>
                    </p>

                </div>
                <div class="col-md-2">
                    <label for="start_date">Tanggal Awal:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="end_date">Tanggal Akhir:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control form-control-sm">
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn mt-5 btn-sm btn-primary">Filter</button>

                </div>
        </form>

        <div class="col-md-1">

            @if ($aw == '01/01/1970')
            @elseif($aw != '01/01/1970')
            <form id="export-form" method="POST" action="/hr/fingerprint/export">
                @csrf
                <input type="hidden" name="export" value="{{ json_encode($export) }}">
                <button id="export-button" class="btn btn-sm mt-5 btn-warning" type="submit">Export</button>
            </form>
                        @endif
          
        </div>
        
    </div>
    </div>



    <div class="card mt-1  p-0 m-0 border-primary rounded-0">
        <div class="row mt-0">
            <div style="height:450px;overflow:auto;">
                <table id="myTable" class="align-items-center    table1">
                    <thead class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
                        <tr style="font-size: 9pt; background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                            class="text-light">
                            <th style="width:10%;">IP</th>
                            <th style="width:10%;">PIN</th>
                            <th style="width:10%;">Date</th>
                            <th style="width:10%;">Time In</th>
                            <th style="width:10%;">Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($export as $data)
                            <tr>
                                <td>{{ $data['IP'] }}</td>
                                <td>{{ $data['PIN'] }}</td>
                                <td>{{ $data['Date'] }}</td>
                                <td>{{ $data['TimeIn'] }}</td>
                                <td>{{ $data['TimeOut'] }}</td>
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
                "pageLength": 100, // Menetapkan entri default menjadi 100
                "lengthChange": false // Menyembunyikan pilihan "Show [n] entries"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#export-form , #export-form2 ').on('submit', function() {
                $('#export-button').attr('disabled', 'disabled');
                $('#export-button').html('<i class="fas fa-spinner fa-spin"></i> Harap tunggu...');
                $('.loading-overlay').fadeIn();
            });
        });
    </script>
    





<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-overlay .loading-icon {
        color: #ffffff;
        font-size: 3rem;
        margin-right: 1%;
    
    }

    .loading-overlay .loading-text {
        color: #fff;
        margin-top: 10px;
    }
</style>

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
            padding: 2em;
            line-height: 1;

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
