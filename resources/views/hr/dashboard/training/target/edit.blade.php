@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="row-md-8">
            
            
            <div class="col-md-6">
                <div class="card card-plain rounded-0 border-primary">
                    <div class="card-header pb-0 bg-primary rounded-0">
                            <h4 class="font-weight-bolder text-light">Edit Target</h4>
                        </div>
                    <form action="{{ url('/hr/dashboard/training/target/update', $data->id) }}" method="POST">
                        @csrf
                        @csrf
                        <table class="table ">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Tgl Input</label></td>
                            <td><input class="form-control form-control-sm" type="date" name="tgl_input" value="{{ $data->tgl_input }}"></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian</label></td>
                            <td><select class="form-control form-control-sm" type="text" name="bagian" value="">
                                <option value="{{ $data->bagian }}">{{ $data->bagian }}</option>
                                @foreach ($bagian as $item_bg)
                                <option value="{{ $item_bg->bagian }}">{{ $item_bg->bagian }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Jml Jam Target</label></td>
                            <td><input class="form-control form-control-sm" type="text" name="jumlah_jam" value="{{ $data->jumlah_jam }}"></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Periode Awal</label></td>
                            <td><input class="form-control form-control-sm" type="date" name="periode_awal" value="{{ $data->periode_awal }}"></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Periode Akhir</label></td>
                            <td><input class="form-control form-control-sm" type="date" name="periode_akhir" value="{{ $data->periode_akhir }}"></td>
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
