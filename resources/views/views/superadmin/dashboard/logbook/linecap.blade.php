@extends('superadmin.dashboard.layout.layout')

@section('content')


                      

<div class="col-md-4">
    <button type="button" class="btn btn-block btn-default mb-3 border" data-bs-toggle="modal"
        data-bs-target="#modal-form">Tambah</button>
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card card-plain">
                        <div class="card-header pb-0 text-left">
                            <h4 class="font-weight-bolder text-info text-gradient">Tambah Jalur</h4>
                        </div>
                        <div class="card-body">
                            <form role="form text-left" action="{{ url('/superadmin/dashboard/jalurcap/store') }}"
                                method="POST">
                                @csrf
                                <label>Jalur</label>
                                <div class="input-group mb-3">
                                    <input type="text"  name="nama" class="form-control"
                                        placeholder="Nama">
                                </div>
                                <label>Bagian</label>
                                <input  type="text"  name="jenis" class="form-control"
                                        placeholder="Jenis" value="Capping">
                                  
                                <div class="text-center">
                                    <button type="submit"
                                        class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-4">
        <div class="card card-frame">
            @error('nama')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
            @error('jenis')
              <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="card m-4">
                @foreach ($jalur as $item)
                <div class="card-body bg-gradient-primary border rounded">
                    <a href="/superadmin/dashboard/loogbokcap/{{ $item->nama }}/data">
                        <button type="button" class="btn btn-lg bg-gradient-info rounded-pill">{{ $item->nama }}</button>
                    </a>      
                </div>
                    @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-frame">

        </div>
    </div>

@endsection

