@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-8">
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
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead class="text-center">
                            <tr>
                                <th class=" font-weight-bolder opacity-7">Bagian</th>
                                <th class=" font-weight-bolder opacity-7 ps-2">Departement</th>
                                <th class=" font-weight-bolder opacity-7">Seksi</th>
                                <th class=" font-weight-bolder opacity-7">Opt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bagians as $bag)
                                <tr class="text-center">
                                    <td class=" font-weight-bolder">{{ $bag->bagian }}</td>
                                    <td class=" font-weight-bolder">{{ $bag->departemen }}</td>
                                    <td class=" font-weight-bolder" class=" font-weight-bolder">{{ $bag->seksi }}</td>
                                    <td class="text-center">
                                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('hr.bagian.delete', $bag->id) }}" method="POST">
                                            <a href="{{ route('hr.bagian.edit', $bag->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                        </form>
                                    </td>       
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <div class="col-md-4">
       
        <div class="card card-plain">
            <div class="card-header pb-0 text-left">
                <h4 class="font-weight-bolder text-info text-gradient">Tambah Bagian</h4>
            </div>
            <div class="card-body">
                <form role="form text-left" action="{{ url('/hr/dashboard/training/bagian/store') }}"
                    method="POST">
                    @csrf
                    <label>Bagian</label>
                    <div class="input-group mb-3">
                        <input type="text"  name="bagian" class="form-control"
                            placeholder="bagian">
                    </div>
                    <label>Departemen</label>
                    <input  type="text"  name="departemen" class="form-control"
                            placeholder="departemen" >

                    <label>Seksi</label>
                    <input  type="text"  name="seksi" class="form-control"
                            placeholder="seksi" >
                      
                    <div class="text-center">
                        <button type="submit"
                            class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Simpan</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
    </div>
@endsection
