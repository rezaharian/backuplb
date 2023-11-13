@extends('hr.dashboard.layout.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <style>
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
    <div class="container fade-in ">
        <div class="row px-2">
            <div class="row ">
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
                <div class="col-md-6 ">

                </div>



            </div>
            <ul class="nav nav-tabs fw-bold" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                        data-bs-target="#data-extrupack-plane" type="button" role="tab"
                        aria-controls="data-extrupack-plane" aria-selected="true">Extrupack</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                        data-bs-target="#data-satpamkebersihan-plane" type="button" role="tab"
                        aria-controls="data-satpamkebersihan-plane" aria-selected="false">Satpam &
                        Kebersihan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data-keluar-plane"
                        type="button" role="tab" aria-controls="data-keluar-plane"
                        aria-selected="false">Keluar</button>
                </li>



                </form>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="data-extrupack-plane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">

                    <div class="container">
                        <a href="/hr/dashboard/pegawai/create">
                            <i class="fas btn-sm btn-primary mt-2 fa-add"></i>
                        </a>
                        <div class="row mt-2">

                            <div class="card  p-0 m-0 border-primary rounded-0">
                                <div style="height:450px;overflow:auto;">
                                    <table id="myTable" class="align-items-center   table1">
                                        <thead
                                            class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
                                            <tr style="font-size: 9pt; background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                                                class="text-light">
                                                <th style="width:4%;" scope="col">View</th>
                                                <th style="width:1%;" scope="col">No</th>
                                                <th style="width:10%;" scope="col">NIK</th>
                                                <th style="width:40%;" scope="col">Nama</th>
                                                <th style="width:20%;" scope="col">Bagian</th>
                                                <th style="width:30%;" scope="col">Jabatan</th>
                                                <th style="width:40%;" scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $item)
                                                <tr style="font-size: 9pt; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; "
                                                    class="text-secondary">

                                                    <td class="text-center">
                                                        <a href="{{ route('datapegawai.detail', $item->id) }}"> <i
                                                                class="fa-solid fa-eye">
                                                            </i> </a>
                                                    </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td style="text-align: center;">{{ $item->no_payroll }}</td>
                                                    <td>{{ $item->nama_asli }}</td>
                                                    <td>{{ $item->bagian }}</td>
                                                    <td>{{ $item->jabatan }}</td>
                                                    <td>{{ $item->jns_peg }}</td>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show " id="data-satpamkebersihan-plane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="container">
                        <a href="/hr/dashboard/pegawai/create_satpamkebersihan">
                            <i class="fas btn-sm btn-primary mt-2 fa-add"></i>
                        </a>
                        <div class="row mt-2">
                            <div class="card  p-0 m-0 border-primary rounded-0">
                                <div style="height:450px;overflow:auto;">
                                    <table id="myTable2" class="align-items-center   table1">
                                        <thead
                                            class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
                                            <tr style="font-size: 9pt; background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                                                class="text-light">
                                                <th style="width:1%;" scope="col">View</th>
                                                <th style="width:1%;" scope="col">No</th>
                                                <th style="width:10%;" scope="col">NIK</th>
                                                <th style="width:50%;" scope="col">Nama</th>
                                                <th style="width:20%;" scope="col">Bagian</th>
                                                <th style="width:30%;" scope="col">Jabatan</th>
                                                <th style="width:40%;" scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_satpamkebersihan as $item)
                                                <tr style="font-size: 9pt; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; "
                                                    class="text-secondary">

                                                    <td class="text-center">
                                                        <a href="{{ route('datapegawai.detail', $item->id) }}"> <i
                                                                class="fa-solid fa-eye">
                                                            </i> </a>
                                                    </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td style="text-align: center;">{{ $item->no_payroll }}</td>
                                                    <td>{{ $item->nama_asli }}</td>
                                                    <td>{{ $item->bagian }}</td>
                                                    <td>{{ $item->jabatan }}</td>
                                                    <td>{{ $item->jns_peg }}</td>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show " id="data-keluar-plane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="container">
                        {{-- <a href="/hr/dashboard/pegawai/create_satpamkebersihan">
                            <i class="fas btn-sm btn-primary mt-2 fa-add"></i>
                        </a> --}}
                        <div class="row mt-2">
                            <div class="card  p-0 m-0 border-primary rounded-0">
                                <div style="height:450px;overflow:auto;">
                                    <table id="myTable2" class="align-items-center   table1">
                                        <thead
                                            class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
                                            <tr style="font-size: 9pt; background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                                                class="text-light">
                                                <th style="width:1%;" scope="col">View</th>
                                                <th style="width:1%;" scope="col">No</th>
                                                <th style="width:10%;" scope="col">NIK</th>
                                                <th style="width:10%;" scope="col">Tgl Keluar</th>
                                                <th style="width:50%;" scope="col">Nama</th>
                                                <th style="width:20%;" scope="col">Bagian</th>
                                                <th style="width:30%;" scope="col">Jabatan</th>
                                                <th style="width:40%;" scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_keluar as $item)
                                                <tr style="font-size: 9pt; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; "
                                                    class="text-secondary">

                                                    <td class="text-center">
                                                        <a href="{{ route('datapegawai.detail', $item->id) }}"> <i
                                                                class="fa-solid fa-eye">
                                                            </i> </a>
                                                    </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td style="text-align: center;">{{ $item->no_payroll }}</td>
                                                    <td>{{ date_format(new DateTime($item->tgl_keluar), 'd-m-Y') }}</td>
                                                    <td>{{ $item->nama_asli }}</td>
                                                    <td>{{ $item->bagian }}</td>
                                                    <td>{{ $item->jabatan }}</td>
                                                    <td>{{ $item->jns_peg }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
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

@endsection
