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
  <title>Print Rekap Target</title>
  <body>
    <img  src="{{public_path ("/image/logos/logo-ext.png" ) }}" alt="" style="width:20%;">
    <div style="text-align: center;">
          <h3>REKAP TARGET TRAINING KARYAWAN PT.EXTRUPACK</h3>
          <div style="text-align: left;">
    
            </div>
        </div>
      <div>
        <strong>Periode : {{ $t1 }} - {{ $t2 }}</strong>
          <table >
              <thead>
                <hr>
                <tr>
                    <th style="width: 10 ; " scope="col">No</th>
                      <th style="width: 100 ;" scope="col">Bagian</th>
                      <th style="width: 70 ;" scope="col">Target</th>
                      <th  style="width: 70 ;"scope="col">Total Jam</th>
                      <th  style="width: 70 ; " scope="col">Kurang Jam</th>
                      <th style="width: 100 ; " scope="col"> Grafik </th>
                      <th style="width: 70 ; " scope="col">  Presentase</th>

                  </tr>
                  <hr>
              </thead>
              <tbody>
  
                  @foreach ($data as $item)
                  
                      <tr>
                        <td style="text-align:center; ">{{ $loop->iteration }}</td>
                        <td>{{ $item->bagian }}</td>
                        <td style="text-align:center; ">{{ $item->target }}</td>
                        <td style="text-align:center; ">{{ $item->totaljam }}</td>
                        <td style="text-align:center; ">{{ $item->kurangjam }}</td>
                        <td style="display: flex; align-items: center;">
                          <div style="width: {{ round(($item->totaljam / $item->target) * 100, 2) }}%; background-color: #007bff; height: 10px;"></div>
                        </td>
                        <td>
                          <span style="margin-left: 5px; display: flex;"><b>{{ round(($item->totaljam / $item->target) * 100, 2) }}%</b> </span>
                        </td>
                        
                        
                      </tr>
                  @endforeach
              </tbody>
              <hr>
          </table>
      </div>
  </body>
  