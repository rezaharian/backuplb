<img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:17%;">


<h5 style="text-align: center" >{{ strtoupper('RINGKASAN ABSENSI KARYAWAN PT.EXTRUPACK') }} <br> DARI BULAN
    {{ strtoupper($bulanAwal) }} S.D. {{ strtoupper($bulanAkhir) }} {{ $tahun }}</h5>

<p class="m-1 " style="font-size: 10pt">Bagian : {{ $peg->bagian }}</p>
<div class="table-responsive">
    <table class="m-1 text-center" border="1" style="font-size: 8pt; max-width: 100%; border-collapse: collapse; text-align:center;">
        <thead>
            <tr>
                            <th style="padding: 2;">No</th>
                            <th style="padding: 2;">Reg</th>
                            <th style="padding: 2;">Nama</th>
                            <th style="padding: 2;">Msk Kerja</th>
                            <th style="padding: 2;">SK</th>
                            <th style="padding: 2;">SD</th>
                            <th style="padding: 2;">H</th>
                            <th style="padding: 2;">I</th>
                            <th style="padding: 2;">IPC</th>
                            <th style="padding: 2;">IC</th>
                            <th style="padding: 2;">M</th>
                            <th style="padding: 2;">Lmbt(x)</th>
                            <th style="padding: 2;">Lmbt(jam)</th>
                            <th style="padding: 2;">IPA(x)</th>
                            <th style="padding: 2;">IPA(jam)</th>
                            <th style="padding: 2;">DL</th>
                            <th style="padding: 2;">Cuti Besar</th>
                            <th style="padding: 2;">SCTB</th>
                            <th style="padding: 2;">SCB</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr >
                            <td style="padding: 1;">1</td>
                            <td style="padding: 1;">{{ $peg->no_payroll }}</td>
                            <td style="padding: 1;">{{ $peg->nama_asli }}</td>
                            <td style="padding: 1;">{{ date('d-m-Y', strtotime($peg->tgl_masuk)) }}</td>
                            <td style="padding: 1;"> {{ $SK }}</td>
                            <td style="padding: 1;"> {{ $SD }}</td>
                            <td style="padding: 1;"> {{ $H }}</td>
                            <td style="padding: 1;"> {{ $I }}</td>
                            <td style="padding: 1;"> {{ $IPC }}</td>
                            <td style="padding: 1;"> {{ $IC }}</td>
                            <td style="padding: 1;"> {{ $M }}</td>
                            <td style="padding: 1;"> {{ $lmbtx }}</td>
                            <td style="padding: 1;"> {{ $lmbtjm }}</td>
                            <td style="padding: 1;"> {{ $ipax }}</td>
                            <td style="padding: 1;"> {{ $ipajam }}</td>
                            <td style="padding: 1;"> {{ $dl }}</td>
                            <td style="padding: 1;"> {{ $icb }}</td>
                            <td style="padding: 1;"> {{ $SCTB }}</td>
                            <td style="padding: 1;"> {{ $SCB }}</td>
                        </tr>
                    </tbody>
                </table>