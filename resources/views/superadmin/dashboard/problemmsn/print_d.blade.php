<!DOCTYPE html>
<html>

<head>
    <title>Mesin</title>

    <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        h1, h3 {
            text-align: center;
        }

        img {
            display: block;
            margin: 10px auto;
            max-width: 150px;
            max-height: 150px;
        }
    </style>
</head>

<body>
    <h1 style="font-size: 20px;">Permasalahan Mesin</h1>

    <table>
        <tr>
            <th style="width:20%; font-size: 10pt;">Kode</th>
            <td style="font-size: 10pt;"><strong>{{ $view->prob_cod }}</strong></td>
        </tr>
        <tr>
            <th style="font-size: 10pt;">Line</th>
            <td style="font-size: 10pt;"><strong>{{ $view->line }}</strong></td>
        </tr>
        <tr>
            <th style="font-size: 10pt;">Unit</th>
            <td style="font-size: 10pt;"><strong>{{ $view->unitmesin }}</strong></td>
        </tr>
        <tr>
            <th style="font-size: 10pt;">Tgl Input</th>
            <td style="font-size: 10pt;"><strong>{{ $view->tgl_input }}</strong></td>
        </tr>
        <tr>
            <th style="font-size: 10pt;">Masalah</th>
            <td style="font-size: 10pt;"><strong>{{ $view->masalah }}</strong></td>
        </tr>
    </table>

    <h3 style="font-size: 18px;">Detail Perbaikan</h3>

    <table>
        <tr>
            <th style="font-size: 10pt;">Penyebab</th>
            <th style="font-size: 10pt;">Tgl Perbaikan</th>
            <th style="font-size: 10pt;">Perbaikan</th>
            <th style="font-size: 10pt;">Tgl Pencegahan</th>
            <th style="font-size: 10pt;">Pencegahan</th>
        </tr>
        @forelse ($view_d as $item)
            <tr>
                <td style="font-size: 10pt;">{{ $item->penyebab }}</td>
                <td style="font-size: 10pt;">{{ $item->tgl_rpr }}</td>
                <td style="font-size: 10pt;">{{ $item->perbaikan }}</td>
                <td style="font-size: 10pt;">{{ $item->tgl_pre }}</td>
                <td style="font-size: 10pt;">{{ $item->pencegahan }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="font-size: 10pt;">No Data</td>
            </tr>
        @endforelse
    </table>

    <h3 style="font-size: 18px;">Gambar:</h3>

    <table>
        <tr>
            <td>
                <img src="{{ public_path("/image/".$view->img_pro01 ) }}" alt="">
                <img src="{{ public_path("/image/".$view->img_pro02 ) }}" alt="">
                <img src="{{ public_path("/image/".$view->img_pro03 ) }}" alt="">
                <img src="{{ public_path("/image/".$view->img_pro04 ) }}" alt="">
            </td>
        </tr>
    </table>
</body>

</html>
