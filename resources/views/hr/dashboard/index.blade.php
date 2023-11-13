@extends('hr.dashboard.layout.layout')

@section('content')
    {{-- <div>


        <div style="position: relative;">
            <img src="{{ '/image/logos/cpo.jpg' }}" alt=""
                style="width:100%; z-index: 0; animation: slide-in 1s ease-out;">

        </div>
        <div class="slider-container">
            <div class="slider">

                <div class="slider-item" onclick="handleClick(1)">
                    <div class="card-body p-0 m-2 position-relative z-index-1">
                        <a href="/hr/dashboard/pegawai/index" class="d-block">
                            <div class="text-overlay">Pegawai</div>
                            <img src="https://cdn-icons-png.flaticon.com/512/3985/3985171.png"
                                class="img-fluid border-radius-lg">
                        </a>
                    </div>
                </div>

                <div class="slider-item" onclick="handleClick(2)">

                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/training/tr/list" class="d-block">
                                <div class="text-overlay">Training</div>
                                <img src="https://cdn-icons-png.flaticon.com/512/3463/3463112.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(3)">

                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/training/materi/list" class="d-block">
                                <div class="text-overlay">Materi</div>
                                <img src="https://cdn-icons-png.flaticon.com/512/6040/6040924.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(4)">

                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/training/target/list" class="d-block">
                                <div class="text-overlay">Target</div>
                                <img src="https://cdn-icons-png.flaticon.com/512/1969/1969557.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(5)">


                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/tdkabsen/list" class="d-block">
                                <div class="text-overlay">T.Absen</div>
                                <img src="https://cdn-icons-png.flaticon.com/128/10371/10371334.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(6)">

                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/tgllibur/list" class="d-block">
                                <div class="text-overlay">Tgl Libur</div>
                                <img src="https://cdn-icons-png.flaticon.com/256/7406/7406989.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(7)">

                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/training/bagian/index" class="d-block">
                                <div class="text-overlay">Bagian</div>
                                <img src="https://cdn-icons-png.flaticon.com/512/554/554795.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(8)">

                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/absen/list" class="d-block">
                                <div class="text-overlay">Absen</div>
                                <img src="https://cdn-icons-png.flaticon.com/128/10371/10371334.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(9)">


                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="/../hr/dashboard/overt/list" class="d-block">
                                <div class="text-overlay">Over Time</div>
                                <img src="https://cdn-icons-png.flaticon.com/256/4827/4827194.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="slider-item" onclick="handleClick(10)">

                    <div class="slider-item" onclick="handleClick(1)">
                        <div class="card-body p-0 m-2 position-relative z-index-1">
                            <a href="google.com" class="d-block">
                                <div class="text-overlay">Laporan</div>
                                <img src="https://cdn-icons-png.flaticon.com/512/4419/4419274.png"
                                    class="img-fluid border-radius-lg">
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>




        <div class="row mt-2">
            <div class="col-md-6 text-center">
                <span class="fw-bold"> Gender Karyawan</span>
                <canvas id="myChart" width="200"></canvas>
            </div>
            <div class="col-md-6 text-center">
                <span class="fw-bold"> Jenis Karyawan</span>
                <canvas id="chart_jns_peg" width="200"></canvas>
            </div>
        </div>




        <style>
            .text-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 20px;
                font-weight: bold;
                background-color: rgba(91, 91, 93, 0.5);
                border-radius: 10px;

            }

            /*  */
            @keyframes slide-in {
                0% {
                    transform: translateX(100%);
                    opacity: 0;
                }

                100% {
                    transform: translateX(0%);
                    opacity: 1;
                }
            }

            /*  */
            .slider-container {
                overflow-x: scroll;
                white-space: nowrap;
            }



            .slider {
                display: flex;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;

            }

            .slider::-webkit-scrollbar {
                display: none;
            }

            .slider-item {
                flex-shrink: 0;
                width: 150px;
                height: 150px;
                margin-right: 0;
                margin-top: 15px;
                padding: 0;
                /* background-color: #ffffff; */
                border-radius: 10px;
                cursor: pointer;
                transition: transform 0.2s ease-in-out;
            }

            .slider-item:hover {
                transform: scale(1.05);
            }

            .slider-item:last-child {
                margin-right: 0;
            }


            .slider {
                display: inline-block;

            }


            .slider-item {
                display: inline-block;
                width: 200px;
                height: 200px;
                r background-color: #ccc;
                margin-right: 10px;
                text-align: center;
                line-height: 200px;
                cursor: pointer;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            var data = {
                labels: [
                    @foreach ($karyawan as $p)
                        '{{ $p->sex }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Karyawan',
                    data: [
                        @foreach ($karyawan as $p)
                            {{ $p->jk }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    borderWidth: 1
                }]
            };

            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {

                    animations: {
                        tension: {
                            duration: 5000,
                            easing: 'linear',
                            from: 1,
                            to: 0,
                            loop: true
                        }
                    },
                    scales: {

                        yAxes: [{
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                    }
                }
            });


            var data_jns_peg = {
                labels: [
                    @foreach ($jns_peg as $p)
                        '{{ $p->jns_peg }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Karyawan',
                    data: [
                        @foreach ($jns_peg as $p)
                            {{ $p->jp }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    borderWidth: 1
                }]
            };

            var ctx = document.getElementById('chart_jns_peg').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: data_jns_peg,
                options: {
                    animations: {
                        tension: {
                            duration: 5000,
                            easing: 'linear',
                            from: 1,
                            to: 0,
                            loop: true
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                    }
                }
            });

            // 

            function handleClick(index) {
                const slider = document.querySelector('.slider');
                const sliderItem = document.querySelectorAll('.slider-item');
                const itemWidth = sliderItem[0].offsetWidth;
                slider.scroll({
                    left: (index - 1) * (itemWidth + 10),
                    behavior: 'smooth'
                });
            }

            // 


            const slider = document.querySelector('.slider');
            let isDown = false;
            let startX;
            let scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('active');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });

            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('active');
            });

            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('active');
            });

            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 3; // adjust scrolling speed
                slider.scrollLeft = scrollLeft - walk;
            });

            function handleClick(index) {
                console.log(`Clicked on content ${index}`);
            }
        </script>
    </div> --}}


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">



    <style>
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0, 49, 246, 0.1);
            margin-bottom: 4px;
            border: 1px solid #0091ff;
            border-radius: 0;
            padding: 5px;
        }


        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #338ce449;
            font-weight: bold;
            padding: 1px;
            font-size: 18px;
            font-family: sans-serif;
            text-transform: uppercase;
            height: 2pc;
            color: #45494d;
        }



        .card-body {
            padding: 5px;


        }

        .sub-card {
            padding: 2px;
            margin-bottom: 5px;
            background-color: #0372fa;
            box-shadow: 0 0 10px rgba(7, 7, 7, 0.208);
            color: rgb(255, 255, 255);
        }

        .sub-card :hover {
            background-color: #0091ff;
        }

        .sub-card-header {
            background-color: #ffffff1c;
            padding: 5px;
            font-weight: bold;
            border-radius: 0px;
            color: rgb(255, 254, 254);
        }

        .sub-card-body {
            padding: 10px;
            text-align: right;

        }

        .btn-sm {
            background: #fafdff;
            color: #0080ff;
            border-radius: 10%;

        }
    </style>

    <div class="card card-sm">
        <div class="card-header card-header-sm">
            Data Kepegawaian {{ $tahunSekarang }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="sub-card">
                        <div class="sub-card-header">
                            Pegawai
                        </div>
                        <div class="sub-card-body">
                            <h3 class="text-left">
                                <i class="fas fa-users fa-fw fa-lg sub-card-icon opacity-50"></i>
                                <span id="jml_peg">0</span>
                            </h3>
                            <a href="/hr/dashboard/pegawai/index">
                                <button class="btn btn-sm m-0">View</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sub-card">
                        <div class="sub-card-header">
                            Habis Kontrak {{ $r_bln }}
                        </div>
                        <div class="sub-card-body">
                            <h3 class="text-left">
                                <i class="fas fa-file-contract fa-fw fa-lg sub-card-icon opacity-50"></i>
                                <span id="jml_kntr">0</span>
                            </h3>
                            <a href="/hr/dashboard/pegawai/kontrak/laporan/list">
                                <button class="btn btn-sm m-0">View</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sub-card">
                        <div class="sub-card-header">
                            Hari Libur {{ $r_thn }}
                        </div>
                        <div class="sub-card-body">
                            <h3 class="text-left">
                                <i class="fas fa-calendar-alt fa-fw fa-lg sub-card-icon opacity-50"></i>
                                <span id="jml_libur">0</span>
                            </h3>
                            <a href="/hr/dashboard/tgllibur/list">
                                <button class="btn btn-sm m-0">View</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sub-card">
                        <div class="sub-card-header">
                            Absensi {{ $tahunSekarang }}
                        </div>
                        <div class="sub-card-body">
                            <h3 class="text-left">
                                <i class="fas fa-clipboard-list fa-fw fa-lg sub-card-icon opacity-50"></i>
                                <span id="jml_absen">0</span>
                            </h3>
                            <a href="/hr/dashboard/absen/list">
                                <button class="btn btn-sm m-0">View</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="card card-sm">
            <div class="card-header card-header-sm">
                Data Training
            </div>
        
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="sub-card">
                            <div class="sub-card-header">
                                Training
                            </div>
                            <div class="sub-card-body">
                                <h3 class="text-left">
                                    <i class="fas fa-chalkboard-teacher fa-fw fa-lg sub-card-icon opacity-50"></i>
                                    <span id="jml_tra">0</span>
                                </h3>
                                <a href="/hr/dashboard/training/tr/list">
                                    <button class="btn btn-sm m-0">View</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sub-card">
                            <div class="sub-card-header">
                                Materi
                            </div>
                            <div class="sub-card-body">
                                <h3 class="text-left">
                                    <i class="fas fa-book-open fa-fw fa-lg sub-card-icon opacity-50"></i>
                                    <span id="jml_mtr">0</span>
                                </h3>
                                <a href="/hr/dashboard/training/materi/list">
                                    <button class="btn btn-sm m-0">View</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sub-card">
                            <div class="sub-card-header">
                                Bagian
                            </div>
                            <div class="sub-card-body">
                                <h3 class="text-left">
                                    <i class="fas fa-users fa-fw fa-lg sub-card-icon opacity-50"></i>
                                    <span id="jml_bag">0</span>
                                </h3>
                                <a href="/hr/dashboard/training/bagian/index">
                                    <button class="btn btn-sm m-0">View</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sub-card">
                            <div class="sub-card-header">
                                Target
                            </div>
                            <div class="sub-card-body">
                                <h3 class="text-left">
                                    <i class="fas fa-bullseye fa-fw fa-lg sub-card-icon opacity-50"></i>
                                    <span id="jml_tar">0</span>
                                </h3>
                                <a href="/hr/dashboard/training/target/list">
                                    <button class="btn btn-sm m-0">View</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <div class="card">
        <div class="card-header">
            Data Jenis Karyawan
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-md-6 text-center">
                    <span class="fw-bold"> Gender Karyawan</span>
                    <canvas id="myChart" width="200"></canvas>
                </div>
                <div class="col-md-6 text-center">
                    <span class="fw-bold"> Jenis Karyawan</span>
                    <canvas id="chart_jns_peg" width="200"></canvas>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-6  text-center">
                    <span class="fw-bold"> Bagian</span>
                    <canvas id="chart_peg_bag" width="200"></canvas>
                </div>
            </div>
            </div>
        </div>
    </div>

    
    
    

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var data = {
            labels: [
                @foreach ($karyawan as $p)
                    '{{ $p->sex }}',
                @endforeach
            ],
            datasets: [{
                label: 'Karyawan',
                data: [
                    @foreach ($karyawan as $p)
                        {{ $p->jk }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)'
                ],

                borderWidth: 1
            }]
        };

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {

                animations: {
                    tension: {
                        duration: 5000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },
                scales: {

                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                }
            }
        });


        var data_jns_peg = {
            labels: [
                @foreach ($jns_peg as $p)
                    '{{ $p->jns_peg }}',
                @endforeach
            ],
            datasets: [{
                label: 'Karyawan',
                data: [
                    @foreach ($jns_peg as $p)
                        {{ $p->jp }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)'
                ],
                borderWidth: 1
            }]
        };

        var ctx = document.getElementById('chart_jns_peg').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: data_jns_peg,
            options: {
                animations: {
                    tension: {
                        duration: 5000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                }
            }
        });


        var data_jns_peg = {
            labels: [
                @foreach ($peg_bag as $p)
                    '{{ $p->bagian }}',
                @endforeach
            ],
            datasets: [{
                label: 'Karyawan',
                data: [
                    @foreach ($peg_bag as $p)
                        {{ $p->pb }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)',
                    'rgb(54, 162, 235)'
                ],
                borderWidth: 1
            }]
        };

        var ctx = document.getElementById('chart_peg_bag').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: data_jns_peg,
            options: {
                animations: {
                    tension: {
                        duration: 5000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                }
            }
        });
    </script>

<script>
    const jmlPegElem = document.getElementById('jml_peg');
const jmlKntrElem = document.getElementById('jml_kntr');
const jmlLiburElem = document.getElementById('jml_libur');
const jmlAbsenElem = document.getElementById('jml_absen');
const jmlTraElem = document.getElementById('jml_tra');
const jmlMtrElem = document.getElementById('jml_mtr');
const jmlBagElem = document.getElementById('jml_bag');
const jmlTarElem = document.getElementById('jml_tar');

const actualCountPeg = {{ $jml_peg }};
const actualCountKntr = {{ $jml_kntr }};
const actualCountLibur = {{ $jml_libur }};
const actualCountAbsen = {{ $jml_absen }};
const actualCountTra = {{ $jml_tra }};
const actualCountMtr = {{ $jml_mtr }};
const actualCountBag = {{ $jml_bag }};
const actualCountTar = {{ $jml_tar }};

let countPeg = 0;
let countKntr = 0;
let countLibur = 0;
let countAbsen = 0;
let countTra = 0;
let countMtr = 0;
let countBag = 0;
let countTar = 0;

const updateCounts = () => {
    if (countPeg < actualCountPeg) {
        countPeg++;
        jmlPegElem.textContent = countPeg;
    }

    if (countKntr < actualCountKntr) {
        countKntr++;
        jmlKntrElem.textContent = countKntr;
    }

    if (countLibur < actualCountLibur) {
        countLibur++;
        jmlLiburElem.textContent = countLibur;
    }

    if (countAbsen < actualCountAbsen) {
        countAbsen++;
        jmlAbsenElem.textContent = countAbsen;
    }

    if (countTra < actualCountTra) {
        countTra++;
        jmlTraElem.textContent = countTra;
    }

    if (countMtr < actualCountMtr) {
        countMtr++;
        jmlMtrElem.textContent = countMtr;
    }

    if (countBag < actualCountBag) {
        countBag++;
        jmlBagElem.textContent = countBag;
    }

    if (countTar < actualCountTar) {
        countTar++;
        jmlTarElem.textContent = countTar;
    }

    if (
        countPeg < actualCountPeg ||
        countKntr < actualCountKntr ||
        countLibur < actualCountLibur ||
        countAbsen < actualCountAbsen ||
        countTra < actualCountTra ||
        countMtr < actualCountMtr ||
        countBag < actualCountBag ||
        countTar < actualCountTar
    ) {
        setTimeout(updateCounts, 10);
    }
};

updateCounts();

</script>

@endsection
