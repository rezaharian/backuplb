@extends('umum.dashboard.layout.layout')

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


        <a href="/umum/dashboard/overt/create/">
            <button class="btn btn-md btn-primary m-0 mb-2 rounded-0 "><i class="fas fa-add"></i></button>
        </a>
        <div class="row mt-2">
            <div class="card  p-0 m-0 border-primary rounded-0">
                <div style="height:500px;overflow:auto;">
                    <table class="table border-0" id="myTable" style="font-size: 10pt;" >
                        <thead class="bg-primary text-light sticky-top ">
                            <tr class="fw-bold">
                                <td>No</td>
                                <td>Kode</td>
                                <td>Tanggal</td>
                                <td>Hari</td>
                                <td>Bagian</td>
                                <td>Pekerjaan</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody class="text-secondary">
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->ot_cod }}</td>
                                    <td>{{ $item->ot_dat }}</td>
                                    <td>{{ $item->ot_day }}</td>
                                    <td>{{ $item->ot_bag }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td class="item-center text-center">
                                        <a href="{{ route('umum.overt.detail', ['id' => $item->id]) }}"><i
                                                class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $('.nama_asli').select2({
            placeholder: 'SEMUA',
            ajax: {
                url: '/autocompleted',
                dataType: 'json',
                delay: 5,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: [item.nama_asli, item.no_payroll],
                                id: item.no_payroll,
                                // id: item.id,

                            }
                        })
                    };
                },
                cache: false
            }
        });
    </script>

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
    #overtTable_wrapper {
        border: none;
    }

    #overtTable {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }

    #overtTable th,
    #overtTable td {
        border: none;
        padding: 8px; /* Adjust the padding as needed */
        text-align: left;
    }

    #overtTable thead {
        background-color: #f2f2f2; /* Optional: Set background color for header row */
    }

    #overtTable_wrapper .dataTables_paginate {
        margin-top: 10px; /* Optional: Adjust the pagination position */
    }


</style>

    </div>
@endsection
