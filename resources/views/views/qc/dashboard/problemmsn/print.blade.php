<!DOCTYPE html>
<html>

<head>
    <title>Mesin</title>

    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 10pt;
        }
    </style>
</head>

<body>


    <br>
    <strong style="font-family: Arial, Helvetica, sans-serif;">
        <center>Permasalahan Mesin</center>
    </strong>
    <br>



    <div >
    
            <table style="background: rgb(255, 255, 255); border:2;  font-family: Arial, Helvetica, sans-serif;">
                <tr>
                    <td style="width:20%; "> <strong>Kode </strong></td>
                    <td>:  {{ $view->prob_cod }} </td>
                </tr>
                <tr>
                    <td><strong>Line </td>

                    <td>:  {{ $view->line }} </td>
                </tr>
                <tr>
                    <td><strong>Unit </td>

                    <td>:  {{ $view->unitmesin }} </td>
                </tr>
                <tr>
                    <td><strong>Tgl Input </td>

                    <td>:  {{ $view->tgl_input }} </td>
                </tr>
                <tr>
                    <td><strong>Masalah </td>

                    <td>:  {{ $view->masalah }}  </td>
                </tr>
                <br>
            </table>
        </div>
        <div>
            <table >
                <tr>
                    <td><strong>Penyebab </td>

                    <td>:  {{ $view_d->penyebab }} </td>
                </tr>
                <tr>
                    <td><strong>Tgl Perbaikan </td>

                    <td>:  {{ $view_d->tgl_rpr }} </td>
                </tr>
                <tr>
                    <td><strong>Perbaikan </td>

                    <td>:  {{ $view_d->perbaikan }} </td>
                </tr>
                <tr>
                    <td><strong>Tgl Pencegahan </td>

                    <td>:  {{ $view_d->tgl_pre }} </td>
                </tr>
                <tr>
                    <td><strong> Pencegahan </td>

                    <td>:  {{ $view_d->pencegahan }} </td>
                </tr>
            </table>
            <h3>Gambar :</h3>
            <table>
                <br>
                <br>
                <br>

                <tr>
                    <td>
                        <img src="{{ public_path("/image/".$view->img_pro01 ) }}" alt="" style="width: 150px; ">
                        <img src="{{ public_path("/image/".$view->img_pro02 ) }}" alt="" style="width: 150px; ">
                        <img src="{{ public_path("/image/".$view->img_pro03 ) }}" alt="" style="width: 150px; ">
                        <img src="{{ public_path("/image/".$view->img_pro04 ) }}" alt="" style="width: 150px; ">

                    </td>
                </tr>
            </table>
        </div>

    </div>


</body>

</html>
