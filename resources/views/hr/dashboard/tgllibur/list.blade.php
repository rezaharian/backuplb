@extends('hr.dashboard.layout.layout')

@section('content')
 


    <div class="row">
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
            <div class="alert alert-info text-center px-5">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div class="col-md-4">
            <div class="card card-plain rounded-0 border-primary">
                <div class="card-header pb-0 text-left bg-primary rounded-0">
                    <h4 class="font-weight-bolder text-light">Tambah Hari Libur</h4>
                </div>
                <div class="card-body ">
                    <form action="/hr/dashboard/tgllibur/create" method="POST">
                        @csrf
                        <table class="table">
                            <div class="mb-2">
                                
                                    <label for="tgl_libur" class="form-control-label text-secondary m-0">Tgl Libur</label>
                                
                                    <input class="form-control form-control-sm" type="date" id="tgl_libur" name="tgl_libur">
                               
                            </div>
                            <div  class="mb-2">
                               
                                    <label for="keterangan" class="form-condivol-label text-secondary m-0">Ket Libur</label>
                               
                                    <textarea class="form-control form-control-sm" id="keterangan" name="keterangan"></textarea>
                               
                            </div>
                            <div>
                              
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                
                            </div>
                        </table>
                    </form>
                    
        </div>
    </div>
    </div>
    <div class="col-md-8">

        <div class="card card-plain   rounded-0 border-primary">
            <div style="height:500px;overflow:auto;">
                <table class="  align-items-center mb-0   ">
                    <thead
                        class=" bg-primary text-center text-uppercase  text-light shadow font-weight-bolder sticky-top  ">
                        <tr style="font-size: 8pt; background-color:rgb(5, 109, 255);" class="text-light">
                            <th style="width:1%;" scope="col">No</th>
                            <th style="width:10%;" scope="col">Tanggal Libur </th>
                            <th style="width:50%;" scope="col">Keterangan Libur</th>
                            <th style="width:10%;" scope="col">Opt</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 9pt;" class="text-secondary ">
                        @foreach ($tgllibur as $item)
                        <tr style="background-color: {{ $loop->iteration % 2 == 0 ? '#f2f2f2' : '#fff' }};">
                            <th>{{ $loop->iteration }}</th>
                                <td style="text-align: center;">{{ $item->tgl_libur }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td class="text-center">
                                    <div>
                                        <a href="{{ route('hr.tgllibur.delete', $item->id) }}"
                                            onclick="return confirm('Apakah Anda Yakin ?');"
                                            class="btn btn-sm m-0 text-danger"> <i class="fa-solid fa-trash"></i> </a>
                                        <a href="{{ route('hr.tgllibur.edit', $item->id) }}"
                                            class="btn btn-sm m-0 text-primary"> <i class="fa-solid fa-pen"></i> </a>
                                </td>
            </div>
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
    </style>

@endsection
