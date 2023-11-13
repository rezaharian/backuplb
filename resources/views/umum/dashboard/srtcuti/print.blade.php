<title>Permohonan Cuti</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        /* Menggunakan jenis font Poppins */
    }
</style>
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin:0;">
        <H6 style="text-align: right; font-family: 'Poppins', sans-serif; margin: 0;">Bekasi,  {{ date('d-m-Y', strtotime($cuti->ct_tgl)) }}</H6>
        <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:20%; float: left; margin: 0;">
    </div>
</div>

<div class="body " style="padding: 6%; margin-left:3%;">

    <H4 style="text-align: center; font-family: 'Poppins', sans-serif; margin-top:8%;">PERMOHONAN IJIN TIDAK MASUK KERJA/CUTI</H4>

    <div style="font-size:9pt;">
        <table style="margin-top: 4%; margin-bottom:4%;">
            <tr>
                <td>Nama</td>
                <td>: {{ $cuti->ct_nam }}</td>
            </tr>
            <tr>
                <td>No Reg</td>
                <td>: {{ $cuti->ct_reg }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $cuti->ct_jbt }}</td>
            </tr>
        </table>
        <p>Mengajukan permohonan ijin tidak masuk kerja/cuti sbb.;</p>
        <table>
            <tr>
                <td>Selama</td>
                <td>: {{ $cuti->ct_jml }} Hari</td>
            </tr>
            <tr>
                <td></td>
                @if ($cuti->ct_dr1)
                <td> Mulai dari tgl. {{ date('d-m-Y', strtotime($cuti->ct_dr1)) }} sampai dengan tgl. {{ date('d-m-Y', strtotime($cuti->ct_sd1)) }} <br>
               @endif
                @if ($cuti->ct_dr2)
                dan mulai dari tgl. {{ date('d-m-Y', strtotime($cuti->ct_dr2)) }} sampai dengan tgl. {{ date('d-m-Y', strtotime($cuti->ct_sd2)) }} </td>
                @endif
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td>Alasan</td>
                <td> : {{ $cuti->ct_not }}</td>
            </tr>
            <tr>
                <td></td>
                <td>________________________________________________________________________ <br>
                    ________________________________________________________________________ <br>
                    ________________________________________________________________________</td>
            </tr>
        </table>

        <style>
            .table-cell {
                width: 32%;
                /* Setiap kolom mengambil 33.33% lebar dari parent */
                text-align: center;
                /* Teks diatur ke tengah dalam setiap sel kolom */
                display: inline-block;
                /* Menampilkan sel tabel secara inline */
            }

            .table-cell2 {
                width: 49%;
                /* Setiap kolom mengambil 33.33% lebar dari parent */
                text-align: center;
                /* Teks diatur ke tengah dalam setiap sel kolom */
                display: inline-block;
                /* Menampilkan sel tabel secara inline */
            }
        </style>

        <div class="table-container" style="margin-top: 5%; text-align:center;">
            <div class="table-cell">
                <table>
                    <tr>
                        <td>Menyetujui</td>
                    </tr>
                </table>
            </div>
            <div class="table-cell">
                <table>
                    <tr>
                        <td>Mengetahui</td>
                    </tr>
                </table>
            </div>
            <div class="table-cell">
                <table>
                    <tr>
                        <td>Pemohon</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="table-container" style="margin-top: 13%; ">
            <div class="table-cell">
                <table>
                    <tr>
                        <td>{{ $cuti->setuju }} </td>
                    </tr>
                    <tr>
                        <td><hr style="margin: 0; padding:0; width:150px;"></td>
                    </tr>
                    <tr>
                        <td>Direktur/Manager Ybs.</td>
                    </tr>
                </table>
            </div>
            <div class="table-cell">
                <table>
                    <tr>
                        <td>{{ $cuti->ket_atas }}</td>
                    </tr>
                    <tr>
                        <td><hr style="margin: 0; padding:0; width:150px;"></td>
                    </tr>
                    <tr>
                        <td>Atasan Ybs.</td>
                    </tr>
                </table>
            </div>
            <div class="table-cell">
                <table>
                    <tr>
                        <td>{{ $cuti->pemohon }}</td>
                    </tr>
                    <tr>
                        <td><hr style="margin: 0; padding:0; width:150px;"></td>
                    </tr>
                    <tr>
                        <td>Pemohon</td>
                    </tr>
                </table>
            </div>
            <p style="margin-top: 2%;">Catatan Bag. HR & GA :</p>
            <div class="table-cell" style="margin-top: 2%;">
                <table>
                    <tr>
                        <td> <small> Absensi Th ini s/d. tgl {{ date('d-m-Y', strtotime($cuti->ct_tgl)) }} </small></td>
                    </tr>
                    <tr>
                        <td>
                            <table style="border-collapse: collapse;">
                                <tr>
                                    <td style="border: 1pt solid black; padding: 8px;">S</td>
                                    <td style="border: 1pt solid black; padding: 8px;">I</td>
                                    <td style="border: 1pt solid black; padding: 8px;">C</td>
                                    <td style="border: 1pt solid black; padding: 8px;">M</td>
                                    <td style="border: 1pt solid black; padding: 8px;">T</td>
                                    <td style="border: 1pt solid black; padding: 8px;">IPA</td>
                                </tr>
                                <tr>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->skt }}</td>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->ijn }}</td>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->cti }}</td>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->mkr }}</td>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->tlb }}</td>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->ipa }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-cell" >
                <table>
                    <tr>
                    </tr>
                </table>
            </div>
            <div class="table-cell" >
                <table>
                    <tr>
                        <td> <small> Sisa cuti s/d. tgl. {{ date('d-m-Y', strtotime($cuti->ct_tgl)) }} </small></td>
                    </tr>
                    <tr>
                        <td>
                            <table style="border-collapse: collapse;">
                                <tr>
                                    <td style="border: 1pt solid black; padding: 8px;">Sc lalu</td>
                                    <td style="border: 1pt solid black; padding: 8px;">Sc Skr</td>
                                    <td style="border: 1pt solid black; padding: 8px;">Sc Besar</td>
                                </tr>
                                <tr>
                                    <td style="border: 1pt solid black; padding: 8px;">0</td>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->scs }}</td>
                                    <td style="border: 1pt solid black; padding: 8px;">{{ $cuti->scb }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
             <div class="table-cell" style="margin-top: 4%;">
                <table>
                    <tr>
                        <td><small>Keterangan :</small></td>
                    </tr>
                    <tr>
                        <td>
                            <table style="font-size: 8pt;">
                                <tr>
                                    <td>S</td>
                                    <td>: Sakit</td>
                                </tr>
                                <tr>
                                    <td>I</td>
                                    <td>: Ijin</td>
                                </tr>
                                <tr>
                                    <td>C</td>
                                    <td>: Cuti</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>: Mangkir</td>
                                </tr>
                                <tr>
                                    <td>T</td>
                                    <td>: Terlambat</td>
                                </tr>
                                <tr>
                                    <td>IPA</td>
                                    <td>: Ijin Pulang Awal</td>
                                </tr>
                                <tr>
                                    <td>Sc lalu</td>
                                    <td>: Sisa cuti th lalu</td>
                                </tr>
                                <tr>
                                    <td>Sc Skr</td>
                                    <td>: Sisa cuti th ini</td>
                                </tr>
                                <tr>
                                    <td>Sc Besar</td>
                                    <td>: Sisa cuti besar</td>
                                </tr>
                                <tr><td></td></tr>
                                <tr>
                                    <td>
                                        CC. Arsip Ybs.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
             <div class="table-cell ">
                <table  style="text-align: center;" >
                    <tr>
                    </tr>
                </table>
             </div>
             <div class="table-cell ">
                <table  style="text-align: center;" >
                    <tr>
                        <td>Dept. HR & GA</td>
                    </tr>
                    <tr>
                        <td>Mengetahui</td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td><hr style="margin: 0; padding:0; width:150px;"></td>
                    </tr>
                    
                    <tr>
                        <td>Manager HR & GA</td>
                    </tr>
                </table>
            </div>
        </div>


    </div>

</div>
