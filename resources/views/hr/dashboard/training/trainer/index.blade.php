@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')
    <div>

        <div class="container">
            <img src="{{ '/image/logos/cpo.jpg' }}" alt="" style="width:100%;" loading="eager">
            <div class="menu-container">
                <div class="menu-wrapper">
                    <a href="/trainer/list/" class="menu-link slide-from-left">Training</a>
                    <a href="/trainer/msn/list/" class="menu-link slide-from-left">Masalah Mesin</a>
                    <a href="/trainer/laporan/rek_tra/" class="menu-link slide-from-right">Laporan Training</a>
                    <a href="/trainer/ktiga/list/" class="menu-link slide-from-right">K Tiga</a>
                </div>
            </div>
        </div>
        

        {{-- <div class="row mt-5 p-2 border border-radius-10 mb-0">
            <div class="col-md-6 d-flex justify-content-center ">
                <img src="https://cdn-site.people.ai/2022/01/19055034/company-hero.png" alt=""
                    style="max-width: 70%;" loading="eager">
            </div>
            <div class="col-md-6 d-flex align-items-center justify-content-center mt-3 mt-md-0">
                <div class="card text-center animate__animated animate__fadeIn">
                    <div class="card-body">
                        <h2>Training</h2>
                        <h6 style="text-align: justify; text-align-last: left;">Training di perusahaan adalah proses di mana
                            karyawan diberikan pengetahuan, keterampilan, dan pemahaman baru yang relevan dengan pekerjaan
                            mereka. Tujuannya adalah meningkatkan kompetensi dan kinerja karyawan agar lebih efektif dalam
                            menjalankan tugas-tugas mereka.</h6>
                        <button class="btn btn-sm btn-secondary btn-hover-blue">check</button>
                    </div>
                </div>
            </div>
            
        </div> --}}


        











    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
.container {
    position: relative;
}

.menu-container {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
}

.menu-wrapper {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    align-content: center;
    text-align: center;
    /* background-color: white; */
}

.menu-link {
    text-decoration: none;
    color: black;
    margin: 0 10px;
}

img {
    position: relative;
    z-index: -1;
}

        /*  */
        .btn-hover-blue:hover {
            background-color: rgb(8, 134, 213);
            color: white;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        /*  */
        .border-radius-10 {
            border-radius: 10px;
            background-color: rgb(7, 172, 255);
        }

        /*  */
        .menu-link {
            opacity: 0;
            animation-duration: 0.5s;
            animation-fill-mode: forwards;
        }

        .slide-from-left {
            animation-name: slideFromLeft;
        }

        .slide-from-right {
            animation-name: slideFromRight;
        }

        @keyframes slideFromLeft {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideFromRight {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .menu-link:hover {
            background-color: rgb(8, 134, 213);
            color: white;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        .menu-container {
            display: flex;
            justify-content: center;
        }

        .menu-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .menu-link {
            background-color: #ccc;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            color: #01747f;
            font-weight: bold;

            border-radius: 20px;
            font-size: 14px;
            /* Ukuran teks awal */
        }

        /* Responsiveness */
        @media screen and (max-width: 768px) {
            .menu-link {
                font-size: 12px;
                /* Ukuran teks saat responsif */
            }
        }

        @media screen and (max-width: 480px) {
            .menu-link {
                font-size: 10px;
                /* Ukuran teks saat responsif lebih kecil */
            }
        }

        /*  */
        
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
