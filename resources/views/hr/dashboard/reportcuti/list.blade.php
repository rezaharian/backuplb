@extends('hr.dashboard.layout.layout')

@section('content')
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid rgb(168, 166, 166);
        padding: 5px;
    }
</style>

    <div class="container">
        <h6 class="text-center">{{ strtoupper('RINGKASAN ABSENSI KARYAWAN PT.EXTRUPACK') }} <br> DARI BULAN {{ strtoupper($bulanAwal) }} S.D. {{ strtoupper($bulanAkhir) }}  {{ $tahun }}</h6>
    
        <div class="card text-muted">
            <p class="m-1 " style="font-size: 10pt">Bagian : {{ $peg->bagian }}</p>
            <div class="table-responsive">
                <table class=" m-1  text-center bordered  table-sm" style="font-size: 8pt; max-width: 100%;">
                    <thead>
                        <tr>
                            <th class="m-0 p-0">No</th>
                            <th class="m-0 p-0">Reg</th>
                            <th class="m-0 p-0">Nama</th>
                            <th class="m-0 p-0">Msk Kerja</th>
                            <th class="m-0 p-0">SK</th>
                            <th class="m-0 p-0">SD</th>
                            <th class="m-0 p-0">H</th>
                            <th class="m-0 p-0">I</th>
                            <th class="m-0 p-0">IPC</th>
                            <th class="m-0 p-0">IC</th>
                            <th class="m-0 p-0">M</th>
                            <th class="m-0 p-0">Lmbt(x)</th>
                            <th class="m-0 p-0">Lmbt(jam)</th>
                            <th class="m-0 p-0">IPA(x)</th>
                            <th class="m-0 p-0">IPA(jam)</th>
                            <th class="m-0 p-0">DL</th>
                            <th class="m-0 p-0">Cuti Besar</th>
                            <th class="m-0 p-0">SCTB</th>
                            <th class="m-0 p-0">SCB</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $peg->no_payroll }}</td>
                            <td>{{ $peg->nama_asli }}</td>
                            <td>{{ date('d-m-Y', strtotime($peg->tgl_masuk)) }}</td>
                            <td> {{ $SK }}</td>
                            <td> {{ $SD }}</td>
                            <td> {{ $H }}</td>
                            <td> {{ $I }}</td>
                            <td> {{ $IPC }}</td>
                            <td> {{ $IC }}</td>
                            <td> {{ $M }}</td>
                            <td> {{ $lmbtx }}</td>
                            <td> {{ $lmbtjm }}</td>
                            <td> {{$ipax}}</td>
                            <td> {{ $ipajam }}</td>
                            <td> {{ $dl }}</td>
                            <td> {{ $icb }}</td>
                            <td> {{ $SCTB }}</td>
                            <td> {{ $SCB }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
