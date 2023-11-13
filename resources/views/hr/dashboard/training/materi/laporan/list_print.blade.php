<style>
    hr {
        width: 4230%;
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
    <div style="text-align: center;">
          <h3>REKAP MATERI TRAINING KARYAWAN PT.EXTRUPACK</h3>
          <div style="text-align: left;">
    
              Materi  : {{ $mtr }}
            </div>
        </div>
  
      <div>
          <table >
              <thead>
                <hr>
                <tr>
                    <th style="width: 5 ; " scope="col">No</th>
                      <th style="width: 40 ;" scope="col">Tanggal</th>
                      <th style="width: 20 ;" scope="col">NIK</th>
                      <th  style="width: 130 ;"scope="col">Nama</th>
                      <th  style="width: 70 ; " scope="col">Bagian</th>
                      <th  style="width: 70 ;" scope="col">Jabatan</th>
                      <th  style="width: 60 ;" scope="col">Trainer</th>
                      <th  style="width: 40 ;" scope="col">Pre Test</th>
                      <th  style="width: 40 ;" scope="col">Post Test</th>
                  </tr>
                  <hr>
              </thead>
              <tbody>
  
                  @foreach ($data as $item)
                  
                      <tr>
                        <td style="text-align:center; ">{{ $loop->iteration }}</td>
                        <td>{{ $item->train_date }}</td>
                        <td>{{ $item->no_payroll }}</td>
                        <td>{{ $item->nama_asli }}</td>
                        <td>{{ $item->bagian }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->pemateri }}</td>
                        <td style="text-align: center;">{{ $item->nilai_pre }}</td>
                        <td style="text-align: center;">{{ $item->nilai }}</td>
                      </tr>
                  @endforeach
              </tbody>
              <hr>
          </table>
      </div>
  </body>
  