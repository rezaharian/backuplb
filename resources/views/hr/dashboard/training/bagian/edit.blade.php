@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
    <div class="col-md-4 ms-3">
       

        <div class="card card-plain rounded-0 border-primary">
            <div class="card-header pb-0 bg-primary rounded-0">
                <h4 class="font-weight-bolder text-light">Edit Bagian</h4>
            </div>
            <div class="card-body">
                <form role="form text-left" action="{{ url('/hr/dashboard/training/bagian/update', $bagian->id) }}"
                    method="POST">
                    @csrf
                    <label>Bagian</label>
                    <div class="input-group mb-3">
                        <input type="text" value="{{ $bagian->bagian }}" name="bagian" class="form-control form-control-sm"
                            placeholder="bagian">
                    </div>
                    <label>Departemen</label>
                    <input  type="text" value="{{ $bagian->departemen }}"  name="departemen" class="form-control form-control-sm"
                            placeholder="departemen" >

                    <label>Seksi</label>
                    <input  type="text" value="{{ $bagian->seksi }}"  name="seksi" class="form-control form-control-sm"
                            placeholder="seksi" >
                      
                    <div class="">
                        <button type="submit"
                            class="btn btn-primary btn-sm mt-2 mb-0">Simpan</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
    </div>

    <style>
        .form-control{
            border-radius: 0;
        }
       
        .card-plain {
        opacity: 0;
        animation-name: fade-in;
        animation-duration: 0.3s;
        animation-fill-mode: forwards;
        animation-timing-function: ease-in-out;
        animation-delay: 0.3s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
@endsection
