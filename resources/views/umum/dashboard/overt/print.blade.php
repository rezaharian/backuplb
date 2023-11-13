<!DOCTYPE html>
<html>

<head>
    <title>Daftar Hadir</title>

    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 8pt;

        }
   
    </style>

</head>

<body>

    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:20%;">

    <strong style="font-family: Arial, Helvetica, sans-serif;">
        <center>KESEPAKATAN LEMBUR</center>
    </strong>
    <br>

    <div>
        <div>
            <table
                style="background: rgb(255, 255, 255); border:0;  text-transform: uppercase;  font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <td style="width:30%; ">Nomer Urut </td>
                    <td> : <strong> {{ $data->ot_cod }} </strong></td>
                </tr>
                <tr>
                    <td>Tanggal </td>
                    <td> : <strong> {{ $data->ot_dat }} </strong></td>
                </tr>
                <tr>
                    <td>Bagian </td>
                    <td> : <strong> {{ $data->ot_bag }} </strong> </td>
                </tr>
                <tr>
                    <td>Untuk Pekerjaan </td>
                    <td> : <strong> {{ $data->keterangan }} </strong></td>
                </tr>

            </table>
        </div>

    </div>
    <br>
    <div style="position: relative; width: 100%; min-height:65%; font:normal normal 12px Verdana,Arial,Sans-Serif;  ">
        Keterangan :
        <table width="100%"  style="border-collapse:collapse; font:normal normal 10px Verdana,Arial,Sans-Serif; ">
            <hr style="width:2190%">
            <thead style=" ">
                <tr>
                    <th width="30px" style="text-align: center">No</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Jam</th>
                    <th>Keterangan</th>
                    <th>TTD Masuk</th>
                    <th>TTD Keluar</th>
                </tr>
            </thead>
            <hr style="width:2190%">
            <tbody>
                @php $i=1 @endphp
                @foreach ($data_d as $item)
                    <tr style="margin-top: 4% ">
                        <td style="text-align: center">{{ $i++ }}</td>
                        <td>{{ $item->no_payroll }}</td>
                        <td>{{ $item->nama_asli }}</td>
                        <td>{{ $item->ot_hrb }} - {{ $item->ot_hre }}</td>
                        <td>{{ $item->tugas }}</td>
                        <td>___________</td>
                        <td>___________</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="text-align: left; margin-right:5%;  ">
        <hr style="margin: 0; padding:0;">
        <p>Yang Menyetujui, &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;   
            Yang Menyetujui, &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;   &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; 
             Bekasi, {{ $tgl }} <br> 
             Manager Personalia &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
            Manager Ybs &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
            Yang Mengajukan, </p> <br>
    </div>



</body>

</html>
