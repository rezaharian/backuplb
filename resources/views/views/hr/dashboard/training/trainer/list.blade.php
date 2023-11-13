@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')
    <div class="section  px-4">
        <div class="row ">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-2 ">
                        <a href="/hr/dashboard/training/tr/create"  >
                            <button class="btn btn-sm bg-info">Tambah</button>
                        </a>
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
                    <div class="table  ">
                        <table  class="table align-items-center mb-0 border-success table-striped">
                            <thead class=" bg-secondary text-light shadow font-weight-bolder  ">
                                <tr>
                                    <td>No Pelatihan</td>
                                    <td>Pemateri</td>
                                    <td >Pelatihan</td>
                                    <td>Kompetensi</td>
                                    <td>Tanggal</td>
                                    <td>Tipe</td>
                                    <td>Option</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($training as $item)
                                    <tr class="bg-gradient-light   ">
                                        <td>{{ $item->train_cod }}</td>
                                        <td>{{ $item->pemateri }}</td>
                                        <td>{{ $item->pltran_nam }}</td>
                                        <td>{{ $item->kompetensi }}</td>
                                        <td>{{ $item->train_dat }}</td>
                                        <td>{{ $item->tipe }}</td>
                                        <td><a href="{{ route('trainer.view', $item->id) }}"
                                                class="btn btn-sm btn-primary ">Lihat</a></td>
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

