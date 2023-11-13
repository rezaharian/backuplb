@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-plain rounded-0 border-primary">

                <div class="card-header pb-0 text-left bg-primary rounded-0">
                    <h4 class="font-weight-bolder text-light">Detail Materi</h4>
            </div>
                <form action="{{ url('/hr/dashboard/training/materi/update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <table class="table ">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Kode Materi
                                </label>
                            </td>
                            <td>
                               : {{ $data->kode_materi }}
                            </td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian </label>
                            </td>
                            <td>
                                : {{ $data->bagian }}
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Materi </label>
                            </td>
                            <td>
                                <textarea name="" class="form-control form-control-sm  text-secondary" id="" cols="30" rows="10">{{ $data->materi }}         

                                </textarea>
                                                </td>
                        </tr>
                       
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Keterangan
                                </label>
                            </td>
                            <td>
                                : {{ $data->keterangan }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>

                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <small class="fw-bold text-secondary">jika preview tidak muncul, silahkan reload atau unduh File disini:
                <a href="{{ $url }}" target="_blank">File</a>

            </small>
        </div>
        <div class="col-md-8">
            {{-- <iframe src="https://docs.google.com/gview?url={{ $url }}&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
            <iframe src="https://docs.google.com/gview?url={{ url('/uploads/materi/'.$data->file_materi) }}&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
            --}}

            <iframe width="100%" height="500px" src="https://docs.google.com/gview?url={{ url('/uploads/materi/'.$data->file_materi) }}&embedded=true"></iframe>




    </div>

    <style>
        td{
            color: rgb(120, 119, 119);
        }
    </style>
@endsection

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