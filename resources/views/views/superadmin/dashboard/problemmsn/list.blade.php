@extends('superadmin.dashboard.layout.layout')

@section('content')
    <div class="section text-xs  px-4">
        <div class="row ">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-2 ">
                        <a href="/superadmin/dashboard/problemmsn/create" class="btn btn-sm btn-success">Tambah</a>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
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
                    <div class="card ">
                        <div style="height:450px;overflow:auto;">
                            <table  class="  align-items-center mb-0  table-bordered border-dark">
                                <thead class=" bg-primary text-center text-light shadow font-weight-bolder sticky-top  ">
                                   
                                        <tr   class="text-xs">
                                            <td style="width:10%;">Tanggal</td>
                                            <td style="width:10%;">DOC</td>
                                            <td style="width:10%;">Line</td>
                                            <td style="width:10%;">Unit Mesin</td>
                                            <td style="width:40%;">Masalah</td>
                                            <td style="width:15%;">Aksi</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prob_h as $item)
                                    <tr class="bg-gradient-light   ">
                                        <td>{{ $item->tgl_input }}</td>
                                        <td>{{ $item->prob_cod }}</td>
                                        <td>{{ $item->line }}</td>
                                        <td>{{ $item->unitmesin }}</td>
                                        <td>{{ $item->masalah }}</td>
                                        <td>
                                        <a href="{{ route('adm.problemmsn.edit', $item->id) }}"class="btn btn-sm btn-primary ">Edit</a>
                                        <a href="{{ route('adm.problemmsn.print_d', $item->id) }}"class="btn btn-sm btn-warning " target="_blank">Print</a>
                                        <a href="{{ route('adm.problemmsn.delete', $item->id) }}" onclick="return confirm('Apakah Anda Yakin ?');" class="btn btn-sm btn-danger">Hapus</a>

                                            </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Data Post belum Tersedia.
                                    </div>
                                @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>


 
@endsection

