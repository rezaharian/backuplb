@extends('hr.dashboard.layout.layout')

@section('content')
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid rgb(168, 166, 166);
            padding: 5px;
        }
    </style>

    <div class="container">
        <h6 class="text-center">{{ strtoupper('RINGKASAN KERAJINAN KARYAWAN PT.EXTRUPACK') }} <br> DARI
            {{-- {{ strtoupper($tglawal) }} S.D. {{ strtoupper($tglakhir) }} </h6> --}}

        <div class="card text-muted">
            <div class="table-responsive">
                <table class=" m-1  text-center bordered  table-sm" style="font-size: 8pt; max-width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No.Payr</th>
                            <th>Nama </th>
                            <th>Bagian</th>
                            <th>Gol</th>
                            <th>H</th>
                            <th>SK</th>
                            <th>SD</th>
                            <th>ITU</th>
                            <th>I</th>
                            <th>IPC</th>
                            <th>IC</th>
                            <th>M</th>
                            <th>LMBT(X)</th>
                            <th>LMBT(M)</th>
                            <th>IPA(x)</th>
                            <th>IPA(J)</th>
                            <th>PRM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $noPayroll => $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$noPayroll}}</td>
                                <td>{{$data['pegawai']->nama_asli}}</td>
                                <td>{{$data['pegawai']->bagian}}</td>
                                <td>{{$data['pegawai']->golongan}}</td>
                                <td>{{$data['H']}}</td>
                                <td>{{$data['SK']}}</td>
                                <td>{{$data['SD']}}</td>
                                <td>{{$data['ITU']}}</td>
                                <td>{{$data['I']}}</td>
                                <td>{{$data['IPC']}}</td>
                                <td>{{$data['IC']}}</td>
                                <td>{{$data['M']}}</td>
                                <td>{{$data['lmbtx']}}</td>
                                <td>{{$data['lmbtjm']}}</td>
                                <td>{{$data['ipax']}}</td>
                                <td>{{$data['ipajam']}}</td>
                                <td>{{$data['PRM']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
