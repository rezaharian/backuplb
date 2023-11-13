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
            <div class="alert alert-info text-center">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
            <div class="card">
                <div style="height:450px;overflow:auto; width:auto;">
                    <table  class="  align-items-center mb-0  " >
                        <thead class=" bg-primary text-center   text-light shadow font-weight-bolder sticky-top border-primary  ">   
                            <tr>
                                <th  style="width:30%" class=" font-weight-bolder ">Nama Pemateri</th>
                                <th  style="width:30%" class=" font-weight-bolder  ps-2">Level</th>
                                <th  style="width:20%" class=" font-weight-bolder ">keterangan</th>
                                <th  style="width:20%" class=" font-weight-bolder ">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemateri as $pem)
                                <tr class="text-secondary">
                                    <td class="fw-bolder">{{ $pem->nama }}</td>
                                    <td class="fw-bolder">{{ $pem->level }}</td>
                                    <td class="fw-bolder" class="fw-bolder">{{ $pem->ket }}</td>
                                    <td class="text-center">
                                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('pemateri.delete', $pem->id) }}" method="POST">
                                            <a href="{{ route('pemateri.edit', $pem->id) }}" class="btn btn-md btn-rounded text-light btn-primary m-0"><i class="fas fa-pen"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-md btn-rounded btn-danger text-light m-0"><i class="fas fa-trash"></i></button>
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
                <h4 class="font-weight-bolder text-info text-gradient">Tambah Trainer</h4>
            </div>
            <div class="card-body">
                <form role="form text-left" action="{{ url('/hr/dashboard/training/pemateri/store') }}"
                    method="POST">
                    @csrf
                    <label>Nama</label>
                    <div class="input-group mb-3">
                        <input type="text"  name="nama" class="form-control"
                            placeholder="">
                    </div>
                    <label>Level</label>
                    <input  type="text"  name="level" class="form-control"
                            placeholder="" >

                    <label>Ket</label>
                    <input  type="text"  name="ket" class="form-control"
                            placeholder="" >
                      
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
