@extends('qc.dashboard.layout.layout')

@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

<div class="section text-xs">
    <div class="row ">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-2 ">
                        <a href="/qc/dashboard/problemmsn/create"  class="btn btn-sm btn-primary rounded-0 mb-0">Tambah</a>
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>
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
                    <div class="alert alert-info text-center">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
                <div class="card card-plain rounded-0 border-primary me-1">
                    <div class="table-responsive">
                        <table id="myTable" class="align-items-center mb-0 table-sm">
                            <thead class="bg-primary text-center text-uppercase text-light shadow font-weight-bolder sticky-top">
                                <tr style="font-size: 8pt; background-color: rgb(5, 109, 255);" class="text-light">
                                    <th>Tanggal</th>
                                    <th>DOC</th>
                                    <th>Line</th>
                                    <th>Unit Mesin</th>
                                    <th>Masalah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody  class="text-secondary">
                                @forelse ($prob_h as $item)
                                    <tr class="table-row" style="cursor: pointer;">
                                        <td>{{ $item->tgl_input }}</td>
                                        <td>{{ $item->prob_cod }}</td>
                                        <td>{{ $item->line }}</td>
                                        <td>{{ $item->unitmesin }}</td>
                                        <td>{{ $item->masalah }}</td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('problemmsn.edit', $item->id) }}" class="link-icon">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('problemmsn.print_d', $item->id) }}" class="link-icon" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="{{ route('problemmsn.delete', $item->id) }}" onclick="return confirm('Apakah Anda Yakin ?');" class="link-icon">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                        </div>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="alert alert-danger">
                                            Data Post belum Tersedia.
                                        </div>
                                    </td>
                                </tr>
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
            "order": [[0, "desc"]], // Mengurutkan berdasarkan kolom Tanggal (indeks kolom 0) secara descending
            "pageLength": 100, // Menetapkan entri default menjadi 100
            "lengthChange": false, // Menyembunyikan pilihan "Show [n] entries"
            "scrollY": "400px", // Mengatur tinggi maksimum tabel
            "scrollCollapse": true, // Mengaktifkan penyesuaian tinggi saat scroll
            "dom": '<"top"f<"float-end"l>>rt<"bottom"ip>',
        });

        $('.table-row').hover(function() {
            $(this).css('background-color', '#e9e8f9');
        }, function() {
            $(this).css('background-color', '');
        });
    });
</script>

@endsection
