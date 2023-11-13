@extends('hr.dashboard.layout.layout')

@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">



    <div class="container  ">
        <div class="row  p-2">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-info text-center">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="col card-plain">
                <div class="row">
                    <div class="col-md ">
                        <a href="/hr/dashboard/training/tr/create">
                            <button class="btn btn-sm btn-primary m-0  ">Tambah</button>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <div class="card card-plain rounded-0 border-primary">
            <div class="row ">
                <div class="col-md-12">
                    <div style="height:490px; overflow:auto;">
                        <table id="myTable" class="align-items-center   table1">
                            <thead class=" bg-primary text-center text-uppercase  text-light  font-weight-bolder sticky-top  ">

                                <tr class="text-xs opacity-10">
                                    <td style="width: 10%;">No Doc</td>
                                    <td style="width: 15%;">Pemateri</td>
                                    <td style="width: 25%;">Pelatihan</td>
                                    <td hidden style="width: 20%;">Kompetensi</td>
                                    <td style="width: 8%;">Tanggal</td>
                                    <td style="width: 8%;">Tipe</td>
                                    <td style="width: 2%;">Approve</td>
                                    <td style="width: 2%;">Action</td>
                                </tr>

                            </thead>
                            <tbody>
                                @forelse ($training as $item)
                                    <tr class=" text-uppercase    "
                                        style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size:9pt;",>
                                        <td class="opacity-7">{{ $item->train_cod }}</td>
                                        <td class="opacity-7">{{ $item->pemateri }}</td>
                                        <td class="opacity-7">{{ $item->train_tema }}</td>
                                        <td hidden class="opacity-7">{{ $item->kompetensi }}</td>
                                        <td class="opacity-7" class="text-center">{{ $item->train_date }}</td>
                                        <td class="opacity-7">{{ $item->tipe }}</td>
                                        <td class="opacity-7">{{ $item->approve }}</td>
                                        <td class="text-center "><a href="{{ route('hr.training.view', $item->id) }}"><i
                                                    class="fas fa-eye"></i>
                                            </a></td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Data Post belum Tersedia.
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [
                    [0, "desc"]
                ], // Mengurutkan berdasarkan kolom Tanggal (indeks kolom 4) secara descending
                "pageLength": 100, // Menetapkan entri default menjadi 100
                "lengthChange": false // Menyembunyikan pilihan "Show [n] entries"
            });
        });
    </script>




    <style>
        
    ::-webkit-scrollbar {
        width: 0.1em; /* Ubah lebar scrollbar sesuai kebutuhan */
        height: 0.1em; /* Ubah tinggi scrollbar sesuai kebutuhan */
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
    
    table td, table th {
        padding: 8px;
    }
    
    table th {
        background-color: #f2f2f2; /* Warna latar belakang header dapat disesuaikan */
    }
    
    table tbody tr:hover {
        background-color: #f5f5f5; /* Warna latar belakang baris tbody saat hover */
    }
    
    table tbody tr:hover td {
        background-color: #ebebeb; /* Warna latar belakang sel-sel dalam baris tbody saat hover */
    }
    

        table td {
            padding: 1em;
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
