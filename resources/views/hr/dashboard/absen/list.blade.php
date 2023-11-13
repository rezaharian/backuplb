@extends('hr.dashboard.layout.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <div class="container">
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

        <div class=" card-plain">
            <div class="row raounded-0 ">


                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="/hr/dashboard/absen/create/{{ $bln }}/{{ $thn }}">
                                <button class="btn btn-sm btn-primary m-0 mb-2 ">Tambah</button>
                            </a>
                        </div>


                    </div>
                </div>
                <div class="col-md-6">
                    <form class="" action="/hr/dashboard/absen/list">
                        <div class="row">
                            <div class="col-md-5">
                                <select class=" form-control form-control-sm font-weight-bolder" name="tahun"
                                    type="text" id="tahun">
                                    <option selected value="{{ $thn }}">{{ $thn }}</option>
                                    @foreach ($tahun as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select class=" form-control form-control-sm font-weight-bolder" name="bulan"
                                    type="text" id="bulan">
                                    <option selected value="{{ $bln }}">{{ $bln }}</option>
                                    @foreach ($bulan as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm m-0 btn-sm ">submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card card-plain rounded-0 border-primary">
            {{-- <div class="row m-1">
                <div class="col-md-6">

                </div>
                <div class="col-md-4 mt-1">
                    <form class="" action="/hr/dashboard/absen/list">
                        <select class="nama_asli form-control font-weight-bolder border-primary" name="cari"
                            type="text" id="no_payroll">
                        </select>
                </div>
                <input hidden type="text" value="{{ $thn }}" name="tahun">
                <input hidden type="text" value="{{ $bln }}" name="bulan">
                <div class="col-md-2 ">

                    <button type="submit" class="btn btn-primary p-0 btn-sm  m-0  form-control-sm fw-bold px-3">Cari . . .
                        .</i></button>

                </div>
            </div>

            </form>
        </div> --}}
        <div class="row ">
            <div class="col-md-12 ">

                <div style="height:480px;overflow:auto;">
                    <table id="myTable" class=" table-sm  align-items-center mb-0 table-hover">
                        <thead
                            class=" bg-primary text-center text-uppercase  text-light shadow font-weight-bolder sticky-top  ">

                            <tr class="text-xs opacity-10">
                                <td >No </td>
                                <td >No Payroll</td>
                                <td>Nama Karyawan</td>
                                <td>Bagian</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody class="text-secondary" style="font-size: 9pt;  ">
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center" >{{ $item->no_payroll }}</td>
                                    <td>{{ $item->nama_asli }}</td>
                                    <td>{{ $item->bagian }}</td>
                                    <td class="item-center text-center m-0">
                                        <a
                                            href="{{ route('hr.absen.detail', ['bln' => $bln, 'thn' => $thn, 'id' => $item->no_payroll]) }}"><i
                                                class="fas fa-eye  m-0"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
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
                    [0, "desc"]
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
    </div>
@endsection
