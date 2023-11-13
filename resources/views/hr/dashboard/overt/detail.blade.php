@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="card border-primary rounded-0 pt-1 card1">
            <div class="row">
                <div class="col-md-1 text-center">
                    <div>
                        <a href="{{ route('hr.overt.edit', $data->id) }}">
                            <i class="fas fa-pen"></i>
                        </a>
                    </div>
                    <div class="div">
                        <a href="{{ route('hr.overt.delete', $data->id) }}" onclick="return confirm('Apakah Anda Yakin ?');">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('hr.overt.print', $data->id) }}" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-5">
                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Tanggal </label>
                            </td>
                            <td>
                                <input type="date" readonly class="form-control form-control-sm rounded-0" name="ot_dat"
                                    value="{{ $data->ot_dat }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Hari </label>
                            </td>
                            <td>
                                <input type="text" readonly class="form-control form-control-sm rounded-0" name="ot_day"
                                    value="{{ $data->ot_day }}">
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-5">
                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian </label>
                            </td>
                            <td>
                                <input type="text" readonly class="form-control form-control-sm rounded-0" name="ot_bag"
                                    value="{{ $data->ot_bag }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Pekerjaan
                                </label>
                            </td>
                            <td><input readonly class="form-control form-control-sm rounded-0" type="text" name="keterangan"
                                    value="{{ $data->keterangan }}">
                            </td>
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="card border-primary rounded-0 card2">
                    <table class="table table-sm align-items-center mb-0 table-striped table-hover   " style="width:100%;">
                        <thead class="bg-primary  text-center text-light">
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
                        <tbody id="" style="font-size: 9pt">
                            @foreach ($data_d as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
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



    </div>

    <style>
        thead {
            font-size: 10pt;
        }

        thead th {
            padding: 10px;
        }
        /*  */

        .card1, .card2 {
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
