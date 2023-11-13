@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="card card-plain rounded-0 border-primary">
          <div class="row mt-2 ">
            
            <div class="col-md-1  text-center">
                    <div>
                        <a href="{{ route('hr.absen.edit', $data->id) }}">
                            <i class="fas fa-pen"></i></a>
                    </div>
                    <div class="div">
                        <a href="{{ route('hr.absen.delete', $data->id) }}"
                            onclick="return confirm('Apakah Anda Yakin ?');"><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="div">
                        <a href="{{ route('hr.absen.print', ['id'=>$data->id, 'bln'=>$bln, 'thn'=>$thn]) }}" target="_blank"><i class="fas fa-print"></i></a>
                    </div>
                </div>
                <div class="col-md-5">
                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">NO Payroll
                                </label>
                            </td>
                            <td>
                                <input id="no_payroll" class="form-control form-control-sm" name="no_payroll" readonly
                                    value="{{ $data->no_payroll }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Nama </label>
                            </td>
                            <td><input class="form-control form-control-sm" id="nama_asli" type="text" name="nama_asli" readonly
                                    value="{{ $data->nama_asli }}">
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="col-md-5">
                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Masuk Kerja
                                </label>
                            </td>
                            <td><input class="form-control form-control-sm" id="tgl_masuk" type="text" name="tgl_masuk" readonly
                                    value="{{ $data->tgl_masuk }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian </label>
                            </td>
                            <td><input class="form-control form-control-sm" id="bagian" type="text" name="bagian" readonly
                                    value="{{ $data->bagian }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card-plain">
                <table class="table  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                    <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Kode Absen</th>
                        <th>Kelompok Absen</th>
                        <th>Ambil Cuti Thn</th>
                        <th>Keterangan</th>
                    </thead>
                    <tbody id="" style="font-size: 11pt;">
                        @foreach ($data_d as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->tgl_absen }}</td>
                                <td>{{ $item->jns_absen }}</td>
                                <td>{{ $item->dsc_absen }}</td>
                                <td>{{ $item->thn_jns }}</td>
                                <td>{{ $item->keterangan }}</td>
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
