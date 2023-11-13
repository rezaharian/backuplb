@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')
<style>
    .icon-link {
    display: inline-block;
    margin-right: 5px;
    position: relative;
    text-decoration: none;
}

.icon-link i {
    font-size: 10px;
    padding: 5px;
    color: #fff; /* Warna ikon */
    border-radius: 50%; /* Untuk membuat ikon berbentuk lingkaran */
}

.icon-view i {
    background-color: #00ddff; /* Warna latar belakang ikon edit */
}
.icon-edit i {
    background-color: #007bff; /* Warna latar belakang ikon edit */
}

.icon-print i {
    background-color: #28a745; /* Warna latar belakang ikon print */
}

.icon-delete i {
    background-color: #dc3545; /* Warna latar belakang ikon delete */
}

/* Efek hover */
.icon-link:hover i {
    opacity: 0.8; /* Opacity pada hover */
}

</style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

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

        <div>
            <div class="row  m-0">
                <div class="col-md-2 m-0">           <a href="/hr/trainer/dashboard/penilaiankerja/create">
                    <button class="btn btn-sm btn-primary rounded-0 m-0">Tambah</button>
                </a></div>
                <div class="col-md-10 m-0 bg-primary text-light fw-bold"> {{ $jumlahygsudah }} / {{ $jumlahsemua }} <small>  Karyawan ({{ $aksesbg }}) </small></div>
            </div>
 
            {{-- <h3>INI LIST PENILAIAN KERJA</h3> --}}
            <div class="row mt-2">
                <div class="card  p-0 m-0 border-primary rounded-0">
                    <div style="height:450px;overflow:auto;">
                        <table id="myTable" class="align-items-center    table1" style="font-size: 8pt;">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kode</th>
                                    <th scope="col">No Payroll</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Bagian</th>
                                    <th scope="col">Periode</th>
                                    <th scope="col">Review</th>
                                    <th scope="col">R Nilai</th>
                                    <th scope="col">T Nilai</th>
                                    <th scope="col">Opt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pkinerja as $item)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ substr($item->kode, 0, 5) }}</td>
                                        <td>{{ $item->no_payroll }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->bagian }}</td>
                                        <td>{{ $item->periode }}</td>
                                        <td>{{ $item->review }}</td>
                                        <td>
                                            <?php
                                            if ($item->total_nilai1 > 1 && $item->total_nilai2 == 0 && $item->total_nilai3 == 0) {
                                                echo "<i class='fas fa-check-circle' style='color: green;'></i>  <i class='fas fa-times-circle' style='color: red;'></i>  <i class='fas fa-times-circle' style='color: red;'></i>";
                                            } elseif ($item->total_nilai1 == 0 && $item->total_nilai2 > 1 && $item->total_nilai3 == 0) {
                                                echo "<i class='fas fa-times-circle' style='color: red;'></i>  <i class='fas fa-check-circle' style='color: green;'></i>  <i class='fas fa-times-circle' style='color: red;'></i>";
                                            } elseif ($item->total_nilai1 == 0 && $item->total_nilai2 == 0 && $item->total_nilai3 > 1) {
                                                echo "<i class='fas fa-times-circle' style='color: red;'></i>  <i class='fas fa-times-circle' style='color: red;'></i>  <i class='fas fa-check-circle' style='color: green;'></i>";
                                            } elseif ($item->total_nilai1 > 1 && $item->total_nilai2 > 1 && $item->total_nilai3 > 1) {
                                                echo "<i class='fas fa-check-circle' style='color: green;'></i>  <i class='fas fa-check-circle' style='color: green;'></i>  <i class='fas fa-check-circle' style='color: green;'></i>";
                                            } elseif ($item->total_nilai1 == 0 && $item->total_nilai2 == 0 && $item->total_nilai3 == 0) {
                                                echo "<i class='fas fa-times-circle' style='color: red;'></i>  <i class='fas fa-times-circle' style='color: red;'></i>  <i class='fas fa-times-circle' style='color: red;'></i>";
                                            } elseif ($item->total_nilai1 > 1 && $item->total_nilai2 > 1  && $item->total_nilai3 == 0) {
                                                echo "<i class='fas fa-check-circle' style='color: green;'><i class='fas fa-check-circle' style='color: green;'></i>  <i class='fas fa-times-circle' style='color: red;'></i>";
                                            } else {
                                                echo "Invalid Input";
                                            }
                                            ?>
                                        </td>                                        <td>{{ $item->total_score }}</td>
                                        <td class="text-center">
                                            {{-- <a href="{{ route('tr.hr.penilaiankerja.view', $item->id) }}" class="icon-link icon-view">
                                                <i class="fas fa-eye"></i>
                                            </a> --}}
                                            <a href="{{ route('tr.hr.penilaiankerja.edit', ['id' => $item->id]) }}" class="icon-link icon-edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="{{ route('tr.hr.penilaiankerja.print', ['id' => $item->id]) }}" class="icon-link icon-print" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="{{ route('tr.hr.penilaiankerja.delete', ['id' => $item->id]) }}" class="icon-link icon-delete" onclick="return confirm('Apakah Anda yakin akan menghapus item ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            
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
            $('#myTable').DataTable({
                "order": [
                    [0, "asc"]
                ],
                "pageLength": 20,
                "lengthChange": false
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
