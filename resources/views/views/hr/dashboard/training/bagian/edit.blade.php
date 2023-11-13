@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
    <div class="col-md-4 ms-3">
       
        <div class="card card-plain">
            <div class="card-header pb-0 text-left">
                <h4 class="font-weight-bolder text-info text-gradient">Tambah Bagian</h4>
            </div>
            <div class="card-body">
                <form role="form text-left" action="{{ url('/hr/dashboard/training/bagian/update', $bagian->id) }}"
                    method="POST">
                    @csrf
                    <label>Bagian</label>
                    <div class="input-group mb-3">
                        <input type="text" value="{{ $bagian->bagian }}" name="bagian" class="form-control"
                            placeholder="bagian">
                    </div>
                    <label>Departemen</label>
                    <input  type="text" value="{{ $bagian->departemen }}"  name="departemen" class="form-control"
                            placeholder="departemen" >

                    <label>Seksi</label>
                    <input  type="text" value="{{ $bagian->seksi }}"  name="seksi" class="form-control"
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
