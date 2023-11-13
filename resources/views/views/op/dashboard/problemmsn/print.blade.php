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
            <table border="" style="background: rgb(255, 255, 255); border:0;   font-family: Arial, Helvetica, sans-serif;">
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
                    <td>Penyebab </td>

                    <td> <strong> {{ $view_d->penyebab }} </strong></td>
                </tr>
                <tr>
                    <td>Tgl Perbaikan </td>

                    <td> <strong> {{ $view_d->tgl_rpr }} </strong></td>
                </tr>
                <tr>
                    <td>Perbaikan </td>

                    <td> <strong> {{ $view_d->perbaikan }} </strong></td>
                </tr>
                <tr>
                    <td>Tgl Pencegahan </td>

                    <td> <strong> {{ $view_d->tgl_pre }} </strong></td>
                </tr>
                <tr>
                    <td> Pencegahan </td>

                    <td> <strong> {{ $view_d->pencegahan }} </strong></td>
                </tr>
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
