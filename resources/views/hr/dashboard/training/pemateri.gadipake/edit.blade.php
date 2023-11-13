@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
    <div class="col-md-4 ms-3">
       
        <div class="card card-plain">
            <div class="card-header pb-0 text-left">
                <h4 class="font-weight-bolder text-info text-gradient">Edit Trainer</h4>
            </div>
            <div class="card-body">
                <form role="form text-left" action="{{ url('/hr/dashboard/training/pemateri/update', $pemateri->id) }}"
                    method="POST">
                    @csrf
                    <label>Nama</label>
                    <div class="input-group mb-3">
                        <input type="text" value="{{ $pemateri->nama }}" name="nama" class="form-control"
                            placeholder="nama">
                    </div>
                    <label>Level</label>
                    <input  type="text" value="{{ $pemateri->level }}"  name="level" class="form-control"
                            placeholder="level" >

                    <label>Ket</label>
                    <input  type="text" value="{{ $pemateri->ket }}"  name="ket" class="form-control"
                            placeholder="ket" >
                      
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
