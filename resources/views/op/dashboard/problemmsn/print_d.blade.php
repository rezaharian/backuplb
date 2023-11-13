<!DOCTYPE html>
<html>

<head>
    <title>Mesin</title>

    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 12pt;
        }
    </style>
</head>

<body>


    <br>
    <strong style="font-family: Arial, Helvetica, sans-serif;">
        <center>Permasalahan Mesin</center>
    </strong>
    <br>



    <div>
        <div>
            <table border=""
                style="background: rgb(255, 255, 255); border:0;   font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <td style="width:20%; ">Kode </td>
                    <td> <strong> {{ $view->prob_cod }} </strong></td>
                </tr>
                <tr>
                    <td>Line </td>

                    <td> <strong> {{ $view->line }} </strong></td>
                </tr>
                <tr>
                    <td>Unit </td>

                    <td> <strong> {{ $view->unitmesin }} </strong></td>
                </tr>
                <tr>
                    <td>Tgl Input </td>

                    <td> <strong> {{ $view->tgl_input }} </strong></td>
                </tr>
                <tr>
                    <td>Masalah </td>

                    <td> <strong> {{ $view->masalah }} </strong> </td>
                </tr>
                <br>
                
                <tr>
                    <td> <strong> Penyebab </strong></td>
                    <td> <strong> Tgl Perbaikan </strong></td>
                    <td> <strong> Perbaikan </strong></td>
                    <td> <strong> Tgl Pencegahan </strong></td>
                    <td> <strong>  Pencegahan </strong></td>

                </tr>
                @forelse ($view_d as $item)
                    
              
                <tr>
                    <td> {{ $item->penyebab }} </td>
                    <td> {{ $item->tgl_rpr }} </td>
                    <td> {{ $item->perbaikan }} </td>
                    <td> {{ $item->tgl_pre }} </td>
                    <td> {{ $item->pencegahan }} </td>

                </tr>
                @empty
                    <p>No Data</p>
                @endforelse
             
            </table>
            <h3>Gambar :</h3>
            <table>

                <tr>
                    <td>
                        {{-- <img src="{{ public_path("/image/".$view->img_pro01 ) }}" alt="" style="width: 150px; height: 150px;">
                        <img src="{{ public_path("/image/".$view->img_pro02 ) }}" alt="" style="width: 150px; height: 150px;">
                        <img src="{{ public_path("/image/".$view->img_pro03 ) }}" alt="" style="width: 150px; height: 150px;">
                        <img src="{{ public_path("/image/".$view->img_pro04 ) }}" alt="" style="width: 150px; height: 150px;"> --}}

                    </td>
                </tr>
            </table>
        </div>

    </div>


</body>

</html>
