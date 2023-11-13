<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\Presensi;
use App\Models\Timework;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AutoFingerController extends Controller
{
    public function exportData()
    {
        set_time_limit(220);

        $IPs = [
            // "101.255.144.202", //ip public nya
            '192.168.1.150', //local
            '192.168.1.154', //local
            '192.168.1.201', //local
        ];

        $Key = '0';

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $today = date('Y-m-d');

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

                    if ($date >= $yesterday && $date <= $today) {
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
                        } elseif ($IP === '192.168.1.201') {
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
                }

                // Process and insert data into the database
                foreach ($tempData as $data) {
                    $date = $data['Date'];
                    $timeIn = $data['TimeIn'];
                    $timeOut = $data['TimeOut'];
                    $pin = str_pad($data['PIN'], 4, '0', STR_PAD_LEFT);
                    $peg = Pegawai::where('no_payroll', $pin)->first();
        
                    if ($peg) {
                        $time = Timework::where('tw_cod', $peg->gkcod)->orWhereNull('tw_cod')->orWhereNull('tw_qty')->first();
                        if ($time ) {
                            if ($time->tw_qty == 0) {
                                $m = $time->tw_ins;
                                $k = $time->tw_out;
                                $msk = date('G', strtotime($data['TimeIn'])) * 60 + date('i', strtotime($data['TimeIn']));
                                $klr = date('G', strtotime($data['TimeOut'])) * 60 + date('i', strtotime($data['TimeOut']));
        
                             
                            }
                            if ($time->tw_qty != 0  ) {
                                $m = $time->tw_ins;
                                $k = $time->tw_out;
                                $msk = date('G', strtotime($data['TimeIn'])) * 60 + date('i', strtotime($data['TimeIn']));
                                $klr = date('G', strtotime($data['TimeOut'])) * 60 + date('i', strtotime($data['TimeOut']));
                                $time1 = Timework::where('tw_cod', $peg->gkcod)
                                    ->where(function ($query) use ($msk, $klr, &$stt) {
                                        $query
                                            ->where(function ($query) use ($msk, $klr, &$stt) {
                                                $query->where('vins01', '>=', $msk)->where('vout02', '<=', $klr);
                                            })
                                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                                $query->where('vins01', '>=', $msk)->where('vout01', '<=', $klr);
                                            })
                                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                                $query->where('vins02', '>=', $msk)->where('vout02', '<=', $klr);
                                            })
                                            ->orWhere(function ($query) use ($msk, $klr, &$stt) {
                                                $query->where('vins02', '>=', $msk)->where('vout01', '<=', $klr);
                                            });
                                    })
                                    ->first();
                           
        
                                $m = $time1->tw_ins;
                                $k = $time1->tw_out;
                            }
        
                            // Cek apakah data presensi sudah ada berdasarkan tanggal dan PIN
                            $existingPresensi = Presensi::where('tanggal', $date)
                                ->where('no_reg', $pin)
                                ->first();
        
                            if ($existingPresensi) {
                                // Jika data presensi sudah ada, cek apakah ada kolom kosong yang perlu diisi
                                if (empty($existingPresensi->masuk) || $existingPresensi->masuk > $timeIn) {
                                    $existingPresensi->fill([
                                        'masuk' => empty($timeIn) && !empty($existingPresensi->masuk) ? $existingPresensi->masuk : $timeIn,
                                        'gkcod' => $peg->gkcod ?? null,
                                    ]);
                                }
        
                                if (empty($existingPresensi->keluar) || $existingPresensi->keluar < $timeOut) {
                                    $existingPresensi->fill([
                                        'keluar' => empty($timeOut) && !empty($existingPresensi->keluar) ? $existingPresensi->keluar : $timeOut,
                                        'gkcod' => $peg->gkcod ?? null,
                                    ]);
                                }
        
                                if (empty($existingPresensi->norm_m)) {
                                    $existingPresensi->fill([
                                        'norm_m' => $m ?? null,
                                    ]);
                                }
        
                                if (empty($existingPresensi->norm_k)) {
                                    $existingPresensi->fill([
                                        'norm_k' => $k ?? null,
                                    ]);
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
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Berhasil export data');
    }

    private function parseData($data, $startTag, $endTag)
    {
        $start = strpos($data, $startTag);
        if ($start !== false) {
            $start += strlen($startTag);
            $end = strpos($data, $endTag, $start);
            if ($end !== false) {
                return substr($data, $start, $end - $start);
            }
        }
        return null;
    }
}
