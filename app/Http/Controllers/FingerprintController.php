<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\Presensi;
use App\Models\Timework;
use Illuminate\Http\Request;

class FingerprintController extends Controller
{
    public function index()
    {
        set_time_limit(500);

        $IPs = [
            // "101.255.144.202", //ip public nya
            '192.168.1.150', //local
            '192.168.1.154', //local
            '192.168.1.153', //local
        ];

        $Key = '0';

        $export = [];

        foreach ($IPs as $IP) {
            $Connect = fsockopen($IP, '80', $errno, $errstr, 1);
            if ($Connect) {
                $soap_request =
                    "<GetAttLog>
                    <ArgComKey xsi:type=\"xsd:integer\">" .
                    $Key .
                    "</ArgComKey>
                    <Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg>
                </GetAttLog>";

                $newLine = "\r\n";
                fputs($Connect, 'POST /iWsService HTTP/1.0' . $newLine);
                fputs($Connect, 'Content-Type: text/xml' . $newLine);
                fputs($Connect, 'Content-Length: ' . strlen($soap_request) . $newLine . $newLine);
                fputs($Connect, $soap_request . $newLine);
                $buffer = '';
                while ($Response = fgets($Connect, 1024)) {
                    $buffer = $buffer . $Response;
                }
                fclose($Connect);

                $buffer = $this->parseData($buffer, '<GetAttLogResponse>', '</GetAttLogResponse>');
                $buffer = explode("\r\n", $buffer);

                $tempData = [];

                for ($a = 0; $a < count($buffer); $a++) {
                    $data = $this->parseData($buffer[$a], '<Row>', '</Row>');

                    $dateTime = $this->parseData($data, '<DateTime>', '</DateTime>');
                    $timestamp = strtotime($dateTime);
                    $date = date('Y-m-d', $timestamp);
                    $time = date('H:i', $timestamp);
                    $status = $this->parseData($data, '<Status>', '</Status>');

                    $key = $this->parseData($data, '<PIN>', '</PIN>') . '_' . $date;

                    if (!isset($tempData[$key])) {
                        $tempData[$key] = [
                            'IP' => $IP,
                            'PIN' => $this->parseData($data, '<PIN>', '</PIN>'),
                            'Date' => $date,
                            'TimeIn' => null,
                            'TimeOut' => null,
                        ];
                    }

                    if ($IP === '192.168.1.154') {
                        if (!isset($tempData[$key]['TimeOut'])) {
                            $tempData[$key]['TimeOut'] = $time;
                        }
                    } elseif ($IP === '192.168.1.153') {
                        if (!isset($tempData[$key]['TimeIn'])) {
                            $tempData[$key]['TimeIn'] = $time;
                        }
                    } elseif ($IP === '192.168.1.150') {
                        if ($status === '0') {
                            if (!isset($tempData[$key]['TimeIn'])) {
                                $tempData[$key]['TimeIn'] = $time;
                            }
                        } elseif ($status === '1') {
                            $tempData[$key]['TimeOut'] = $time;
                        }
                    }
                }

                // Menggabungkan data dengan tanggal dan pin yang sama
                foreach ($tempData as $key => $data) {
                    $sameDataKey = null;
                    foreach ($export as $index => $exportData) {
                        if ($exportData['PIN'] === $data['PIN'] && $exportData['Date'] === $data['Date']) {
                            $sameDataKey = $index;
                            break;
                        }
                    }

                    if ($sameDataKey !== null) {
                        if ($data['IP'] === '192.168.1.154') {
                            if (!isset($export[$sameDataKey]['TimeOut'])) {
                                $export[$sameDataKey]['TimeOut'] = $data['TimeOut'];
                            }
                        } elseif ($data['IP'] === '192.168.1.153') {
                            if (!isset($export[$sameDataKey]['TimeIn'])) {
                                $export[$sameDataKey]['TimeIn'] = $data['TimeIn'];
                            }
                        } elseif ($data['IP'] === '192.168.1.150') {
                            if ($data['Status'] === '0') {
                                if (!isset($export[$sameDataKey]['TimeIn'])) {
                                    $export[$sameDataKey]['TimeIn'] = $data['TimeIn'];
                                }
                            } elseif ($data['Status'] === '1') {
                                $export[$sameDataKey]['TimeOut'] = $data['TimeOut'];
                            }
                        }
                    } else {
                        $export[] = $data;
                    }
                }
            }
        }

        return view('fingerprint.index', ['export' => $export]);
    }

    public function export(Request $request)
    {
        set_time_limit(500);

        $exportData = json_decode($request->input('export'), true);

        foreach ($exportData as $data) {
            $date = $data['Date'];
            $timeIn = $data['TimeIn'];
            $timeOut = $data['TimeOut'];
            $pin = str_pad($data['PIN'], 4, '0', STR_PAD_LEFT);
            $peg = Pegawai::where('no_payroll', $pin)->first();

            if ($peg) {
                $time = Timework::where('tw_cod', $peg->gkcod)
                    ->orWhereNull('tw_cod')
                    ->orWhereNull('tw_qty')
                    ->first();

                if ($time) {
                    if ($time->tw_qty == 0) {
                        $m = $time->tw_ins;
                        $k = $time->tw_out;
                        $msk = date('G', strtotime($data['TimeIn'])) * 60 + date('i', strtotime($data['TimeIn']));
                        $klr = date('G', strtotime($data['TimeOut'])) * 60 + date('i', strtotime($data['TimeOut']));
                    } else {
                        $m = $time->tw_ins;
                        $k = $time->tw_out;
                        $msk = date('G', strtotime($data['TimeIn'])) * 60 + date('i', strtotime($data['TimeIn']));
                        $klr = date('G', strtotime($data['TimeOut'])) * 60 + date('i', strtotime($data['TimeOut']));
                        $time1 = Timework::where('tw_cod', $peg->gkcod)
                            ->where(function ($query) use ($msk, $klr) {
                                $query
                                    ->where(function ($query) use ($msk, $klr) {
                                        $query->where('vins01', '>=', $msk)->where('vout02', '<=', $klr);
                                    })
                                    ->orWhere(function ($query) use ($msk, $klr) {
                                        $query->where('vins01', '>=', $msk)->where('vout01', '<=', $klr);
                                    })
                                    ->orWhere(function ($query) use ($msk, $klr) {
                                        $query->where('vins02', '>=', $msk)->where('vout02', '<=', $klr);
                                    })
                                    ->orWhere(function ($query) use ($msk, $klr) {
                                        $query->where('vins02', '>=', $msk)->where('vout01', '<=', $klr);
                                    });
                            })
                            ->first();

                        if ($time1) {
                            $m = $time1->tw_ins;
                            $k = $time1->tw_out;
                        }
                    }

                    // Cek apakah data presensi sudah ada berdasarkan tanggal dan PIN
                    $existingPresensi = Presensi::where('tanggal', $date)
                        ->where('no_reg', $pin)
                        ->first();

                    if ($existingPresensi) {
                        // Jika data presensi sudah ada, cek apakah ada kolom kosong yang perlu diisi
                        if (empty($existingPresensi->masuk) || $existingPresensi->masuk > $timeIn) {
                            $existingPresensi->masuk = empty($timeIn) && !empty($existingPresensi->masuk) ? $existingPresensi->masuk : $timeIn;
                            $existingPresensi->gkcod = $peg->gkcod ?? null;
                        }

                        if (empty($existingPresensi->keluar) || $existingPresensi->keluar < $timeOut) {
                            $existingPresensi->keluar = empty($timeOut) && !empty($existingPresensi->keluar) ? $existingPresensi->keluar : $timeOut;
                            $existingPresensi->gkcod = $peg->gkcod ?? null;
                        }

                        if (empty($existingPresensi->norm_m)) {
                            $existingPresensi->norm_m = $m ?? null;
                        }

                        if (empty($existingPresensi->norm_k)) {
                            $existingPresensi->norm_k = $k ?? null;
                        }

                        $existingPresensi->save();
                    } else {
                        // Jika data presensi belum ada, tambahkan data baru
                        $presensi = new Presensi([
                            'tanggal' => $date,
                            'no_reg' => $pin,
                            'masuk' => $timeIn,
                            'keluar' => $timeOut,
                            'norm_m' => $m ?? null,
                            'norm_k' => $k ?? null,
                            'gkcod' => $peg->gkcod ?? null,
                        ]);
                        $presensi->save();
                    }
                }
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Berhasil export data');
    }

    private function parseData($data, $p1, $p2)
    {
        $data = ' ' . $data;
        $hasil = '';
        $awal = strpos($data, $p1);
        if ($awal !== false) {
            $akhir = strpos(strstr($data, $p1), $p2);
            if ($akhir !== false) {
                $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
            }
        }
        return $hasil;
    }

    public function filter(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $aw = date('d/m/Y', strtotime($request->input('start_date')));
        $ak = date('d/m/Y', strtotime($request->input('end_date')));

        $tgl_terakhir_export = Presensi::latest('updated_at')->value('updated_at');
        $tgl_trakhir_export_formatted = date('d/m/Y', strtotime($tgl_terakhir_export));

        // Get the data from the index function
        $export = $this->index()['export'];

        // Perform data processing based on the given date range
        $filteredData = [];

        foreach ($export as $data) {
            $date = $data['Date'];

            if ($date >= $start_date && $date <= $end_date) {
                $filteredData[] = $data;
            }
        }

        // Return the view with the filtered data
        return view('fingerprint.index', compact('aw', 'ak', 'tgl_terakhir_export'), ['export' => $filteredData]);
    }
}
