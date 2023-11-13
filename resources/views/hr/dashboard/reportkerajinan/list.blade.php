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
                            <th>NoPayr</th>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th>Gol</th>
                            <th> SK</th>
                            <th> SD</th>
                            <th> HD</th>
                            <th> IPC</th>
                            <th> ITU</th>
                            <th> I</th>
                            <th> IC</th>

                            <th> M</th>
                            <th>LMBT X </th>
                            <th>LMBT M </th>
                            <th>IPA X</th>
                            <th>IPA M</th>
                            <th>PRM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $peg->no_payroll }}</td>
                            <td>{{ $peg->nama_asli }}</td>
                            <td>{{ $peg->bagian }}</td>
                            <td>{{ $peg->golongan }}</td>
                            <td>{{ $SK }}</td>
                            <td>{{ $SD }}</td>
                            <td>{{ $H }}</td>
                            <td>{{ $IPC }}</td>
                            <td>{{ $ITU }}</td>
                            <td>{{ $I }}</td>
                            <td>{{ $IC }}</td>

                            <td>{{ $M }}</td>
                            <td>{{ $lmbtx }}</td>
                            <td>{{ $lmbtjm }}</td>
                            <td>{{ $ipax }}</td>
                            <td>{{ $ipajam }}</td>
                            <td>{{ $PRM }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
