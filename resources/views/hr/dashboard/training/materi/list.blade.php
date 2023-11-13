@extends('hr.dashboard.layout.layout')

@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <div class="row">
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
            <div class="alert alert-info text-center px-5">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div class="col-md-4">
            <div class="card card-plain rounded-0 border-primary">
                <div class="card-header pb-0 bg-primary rounded-0">
                    <h4 class="font-weight-bolder text-light">Tambah Materi</h4>
                </div>
                <div class="card-body">
                    <form action="/hr/dashboard/training/materi/create" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group m-0">
                            <label for="example-text-input" class="form-control-label text-secondary  m-0 ">Materi</label>
                            <textarea class="form-control form-control-sm m-0" type="text" name="materi" value=""></textarea>
                        </div>

                        <div class="form-group m-0">
                            <label for="example-text-input" class="form-control-label text-secondary  m-0">Bagian</label>
                            <select class="form-control form-control-sm m-0" type="text" name="bagian" value="">
                                @foreach ($bag as $item)
                                    <option value="{{ $item->bagian }}">{{ $item->bagian }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group m-0">
                            <label for="example-text-input" class="form-control-label text-secondary  m-0">File
                                Materi</label>
                            <input type="file" class="form-control form-control-sm m-0" id="file-upload" name="file_materi">
                        </div>

                        <div class="form-group m-0">
                            <label for="example-text-input"
                                class="form-control-label text-secondary  m-0">Keterangan</label>
                            <input class="form-control form-control-sm m-0" type="text" name="keterangan" value="">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary m-0">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
        <div class="col-md-8">

            <div class="card card-plain rounded-0 border-primary">
                <div style="height:500px;overflow:auto;">
                    <table id="myTable" class="align-items-center mb-0  table-sm">
                        <thead class="bg-primary text-center text-uppercase text-light shadow font-weight-bolder sticky-top">
                            <tr style="font-size: 8pt; background-color: rgb(5, 109, 255);" class="text-light">
                                <th style="width: 1%;" scope="col">No</th>
                                <th style="width: 10%;" scope="col">Kode</th>
                                <th style="width: 40%;" scope="col">Materi</th>
                                <th style="width: 16%;" scope="col">Bagian</th>
                                <th style="width: 10%;" scope="col">Keterangan</th>
                                <th style="width: 18%;" scope="col">Opt</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9pt;" class="text-secondary">
                            @foreach ($materi as $item)
                            <tr >
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: center;">{{ $item->kode_materi }}</td>
                                <td>{{ $item->materi }}</td>
                                <td>{{ $item->bagian }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td class="text-center">
                                    <div>
                                        <a href="{{ route('hr.training.materi.delete', $item->id) }}"
                                            onclick="return confirm('Apakah Anda Yakin ?');" class="btn btn-sm m-0 btn-delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                        <a href="{{ route('hr.training.materi.edit', $item->id) }}"
                                            class="btn btn-sm m-0 btn-editt">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="{{ route('hr.training.materi.view', $item->id) }}"
                                            class="btn btn-sm m-0 btn-view">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
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
            $('#myTable, #myTable2').DataTable({
                "order": [
                    [0, "asc"]
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
            padding: 0.5em;
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
    
        .btn-delete {
            background-color: rgb(255, 255, 255);
            color: red;
        }

        .btn-editt {
            background-color: rgb(255, 255, 255);
            color: rgb(4, 42, 255);
        }

        .btn-view {
            background-color: rgb(255, 255, 255);
            color: rgb(29, 4, 255);
        }

        .btn-delete:hover {
            background-color: red;
            transform: scale(1.1);
            color: white;
        }

        .btn-editt:hover {
            background-color: rgb(4, 42, 255);
            transform: scale(1.1);
            color: white;
        }

        .btn-view:hover {
            background-color: rgb(29, 4, 255);
            transform: scale(1.1);
            color: white;
        }


       
    </style>


@endsection
