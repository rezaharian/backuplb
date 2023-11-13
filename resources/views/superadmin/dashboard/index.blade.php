@extends('superadmin.dashboard.layout.layout')


@section('content')
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
            Dashboard
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="sub-card">
                        <div class="sub-card-header">
                            User
                        </div>
                        <div class="sub-card-body">
                            <h3 class="text-left">
                                <i class="fas fa-user fa-fw fa-lg sub-card-icon opacity-50"></i>
                                <span id="jml_user">0</span>
                            </h3>
                            <a href="/superadmin/dashboard/user">
                                <i class="fas fa-eye fa-lg"></i>
                            </a>
                        </div>
                    </div>
                
                    <div class="sub-card">
                        <div class="sub-card-header">
                            Data Kerusakan
                        </div>
                        <div class="sub-card-body">
                            <h3 class="text-left">
                                <i class="fas fa-file-contract fa-fw fa-lg sub-card-icon opacity-50"></i>
                                <span id="jml_kerusakan">0</span>
                            </h3>
                            <a href="/superadmin/dashboard/problemmsn">
                                <i class="fas fa-eye fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <span class="fw-bold"> User</span>
                    <canvas id="myChart" width="200"></canvas> 
                </div>
                <div class="col-md-5">
                    <span class="fw-bold"> Kerusakan Mesin Line</span>
                    <canvas id="chart_jns_peg" width="200"></canvas>
                </div>
            </div>
        </div>
        
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var data = {
            labels: [
                @foreach ($user as $p)
                    '{{ $p->level }}',
                @endforeach
            ],
            datasets: [{
                label: 'User',
                data: [
                    @foreach ($user as $p)
                        {{ $p->lev }},
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
                @foreach ($ker_line as $p)
                    '{{ $p->line }}',
                @endforeach
            ],
            datasets: [{
                label: ' Kerusakan',
                data: [
                    @foreach ($ker_line as $p)
                        {{ $p->ln }},
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
    </script>

<script>
    function animateCount(elementId, targetValue, duration) {
        const element = document.getElementById(elementId);
        const initialValue = 0;
        const increment = Math.ceil(targetValue / (duration * 1000 / 60));

        let currentValue = initialValue;

        const timer = setTimeout(function updateValue() {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
            }
            element.textContent = currentValue;

            if (currentValue < targetValue) {
                setTimeout(updateValue, 60); // Menunda setiap pembaruan nilai selama 60ms (sesuaikan jika diperlukan)
            }
        }, 60); // Menunda memulai animasi selama 60ms (sesuaikan jika diperlukan)
    }

    animateCount('jml_user', {{ $jml_user }}, 10); // Ganti angka 10 dengan durasi animasi yang diinginkan (dalam detik)
    animateCount('jml_kerusakan', {{ $jml_kerusakan }}, 10); // Ganti angka 10 dengan durasi animasi yang diinginkan (dalam detik)
</script>

 
@endsection
