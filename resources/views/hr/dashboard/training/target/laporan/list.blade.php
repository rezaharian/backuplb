@extends('hr.dashboard.layout.layout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-info text-center px-5">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div class="col-md-12">
            <div class="card card-plain rounded-0 border-primary">

                <form class="mx-2 mt-4" action="/hr/dashboard/training/target/laporan/list/">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <span class="fw-bold text-secondary">Periode Target :</span>
                                <select type="text" class="form-control form-control-sm" name="train_dat_awal_target"
                                    id="exampleFormControlInput1" placeholder="dari tanggal" required>
                                    @foreach ($periode_target as $item)
                                        <option value="{{ $item->periode_awal }}">{{ $item->periode_awal }} /
                                            {{ $item->periode_akhir }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <span class="fw-bold text-secondary">Periode Dari :</span>
                                <input type="date" class="form-control form-control-sm" name="train_dat_awal"
                                    id="exampleFormControlInput1" placeholder="dari tanggal" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <span class="fw-bold text-secondary"> Sampai :</span>
                                <input type="date" class="form-control form-control-sm" name="train_dat_akhir"
                                    id="exampleFormControlInput1" placeholder="sampai tanggal" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <span class="fw-bold text-secondary">Bagian :</span>
                                <select class="form-control form-control-sm " name="bagian" id="">
                                    <option value="">SEMUA</option>
                                    @foreach ($bagian as $item)
                                        <option value="{{ $item->bagian }}">{{ $item->bagian }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <br>
                                <button type="submit"
                                    class="btn btn-primary btn-rounded btn-sm form-control">submit</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <form action="/hr/dashboard/training/target/laporan/print" target="_blank">
                <div class="row">
                    <div class="col-md-10">

                    </div>
                    <div class="col-md-2  text-end">

                        <input type="date" class="form-control form-control-sm" value="{{ $train_dat_awal_target }}"
                            name="train_dat_awal_target" id="exampleFormControlInput1" placeholder="dari tanggal" hidden>

                        <input type="date" class="form-control form-control-sm" value="{{ $train_dat_awal }}"
                            name="train_dat_awal" id="exampleFormControlInput1" placeholder="dari tanggal" hidden>

                        <input type="date" class="form-control form-control-sm" value="{{ $train_dat_akhir }}"
                            name="train_dat_akhir" id="exampleFormControlInput1" placeholder="sampai tanggal" hidden>

                        <input type="text" class="form-control form-control-sm" value="{{ $req_bagian }}"
                            name="req_bagian" id="exampleFormControlInput1" placeholder="sampai tanggal" hidden>
                        <div class="form-group">

                            <button type="submit" class="btn card-plain btn-md btn-primary  mt-1 "><i
                                    class="fas fa-print"></i></button>
                        </div>
                    </div>
                </div>
            </form>
            <h6 class="card-plain">Rekap Training Dari {{ $t1 }} Sampai Dengan {{ $t2 }}</h6>

            <canvas id="Chart"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


            <div class="card card-plain rounded-0 border-primary">
                <div style="height:300px;overflow:auto;">
                    <table class="table" style="font-size: 10pt;">
                        <thead class="bg-primary text-light sticky-top ">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Bagian</th>
                                <th scope="col">Target</th>
                                <th scope="col">Total Jam</th>
                                <th scope="col">Kurang Jam</th>
                                <th scope="col"> Grafik Presentae</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 7;">
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->bagian }}</td>
                                    <td>{{ $item->target }}</td>
                                    <td>{{ $item->totaljam }}</td>
                                    <td>{{ $item->kurangjam }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 100px;">
                                                <div
                                                    style="width: {{ round(($item->totaljam / $item->target) * 100, 2) }}%; background-color: #007bff; height: 10px;">
                                                </div>
                                            </div>
                                            <span style="margin-left: 5px;"><b>
                                                    {{ round(($item->totaljam / $item->target) * 100, 2) }}% </b>
                                                Diselesaikan</span>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* CSS styles */
    </style>

    <script>
        const dataLabels = @json($data->pluck('bagian'));
        const dataJamPelatihanAwal = @json($data->pluck('target'));
        const dataJamPelatihanDilaksanakan = @json($data->pluck('totaljam'));
        const dataKekuranganJam = @json($data->pluck('kurangjam'));

        // Calculate the percentage of training completed for each section
        const dataPersentasePelatihan = dataJamPelatihanDilaksanakan.map((value, index) => {
            const target = dataJamPelatihanAwal[index];
            const persentase = (value / target) * 100;
            return `${persentase.toFixed(1)}%`;
        });




        const chartConfig = {
            type: 'bar',
            data: {
                labels: dataLabels.map((label, index) => `${label} - ${dataPersentasePelatihan[index]}`),
                datasets: [{
                        label: 'Target Jam Pelatihan',
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: dataJamPelatihanAwal,
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            formatter: function(value, context) {
                                return value + '%';
                            }
                        }
                    },
                    {
                        label: 'Jam Pelatihan Dilaksanakan',
                        backgroundColor: 'rgba(255, 500, 34, 0.7)',
                        borderColor: 'rgba(255, 510, 34, 1)',
                        borderWidth: 1,
                        data: dataJamPelatihanDilaksanakan,
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            formatter: function(value, context) {
                                return value + '%';
                            }
                        }
                    },
                    {
                        
                        label: 'Kekurangan Jam Pelatihan',
                        backgroundColor: 'rgba(255, 206, 86, 0.7)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                        data: dataKekuranganJam,
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            formatter: function(value, context) {
                                return value + '%';
                            }
                        }
                    }
                ],
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        },
                    },
                },
                plugins: {
                    datalabels: {
                        display: true,
                    }
                }
            },
        };

        const ctx = document.getElementById('Chart').getContext('2d');
        new Chart(ctx, chartConfig);
    </script>



@endsection
