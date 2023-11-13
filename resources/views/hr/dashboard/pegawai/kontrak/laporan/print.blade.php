<style>
    hr {
        width: 2330%;
        height: 2px;
        border: none;
        background-color: black;
        margin: 2px auto;
    }
    body{
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 8pt;
        text-transform: uppercase;
  
    }
    
  </style>
  <title>Print Habis Kontrak  {{ $bln }} - {{ $thn }} </title>
  <body>
    <img  src="{{public_path ("/image/logos/logo-ext.jpg" ) }}" alt="" style="width:20%;">
    <div style="text-align: center;">
          <h3>DAFTAR KARYAWAN HABIS KONTRAK PT.EXTRUPACK</h3>
          <div style="text-align: left;">
    
            </div>
        </div>
      <div>
        <strong>Periode Berakhir : {{ $bln }} - {{ $thn }}</strong>
          <table >
              <thead>
                <hr>
                <tr>
                    <th style="width: 20 ; " scope="col">No</th>
                      <th style="width: 200 ;" scope="col">Nama Karyawan</th>
                      <th style="width: 150 ;" scope="col">Perpanjang</th>
                      <th  style="width: 150 ;"scope="col">Berakhir</th>
                  </tr>
                  <hr>
              </thead>
              <tbody>
  
                  @foreach ($data as $item)
                      <tr>
                        <td style="text-align:center; ">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_asli }}</td>
                        <td >{{ $item->perpanjang }}</td>
                        <td  >{{ $item->Berakhir }}</td>
                      </tr>
                  @endforeach
              </tbody>
              <hr>
          </table>
      </div>
  </body>
  