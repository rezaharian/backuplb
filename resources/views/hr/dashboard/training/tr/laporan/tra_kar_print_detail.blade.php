
<title>{{ $data->nama_asli }}</title>
<style>
    hr {
        width: 1730%;
        height: 2px;
        border: none;
        background-color: black;
        margin: 2px auto;
    }
    body{
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 7pt;
        text-transform: uppercase;
    }
</style>
<body>

    <img  src="{{public_path ("/image/logos/logo-ext.png" ) }}" alt="" style="width:20%;">
    
    <div class="row" style="font-size: 9pt;  ">
        <div class="column"  style="float:left; width:40%;">
            <table style="margin-top: 35%;" >
                <tr>
                    <td>No Reg </td>
                    <td>:</td>
                    <td>{{ $data->no_payroll }}</td>
                </tr>
                <tr>
                    <td>Nama </td>
                    <td>:</td>
                    <td>{{ $data->nama_asli }} </td>
                </tr>
                <tr>
                    <td>Posisi </td>
                    <td>:</td>
                    <td>{{ $data->jabatan }}  </td>
                </tr>
            </table>
        </div>
        <div class="column" style=" float: left; width:40%;" >
            <p style="margin-top: 15%;"><strong>PERIODE  {{ $dtv1 }} s.d {{ $dtv2 }} </strong></p>

        </div>
        <div class="column" style=" float: left;width:20%; " >
            <img  src="{{ public_path("/image/fotos/".$data->foto ) }}" alt="" style="width:4cm;">
        </div>
    </div>
<table class="table1" style=" float:center; margin-top:22%;    font-size: 7pt;">
    <hr>
    <thead  >
        <th style="width: 30 ;">No</th>
        <th style="width: 40 ;">Tanggal</th>
        <th style="width:100">Training/Kompetensi</th>
        <th style="width:80">Trainer</th>
        <th style="width:30">Level</th>
        <th style="width:70">Pre test</th>
        <th style="width:70">Post test</th>
        <th style="width:50">Jam</th>
        <th style="width:50">Ket</th>
    </thead>
    <hr>
    @foreach ($data_t as $item)     
    <tbody >
        <td style="text-align:center; ">{{ $loop->iteration }}</td>
        <td>{{ $item->train_date }}</td>
        <td>{{ $item->train_tema }}</td>
        <td>{{ $item->pemateri }}</td>
        <td>{{ $item->level }}</td>
        <td  style="text-align:center; ">{{ $item->nilai_pre }}</td>
        <td  style="text-align:center; ">{{ $item->nilai }}</td>
        <td  style="text-align:center; ">{{ $item->totaljam }}</td>
        <td  style="text-align:center; ">{{ $item->keterangan }}</td>
    </tbody>
    @endforeach
<hr>
    <tbody style="text-align: center;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><b>Nilai Rata2</b></td>
        <td><b>Nilai Rata2</b></td>
        <td><b>Total Jam</b></td>
    </tbody>
    <hr>
    <tbody style="text-align: center; ">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><b>{{ $data_total->totalnilaipre }}</b></td>
        <td><b>{{ $data_total->totalnilai }}</b></td>
        <td><b>{{ $data_total->totaljam }}</b></td>
    </tbody>
    
</table>
</body>