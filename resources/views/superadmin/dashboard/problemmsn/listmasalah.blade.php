@extends('superadmin.dashboard.layout.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <div class="section">
    
        <div class="row ">
            <div class="col-md-12">
                <div class="card border-primary rounded-0 pt-2   p-1">
                    <div class="row text-center">
                        <h5 class="font-weight-bolder mb-2 ">Daftar Masalah Mesin</h5>
                        
                        <div class="col-md-2">
                            <form class="form-group" action="{{ route('adm.problemmsn.index') }}" method="GET">
                                <select
                                    class="form-select rounded-0 form-select-sm font-weight-bold text-secondary rounded border-primary"
                                    name="cariline">
                                    <option selected value="">SEMUA</option>
                                    @foreach ($datal as $item)
                                        <option value="{{ $item->line }}">{{ $item->line }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-2">
                            <select
                                class="form-select rounded-0 form-select-sm font-weight-bold text-secondary rounded border-primary"
                                name="cariunitmsn">
                                <option selected value="">SEMUA</option>
                                @foreach ($datau as $item)
                                    <option value="{{ $item->unit_nam }}">{{ $item->unit_nam }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text"
                                class="form-control rounded-0 form-control-sm font-weight-bold text-secondary border-primary"
                                name="masalah" placeholder="Masalah">
                        </div>
                        <div class="col-md-3">
                            <input type="text"
                                class="form-control rounded-0 form-control-sm font-weight-bold text-secondary border-primary"
                                name="penyebab" placeholder="Penyebab">
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-primary border rounded-0 m-0 text-light btn-sm" value="FILTER">
                        </div>
                        </form>
                    </div>

                </div>
          
            </div>
        </div>

    <div class="row mt-1">
        <div class="col-md-12">
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
                <div class="alert alert-success text-center">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="card  p-0 m-0 border-primary rounded-0">
                <div style="height:450px;overflow:auto;">
                    <table id="myTable" class="align-items-center   table1">
                        <thead
                            class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
                            <tr style="font-size: 9pt; background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                                class="text-light">
                                <th >Line</th>
                                <th > Mesin</th>
                                <th >Masalah</th>
                                <th >Penyebab</th>
                                <th >Perbaikan</th>
                                <th >Pencegahan</th>
                                <th >TGL</th>
                                <th > Doc</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list as $item)
                                <tr class="text-secondary" >
                                    <td >{{ $item->line }}</td>
                                    <td>{{ $item->unitmesin }}</td>
                                    <td>
                                        {{ $item->masalah }}
                                    </td>
                                    <td>
                                        {{ $item->penyebab }}
                                    </td>
                                    <td>
                                        {{ $item->perbaikan }}
                                    </td>
                                    <td>
                                        {{ $item->pencegahan }}
                                    </td>
                                    <td>{{ $item->tgl_input }}</td>
                                    <td>{{ $item->prob_cod }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('adm.problemmsn.view_d', $item->id) }}">
                                                <i class="fas fa-eye" style="color: blue;"></i>
                                        </a>
                                    </td>
                                    
                                </tr>
                            @empty
                                <div class="alert alert-danger">
                                    Data belum Tersedia.
                                </div>
                            @endforelse
                        </tbody>
                    </table>
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
            font-size: 10pt;
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
