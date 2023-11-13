<style>
    hr {
        width: 2500%;
        height: 2px;
        border: none;
        background-color: black;
        margin: 2px auto;
    }
    body{
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 6pt;
        text-transform: uppercase;
  
    }
  </style>
<body>
  <img  src="{{public_path ("/image/logos/logo-ext.png" ) }}" alt="" style="width:20%;">
  <div style="text-align: center;">
        <h3>REKAP {{ $jenis }} KARYAWAN PT.EXTRUPACK</h3>
        Data dari {{ $dtv1 }} sampai dengan {{ $dtv2 }}
    </div>
    <div>

        @if ($jenis == 'Sosialisasi')

        <table >
            <thead>
              <hr>
              <tr>
                <th style="width: 20 ; " scope="col">No</th>
                <th style="width: 30 ;" scope="col">No Reg</th>
                <th  style="width: 120 ;"scope="col">Nama</th>
                <th  style="width: 80 ; " scope="col">Bagian</th>
                <th  style="width: 75 ;" scope="col">Jabatan</th>

                <th  style="width: 35 ;" scope="col">Total Jam</th>
                </tr>
                <hr>
            </thead>
            <tbody>

                @foreach ($data as $item)
                
                <tr>
                  <td style="text-align:center; ">{{ $loop->iteration }}</td>
                    <td>{{ $item->no_payroll }}</td>
                    <td>{{ $item->nama_asli }}</td>
                    <td>{{ $item->bagian }}</td>
                    <td>{{ $item->jabatan }}</td>

                    <td style="text-align:center;" >{{ $item->totaljam }}</td>

                </tr>
            @endforeach
            </tbody>
            <hr>
        </table>
        
        @else
        <table >
            <thead>
              <hr>
              <tr>
                <th style="width: 20 ; " scope="col">No</th>
                <th style="width: 30 ;" scope="col">No Reg</th>
                <th  style="width: 120 ;"scope="col">Nama</th>
                <th  style="width: 80 ; " scope="col">Bagian</th>
                <th  style="width: 75 ;" scope="col">Jabatan</th>
                <th  style="width: 35 ;" scope="col">PreT</th>
                <th  style="width: 35 ;" scope="col">PostT</th>
                <th  style="width: 35 ;" scope="col">Target</th>
                <th  style="width: 35 ;" scope="col">Total Jam</th>
                <th  style="width: 45 ;" scope="col">Kurang Jam</th>
                </tr>
                <hr>
            </thead>
            <tbody>

                @foreach ($data as $item)
                
                <tr>
                  <td style="text-align:center; ">{{ $loop->iteration }}</td>
                    <td>{{ $item->no_payroll }}</td>
                    <td>{{ $item->nama_asli }}</td>
                    <td>{{ $item->bagian }}</td>
                    <td>{{ $item->jabatan }}</td>
                    <td style="text-align:center;" >{{ $item->totalnilaipre }}</td>
                    <td style="text-align:center;" >{{ $item->totalnilai }}</td>
                    <td style="text-align:center;" >{{ $item->jumlah_jam }}</td>
                    <td style="text-align:center;" >{{ $item->totaljam }}</td>
                    <td style="text-align:center;" >{{ $item->kurangjam }}</td>
                </tr>
            @endforeach
            </tbody>

            <hr>
            <tr  style="font-size: 8; text-align:center; font-style:bold;">
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Total :</td>
              <td class="fw-bold" >{{ $jjm }}</td>
              <td class="fw-bold"> {{ $jj }}</td>
              <td class="fw-bold">  {{ $kj }}</td>
              <td></td>
          </tr>
        </table>

   
        @endif
    </div>
</body>
