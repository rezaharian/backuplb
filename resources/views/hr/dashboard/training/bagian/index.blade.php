@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-4">

            <div class="card card-plain rounded-0 border-primary">
                <div class="card-header pb-0 bg-primary rounded-0">
                    <h4 class="font-weight-bolder text-light   ">Tambah Bagian</h4>
                </div>
                <div class="card-body">
                    <form role="form text-left" action="{{ url('/hr/dashboard/training/bagian/store') }}" method="POST">
                        @csrf
                        <div>

                            <label>Bagian</label>
                            <div class="input-group mb-3">
                                <input type="text" name="bagian" class="form-control  form-control-sm" placeholder="bagian">
                            </div>
                            <label>Departemen</label>
                            <input type="text" name="departemen" class="form-control  form-control-sm" placeholder="departemen">
                            
                            <label>Seksi</label>
                            <input type="text" name="seksi" class="form-control  form-control-sm" placeholder="seksi">
                        </div>

                       
                            <button type="submit"
                                class="btn  bg-primary text-light btn-cm btn-sm mt-2 mb-0">Simpan</button>
                     
                    </form>
                </div>
            </div>


        </div>
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
                <div class="alert alert-success ">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="card card-plain rounded-0 border-primary">
                <div style="height:500px;overflow:auto;">
                    <table class="table table-sm" style="font-size: 10pt;">
                        <thead class="bg-primary text-light sticky-top ">
                            <tr class="fw-bold">
                                <th class="span" width="24">NO</th>
                                <th  width="240">BAGIAN</th>
                                <th  width="240">DEPARTEMENT</th>
                                <th  width="100">SEKSI</th>
                                <th  width="100">OPTION</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9pt;" class="text-secondary">
                            @foreach ($bagians as $bag)
                                <tr class=" text-secondary">
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $bag->bagian }}</td>
                                    <td>{{ $bag->departemen }}</td>
                                    <td>{{ $bag->seksi }}</td>
                                    <td class="text-center">
                                        <form class="m-0" onsubmit="return confirm('Apakah Anda Yakin ?');"
                                            action="{{ route('hr.bagian.delete', $bag->id) }}" method="POST">
                                            <a href="{{ route('hr.bagian.edit', $bag->id) }}"
                                                class="btn btn-sm m-0 text-primary"><i class="fas fa-pen"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"  class="btn btn-sm m-0 text-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

  
  

    .btn.btn-sm:hover {
        color: white;
    }

    .btn.btn-sm:hover .fas.fa-pen {
        color: white;
    }

    .btn.btn-sm:hover .fas.fa-trash {
        color: white;
    }

    .btn.btn-sm:hover.text-primary {
        background-color: blue;
    }

    .btn.btn-sm:hover.text-danger {
        background-color: red;
    }
</style>

@endsection


