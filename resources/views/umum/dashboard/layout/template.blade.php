<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
<title>Bootstrap Example</title>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<style>
    /* Tombol Dropdown */
    .dropbtn {
        background-color: #3498DB;
        color: rgb(31, 18, 18);
        padding: 9px;
        font-size: 20px;
        border: none;
        /* margin-left: 30px; */
        padding-left: 50px;
        padding-right: 93px;
        cursor: pointer;
    }

    /* Tombol Dropdown pada hover & focus */
    .dropbtn:hover,
    .dropbtn:focus {
        background-color: #2980B9;
    }

    /* Kontainer <div> - diperlukan untuk memposisikan konten dropdown */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Konten Dropdown (Hidden secara Default) */
    .dropdown-content {
        display: none;
        position: absolute;
        /* background-color: #f1f1f1; */
        min-width: 160px;
        /* box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2); */
        z-index: 1;
    }

    /* Link didalam dropdown */
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    /* Ubah warna link dropdown on hover */
    .dropdown-content a:hover {
        background-color: #007bff
    }

    /* Tampilkan menu dropdown (gunakan JS untuk menambahkan kelas ini ke .dropdown-content container ketika pengguna mengklik tombol dropdown) */
    .show {
        display: block;
    }

    a:hover {
        background-color: #68d7f6;
        color: #fcfeff;
    }
    .text-a {
        color: rgb(0, 0, 0);
        
    }
    
</style>
<aside class="sidenav navbar  navbar-vertical navbar-expand-xs border-0 fixed-start m-0  " id="sidenav-main"
    style="background-color: #387cac">
    <div class="sidenav-header text-center mb-2  ">
        <i class="fas fw-bold fa-times  p-1 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none "
            aria-hidden="true" id="iconSidenav"></i>
        <img src="{{ '/image/logos/logo-ext-nav.jpg' }}" alt="Logo" style="width: 100%;">
    </div>
    {{-- <hr class="horizontal  mt-0"> --}}
    <div class="collapse  navbar-collapse  w-auto  h-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav ">
            <li class="nav-item">
                <a class="nav-link  {{ Request::path() === 'umum' ? 'active' : '' }}" href="/../../umum">
                    <i class="fas text-light fw-bold fa-tachometer-alt"></i>

                    <span class="nav-link-text ms-1 text-a fw-bold">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ Request::segment(3) === 'absen' ? 'active' : '' }}"
                    href="/../umum/dashboard/absen/list">
                    <i class="fas text-light fw-bold fa-user-check"></i>
                    <span class="nav-link-text ms-1 text-a fw-bold">Absen</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ Request::segment(3) === 'overt' ? 'active' : '' }}"
                    href="/../umum/dashboard/overt/list">
                    <i class="fas text-light fw-bold fa-clock"></i>
                    <span class="nav-link-text ms-1 text-a fw-bold">Over Time</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ Request::segment(3) === 'srtcuti' ? 'active' : '' }}"
                    href="/../umum/dashboard/srtcuti/list">
                    <i class="far fa-calendar-times text-light fw-bold "></i>
                    <span class="nav-link-text ms-1 text-a fw-bold">Cuti</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#reportSubMenu"
                    onclick="toggleSubMenu(event)">
                    <i class="fas text-light fw-bold fa-file-alt"></i>
                    <span class="nav-link-text ms-1 text-a fw-bold" id="reportText">Report</span>
                </a>

                <div class="collapse" id="reportSubMenu">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link  {{ Request::segment(4) === 'reportabsen' ? 'active' : '' }}"
                                href="/../umum/dashboard/report/reportabsen/index">
                                <i class="fas text-light fw-bold fa-clipboard-list"></i>
                                <span class="nav-link-text ms-1 text-a fw-bold">Laporan Presensi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ Request::segment(4) === 'reportcuti' ? 'active' : '' }}"
                                href="/../umum/dashboard/report/reportcuti/index">
                                <i class="fas text-light fw-bold fa-bed"></i>
                                <span class="nav-link-text ms-1 text-a fw-bold">Laporan Cuti</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ Request::segment(4) === 'reporticb' ? 'active' : '' }}"
                                href="/../umum/dashboard/report/reporticb/index">
                                <i class="fas text-light fw-bold fa-bed"></i>
                                <span class="nav-link-text ms-1 text-a fw-bold">Laporan ICB</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ Request::segment(4) === 'reportipamdt' ? 'active' : '' }}"
                                href="/../umum/dashboard/report/reportipamdt/index">
                                <i class="fas text-light fw-bold fa-sign-out-alt"></i>
                                <span class="nav-link-text ms-1 text-a fw-bold">Laporan IPA MDT</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ Request::segment(4) === 'reporttlak' ? 'active' : '' }}"
                                href="/../umum/dashboard/report/reporttlak/index">
                                <i class="fas text-light fw-bold fa-calendar-check"></i>
                                <span class="nav-link-text ms-1 text-a fw-bold">Laporan Kehadiran</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ Request::segment(4) === 'reportlkk' ? 'active' : '' }}"
                                href="/../umum/dashboard/report/reportlkk/index">
                                <i class="fas text-light fw-bold fa-clock"></i>
                                <span class="nav-link-text ms-1 text-a fw-bold">Laporan Kehadiran Per Shift</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>


</aside>
<main class="main-content position-relative  h-auto mt-0 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-0 m-0   sticky-top bg-primary mt-0" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-0 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-0 px-0 me-sm-6 me-6">
                </ol>
                <h6 class="fw-bolder text-light mb-0">Hello,Admin </h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-1 d-flex align-items-center">
                </div>
                <ul class="navbar-nav  justify-content-end">
                    <li class="nav-item px-1 d-flex align-items-center justify-content-center">
                        <form class="" action="/logout" method="post">
                            @csrf
                            <button type="submit" class="btn text-light m-0 bg-transparant">
                                <i class="fa-solid fa-right-from-bracket"></i> </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-2">
        <div class="row ">
            @yield('content')
        </div>
    </div>
</main>
