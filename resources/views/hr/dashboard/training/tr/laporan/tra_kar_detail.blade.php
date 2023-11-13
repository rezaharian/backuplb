@extends('hr.dashboard.layout.layout')


@section('content')
    <div class="container  px-3">
        <div class="row">
            <div class="card px-3 ">
                <div class="row">
                    <div class="col-md-10">

                    </div>
                    <div class="col-md-2 text-end mt-1">
                        <a class="" target="_blank"
                            href="{{ route('hr.training.tra_kar_print_detail', ['id' => $data->no_payroll, 'dtv1' => $dtv1, 'dtv2' => $dtv2,'jenis'=>$jenis]) }}"><i
                                class="fas fa-print"></i></a>
                    </div>

                    <div class="col-md-12 mt-2">

                        <div class="row">
                            <div class="col-md-8">

                                <table class="ms-4 mb-2 fw-bold" style="border: 0 ; font-size:10pt;">
                                    <tr>
                                        <td>Periode </td>
                                        <td> :</td>
                                        <td></td>
                                        <td>{{ $dtv1 }} Sampai {{ $dtv2 }}</td>
                                    <tr>
                                        <td>No Reg </td>
                                        <td>:</td>
                                        <td></td>
                                        <td>{{ $data->no_payroll }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama </td>
                                        <td>:</td>
                                        <td></td>
                                        <td>{{ $data->nama_asli }} </td>
                                    </tr>
                                    <tr>
                                        <td>Posisi </td>
                                        <td>:</td>
                                        <td></td>
                                        <td>{{ $data->jabatan }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 ">
                                <div class="card position-absolute top-2 end-6 " style="width: 100px; height: 100px;">
                                    <a href="/image/fotos/{{ $data->foto }}" target="_blank">
                                        <img src="/image/fotos/{{ $data->foto }}" class="card-img-top" alt="...">
                                    </a>

                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table mt-3  table-responsive" style="font-size:10pt;">
                                <thead>
                                    <tr>
                                        <th style="width: 30 ;">No</th>
                                        <th style="width: 70 ;">Tanggal</th>
                                        <th style="width:90">Training/Kompetensi</th>
                                        <th style="width:65">Trainer</th>
                                        <th style="width:65">Level</th>
                                        <th style="width:40">Pre test</th>
                                        <th style="width:70">Post test</th>
                                        <th style="width:50">Total Jam</th>
                                        <th style="width:50">Ket</th>
                                    </tr>
                                </thead>
                                @foreach ($data_t as $item)
                                    <tbody>
                                        <tr>
                                            <td style="text-align:center; ">{{ $loop->iteration }}</td>
                                            <td>{{ $item->train_date }}</td>
                                            <td style="word-break: break-all;">{{ $item->train_tema }}</td>
                                            <td>{{ $item->pemateri }}</td>
                                            <td>{{ $item->level }}</td>
                                            <td style="text-align:center; ">{{ $item->nilai_pre }}</td>
                                            <td style="text-align:center; ">{{ $item->nilai }}</td>
                                            <td style="text-align:center; ">{{ $item->totaljam }}</td>
                                            <td style="text-align:center; ">{{ $item->keterangan }}</td>
                                        </tr>
                                    </tbody>
                                @endforeach
                                <tbody style="text-align: center;">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Rata2</b></td>
                                        <td><b>Rata2</b></td>
                                        <td><b>Total Jam</b></td>
                                    </tr>
                                </tbody>
                                <tbody style="text-align: center; ">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>{{ $data_total->totalnilaipre }}</b></td>
                                        <td><b>{{ $data_total->totalnilai }}</b></td>
                                        <td><b>{{ $data_total->totaljam }}</b></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endsection
