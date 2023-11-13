<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        th,
        td {
            border: 1px solid black;
            padding: 2px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('/image/logos/logo-ext.png') }}" alt="" style="width:20%;">

    <div style="margin:1%; text-align:center;">
        <h4 style="margin: 0;">REPORT PENILAIAN KINERJA PT.EXTRUPACK</h4>
        @php
            [$tanggalAwal, $tanggalAkhir] = explode(' - ', $periode);

            // Mengubah format tanggal awal
            $tanggalAwal = date('d-m-Y', strtotime($tanggalAwal));

            // Mengubah format tanggal akhir
            $tanggalAkhir = date('d-m-Y', strtotime($tanggalAkhir));
        @endphp
        <p style="margin: 0;">Periode: {{ $tanggalAwal }} - {{ $tanggalAkhir }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th scope="col" width=3>No</th>
                <th scope="col" width=5>No Payroll</th>
                <th scope="col" width=10>Nama</th>
                <th scope="col" width=10>Bagian</th>
                <th scope="col" width=10>Score</th>
                <th scope="col" width=10>Grade</th>
                <th scope="col" width=50>Criteria</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pkinerja as $item)
                <tr>
                    <th>{{ $loop->iteration }}</th>
                    <td>{{ $item->no_payroll }}</td>
                    <td>
                        @if ($item->nama)
                            {{ $item->nama }}
                        @else
                            {{ $item->nama_asli }}
                        @endif
                    </td>

                    <td>{{ $item->bagian }}</td>
                    <td>{{ $item->total_score }}</td>
                    <td> @php
                        $criteria = '';
                        $grade = '';

                        $total_score = $item->total_score;

                        if ($total_score > 97 && $total_score <= 100) {
                            $criteria = 'Outstanding';
                            $grade = 'A';
                        } elseif ($total_score > 89 && $total_score <= 97) {
                            $criteria = 'Good Performance';
                            $grade = 'B';
                        } elseif ($total_score > 79 && $total_score <= 89) {
                            $criteria = 'Standard Performance';
                            $grade = 'C';
                        } elseif ($total_score > 61 && $total_score <= 79) {
                            $criteria = 'Need Improvement';
                            $grade = 'D';
                        } elseif ($total_score > 100) {
                            $criteria = 'Ada yg salah , tidak boleh lebih dari 100 !!';
                            $grade = 'Z';
                        } else {
                            $criteria = 'Unacceptable';
                            $grade = 'E';
                        }
                    @endphp
                        {{ $grade }}
                    </td>
                    <td>{{ $criteria }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
