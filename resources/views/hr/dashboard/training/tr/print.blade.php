<!DOCTYPE html>
<html>

<head>
    <title>Daftar Hadir</title>

    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 10pt;
        }
    </style>
    
</head>

<body>

    <img  src="{{public_path ("/image/logos/logo-ext.png" ) }}" alt="" style="width:20%;">

    <strong style="font-family: Arial, Helvetica, sans-serif;">
        <center>DAFTAR HADIR</center>
    </strong>
    <br>

    <div>
        <div>
            <table style="background: rgb(255, 255, 255); border:0;  text-transform: uppercase;  font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <td style="width:30%; ">Nama Pelatihan </td>
                    <td> : <strong> {{ $viewew->pltran_nam }} </strong></td>
                </tr>
                <tr>
                    <td>Tanggal </td>
                    <td> : <strong> {{ $viewew->train_date }} </strong></td>
                </tr>
                <tr>
                    <td>Waktu </td>
                    <td> : <strong> {{ $viewew->jam }} </strong> </td>
                </tr>
                <tr>
                    <td>Tempat </td>
                    <td> : <strong> {{ $viewew->tempat }} </strong></td>
                </tr>
                <tr>
                    <td>Instruktur </td>
                    <td> : <strong> {{ $viewew->pemateri }} </strong></td>
                </tr>
                <tr>
                    <td>Materi </td>
                    <td> : <strong> {{ $viewew->train_tema }} </strong></td>
                </tr>
            </table>
        </div>

    </div>
    <br>
    <div style="position: relative; width: 100%; min-height:46%; border: 1px solid rgb(0, 0, 0); ">
        <table border="1" width="100%"
            style="border-collapse:collapse; font:normal normal 12px Verdana,Arial,Sans-Serif;  color:#333333;">
            <thead style="  background: #ededed; font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <th width="30px" style="text-align: center">No</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Bagian</th>
                    <th>TTD</th>
                </tr>
            </thead>
            <tbody>
                @php $i=1 @endphp
                @foreach ($view_d as $item)
                    <tr>
                        <td style="text-align: center">{{ $i++ }}</td>
                        <td>{{ $item->no_payroll }}</td>
                        <td>{{ $item->nama_asli }}</td>
                        <td>{{ $item->bagian }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="position: relative; width: 100%; height:12%; border: 1px solid rgb(0, 0, 0); margin-top:2%; ">
    </div>
    <div style="position: relative; width: 100%; height:12%; border: 1px solid rgb(0, 0, 0);  margin-top:2%; ">
    <div style="text-align: right; margin-right:5%;  " >
        <p>Bekasi, {{ $tgl }} <br> Penanggung Jawab, </p> <br>
        <strong> {{ $view->pemateri }}</strong>
    </div> 
        
    </div>


</body>

</html>
