@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="section px-4">
        <div class="row">
            <div class="col-md-10">
                <a href="/hr/dashboard/training/kompetensi/create">
                    <button class="btn btn-sm bg-info">Tambah</button>
                </a>
            </div>
            <div class="col-md-1">
            </div>
        </div>

        <div class="row mt-2">
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
                    <div class="alert alert-success">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
                <div class="card">
                    <div class="table-responsive">
                        <table  class="table align-items-center mb-0 border-success table-striped">
                            <thead class=" bg-secondary text-light shadow font-weight-bolder  ">
                                <tr>
                                    <td>No Kompetensi</td>
                                    <td>Bagian</td>
                                    <td>Tipe</td>
                                    <td class="text-center">Opsi</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kompetensi as $item)
                                    <tr class="bg-light bg-gradient ">
                                        <td>{{ $item->kompe_cod }}</td>
                                        <td>{{ $item->bagian }}</td>
                                        <td>{{ $item->tipe }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('hr.kompetensi.view', $item->id) }}"
                                                class="btn btn-sm btn-primary">Lihat</a>
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
