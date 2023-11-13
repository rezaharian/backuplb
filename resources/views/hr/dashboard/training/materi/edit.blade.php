@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-plain rounded-0 border-primary">

            <div class="card-header pb-0 text-left bg-primary rounded-0">
                <h4 class="font-weight-bolder text-light">Edit Materi</h4>
            </div>
                <form action="{{ url('/hr/dashboard/training/materi/update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <table class="table ">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Kode Materi
                                </label>
                            </td>
                            <td><input class="form-control form-control-sm" type="text" name="kode_materi"
                                    value="{{ $data->kode_materi }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Materi </label>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" type="text" name="materi" value="">{{ $data->materi }} </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian </label>
                            </td>
                            <td><select class="form-control form-control-sm" type="text" name="bagian" value="">
                                    <option value="{{ $data->bagian }}">{{ $data->bagian }}</option>
                                    @foreach ($bag as $item)
                                        <option value="{{ $item->bagian }}">{{ $item->bagian }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">File Materi
                                </label>
                            </td>
                            <td>

                                <input type="file" class="form-control form-control-sm" id="file-upload"
                                    name="file_materi">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Keterangan
                                </label>
                            </td>
                            <td><input class="form-control form-control-sm" type="text" name="keterangan"
                                    value="{{ $data->keterangan }}">
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
