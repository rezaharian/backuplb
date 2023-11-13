@extends('umum.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-1  text-center">
                <div>
                    <a href="{{ route('umum.overt.edit', $data->id) }}">
                        <i class="fas fa-pen"></i></a>
                </div>

                <div class="div">

                    <a href="{{ route('umum.overt.delete', $data->id) }}"
                        onclick="return confirm('Apakah Anda Yakin ?');"><i class="fas fa-trash"></i></a>
                </div>
                <div>
                    <a href="{{ route('umum.overt.print', $data->id) }}" target="_blank">
                        <i class="fas fa-print"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-5">
                <table class="table">
                <tr>
                    <td><label for="example-text-input" class="form-control-label  text-secondary">Tanggal </label></td>
                    <td>
                        <input type="date" readonly  class="form-control form-control-sm" name="ot_dat" value="{{ $data->ot_dat }}">
                    </td>
                </tr>
                <tr>
                    <td><label for="example-text-input" class="form-control-label  text-secondary">Hari </label> </td>
                    <td>
                        <input type="text" readonly  class="form-control form-control-sm" name="ot_day" value="{{ $data->ot_day }}">
                 
                     </td>
                </tr>
            </table>

            </div>
            <div class="col-md-5">
                <table class="table">
                <tr>
                    <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian </label> </td>
                    <td>
                        <input type="text" readonly  class="form-control form-control-sm" name="ot_bag" value="{{ $data->ot_bag }}">                     
                      </td>
                </tr>
                <tr>
                    <td><label for="example-text-input" class="form-control-label  text-secondary">Pekerjaan </label>
                    </td>
                    <td><input readonly class="form-control form-control-sm"  type="text" name="keterangan"
                        value="{{ $data->keterangan }}">
                    </td>
                </tr>

                
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                    <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Mulai</th>
                        <th>Akhir</th>
                        <th>Lemburan</th>
                        <th>No SPK</th>
                        <th>Line</th>
                        <th>Tugas</th>
                    </thead>
                    <tbody id="">
                        @foreach ($data_d as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_payroll }}</td>
                                <td>{{ $item->nama_asli }}</td>
                                <td>{{ $item->ot_hrb }}</td>
                                <td>{{ $item->ot_hre }}</td>
                                <td>{{ $item->catatan }}</td>
                                <td>{{ $item->spk_nomor }}</td>
                                <td>{{ $item->line }}</td>
                                <td>{{ $item->tugas }}</td>
    
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>



    </div>
@endsection
