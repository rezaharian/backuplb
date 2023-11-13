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

    </div>

    <section>
        <div>
            <a href="../../../hr/dashboard/srtcuti/create" class="btn btn-primary btn-sm rounded-0">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>

        @if (count($sc) > 0)
            <div class="row mt-2">
                <div class="card  p-0 m-0 border-primary rounded-0">
                    <div style="height:490px;overflow:auto;">
                        <table id="myTable2" class="align-items-center   table1">
                            <thead class=" bg-primary text-center text-uppercase shadow font-weight-bolder sticky-top  ">
                                <tr style="font-size: 8pt; background-color:rgb(5, 118, 255); font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"
                                    class="text-light">
                                    <th>No</th>
                                    <th>No Payroll</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Bagian</th>
                                    <th>Lama</th>
                                    <th>Keterangan</th>
                                    <th>Opt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sc as $data)
                                    <tr style="font-size: 9pt;">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data['ct_reg'] }}</td>
                                        <td>{{ $data['ct_tgl'] }}</td>
                                        <td>{{ $data['ct_nam'] }}</td>
                                        <td>{{ $data['ct_unt'] }}</td>
                                        <td>{{ $data['ct_jml'] }}</td>
                                        <td>{{ Str::limit($data['ct_not'], 15) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('hr.srtcuti.edit', $data->id) }}">
                                                <i class="fas fa-pen"></i></a>
                                            <a class="mx-2" href="{{ route('hr.srtcuti.delete', $data->id) }}"
                                                onclick="return confirm('Apakah Anda Yakin ?');"><i
                                                    class="fas fa-trash"></i></a>
                                            <a href="{{ route('hr.srtcuti.print',  $data->id) }}"
                                                target="_blank"><i class="fas fa-print"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <p>Tidak ada data yang tersedia.</p>
        @endif
    </section>


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
@endsection
