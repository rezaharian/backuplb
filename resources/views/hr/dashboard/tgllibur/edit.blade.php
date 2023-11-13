@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-6">
        <div class="card card-plain rounded-0 border-primary">

            <div class="card-header pb-0 text-left bg-primary rounded-0">
                <h4 class="font-weight-bolder text-light">Edit Hari Libur</h4>
            </div>
                <form action="{{ url('/hr/dashboard/tgllibur/update', $tgllibur->id) }}" method="POST">
                    @csrf
                    <table class="table ">
                       
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Tanggal Libur </label>
                            </td>
                            <td>
                                <input class="form-control form-control-sm" type="date" name="tgl_libur" value="{{ $tgllibur->tgl_libur }}">
                            </td>
                        </tr>
                     
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Keterangan Libur
                                </label>
                            </td>
                            <td><textarea class="form-control form-control-sm" type="text" name="keterangan"
                                    value="{{ $tgllibur->keterangan }}">{{ $tgllibur->keterangan }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>

                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            </td>
                        </tr>
                    </table>
                </form>
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
