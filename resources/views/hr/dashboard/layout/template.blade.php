<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://getbootstrap.com/docs/5.3/assets/css/docs.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

{{-- <link rel="stylesheet" type="text/css" href="style.css"> --}}
<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius my-0 fixed-start ms-0 me-0 text-light pb-5"
    id="sidenav-main" style="background-color: #387cac;">
    <div class="sidenav-header text-center mb-2">
        <i class="fas fa-times p-1 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <img src="{{ '/image/logos/logo-ext-nav.jpg' }}" alt="Logo" style="width: 100%;">
    </div>


    <div class="sidebar-collapse mt-3 ">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Request::path() === 'hr' ? 'active' : '' }}" href="/../../hr">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-link-text ms-2 text-light fw-bold text-light fw-bold">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-button" id="collapse-kepegawaian-toggle" role="button" href="#"
                    onclick="toggleKepegawaian()">
                    <i class="fas fa-users"></i>
                    <span class="nav-link-text ms-2 text-light fw-bold">Kepegawaian</span>
                </a>
                <div class="collapse" id="collapse-kepegawaian">
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/pegawai/index') !== false ? 'active' : ''; ?>" href="/hr/dashboard/pegawai/index">
                                <i class="fas fa-user"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Pegawai</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/pegawai/kontrak/laporan/list') !== false ? 'active' : ''; ?>"
                                href="/../hr/dashboard/pegawai/kontrak/laporan/list">
                                <i class="fas fa-file-contract"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Kontrak Pegawai</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'absen' ? 'active' : ' ' }}"
                                href="/../hr/dashboard/absen/list">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Absensi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'tgllibur' ? 'active' : '' }}"
                                href="/../hr/dashboard/tgllibur/list">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Tgl Libur</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'overt' ? 'active' : '' }}"
                                href="/../hr/dashboard/overt/list">
                                <i class="fas fa-clock"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Over Time</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'tdkabsen' ? 'active' : '' }}"
                                href="/../hr/dashboard/tdkabsen/list">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Tdk ABsen</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(2) === 'fingerprint' ? 'active' : '' }}" href="/../hr/fingerprint">
                                <i class="fas fa-fingerprint"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Finger</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'onoff' ? 'active' : '' }}"  href="/hr/dashboard/onoff/index">
                                <i class="fas fa-power-off"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">On Off</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'srtcuti' ? 'active' : '' }}"  href="/hr/dashboard/srtcuti/list">
                                <i class="fas fa-suitcase"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Cuti</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'penilaiankerja' ? 'active' : '' }}"  href="/hr/dashboard/penilaiankerja/index">
                                <i class="fas fa-check-circle"></i> 
                                 <span class="nav-link-text ms-2 text-light fw-bold">Penilaian Kinerja</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'reportkerajinan' ? 'active' : '' }}" href="/hr/dashboard/reportkerajinan/index">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan Kerajinan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'reportabsen' ? 'active' : '' }}" href="/hr/dashboard/reportabsen/index">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan Presensi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'reportcuti' ? 'active' : '' }}" href="/hr/dashboard/reportcuti/index">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan Cuti</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'penilaiankerjareport' ? 'active' : '' }}"  href="/hr/dashboard/penilaiankerjareport/laporan">
                                <i class="fas fa-chart-bar"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan P.Kinerja </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'reporticb' ? 'active' : '' }}"  href="/hr/dashboard/reporticb/index">
                                <i class="fas fa-chart-bar"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan ICB </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'reportipamdt' ? 'active' : '' }}"  href="/hr/dashboard/reportipamdt/index">
                                <i class="fas fa-chart-bar"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan IPA MDT </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'reporttlak' ? 'active' : '' }}"  href="/hr/dashboard/reporttlak/index">
                                <i class="fas fa-chart-bar"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan Kehadiran</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(3) === 'reportlkk' ? 'active' : '' }}"  href="/hr/dashboard/reportlkk/index">
                                <i class="fas fa-chart-bar"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan Kehadiran Per Shift </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a id="collapse-training-toggle" class="nav-link" href="#" onclick="toggleTraining()">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span class="nav-link-text ms-2 text-light fw-bold">Training Menu</span>
                </a>
                <div class="collapse" id="collapse-training">
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/tr') !== false ? 'active' : ''; ?>" id="tr-link"
                                href="/../hr/dashboard/training/tr/list">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Training</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/materi/') !== false ? 'active' : ''; ?>" id="materi-link"
                                href="/../hr/dashboard/training/materi/list">
                                <i class="fas fa-book-open"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Materi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/target/') !== false ? 'active' : ''; ?>" id="target-link"
                                href="/../hr/dashboard/training/target/list">
                                <i class="fas fa-bullseye"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Target</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/bagian/') !== false ? 'active' : ''; ?>" href="/../hr/dashboard/training/bagian/index"
                                id="bagian-link">
                                <i class="fas fa-building"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Bagian</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="collapse-laporan-toggle" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/rek_tra') !== false || strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/tra_kar') !== false || strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/materi/laporan/') !== false || strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/target/laporan/') !== false ? 'active' : ''; ?>" href="#"
                                onclick="toggleLaporan()">
                                <i class="fas fa-chart-bar"></i>
                                <span class="nav-link-text ms-2 text-light fw-bold">Laporan</span>
                            </a>
                            <div class="collapse <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/rek_tra') !== false || strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/tra_kar') !== false || strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/materi/laporan/') !== false || strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/target/laporan/') !== false ? 'show' : ''; ?>" id="collapse-laporan">
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/rek_tra') !== false ? 'active' : ''; ?>" id="rek-tra-link"
                                            href="/../hr/dashboard/training/rek_tra">
                                            <i class="fas fa-clipboard"></i>
                                            <span class="nav-link-text ms-2 text-light fw-bold">Rekap Training</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/tra_kar') !== false ? 'active' : ''; ?>" id="tra-kar-link"
                                            href="/../hr/dashboard/training/tra_kar">
                                            <i class="fas fa-users"></i>
                                            <span class="nav-link-text ms-2 text-light fw-bold">Training Karyawan</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/materi/laporan/') !== false ? 'active' : ''; ?>" id="materi-laporan-link"
                                            href="/../hr/dashboard/training/materi/laporan/list">
                                            <i class="fas fa-file-alt"></i>
                                            <span class="nav-link-text ms-2 text-light fw-bold">Rekap Materi</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/hr/dashboard/training/target/laporan/') !== false ? 'active' : ''; ?>" id="target-laporan-link"
                                            href="/../hr/dashboard/training/target/laporan/list">
                                            <i class="fas fa-chart-bar"></i>
                                            <span class="nav-link-text ms-2 text-light fw-bold">Rekap Target</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>



            <a class="nav-link menu-button" data-bs-toggle="collapse" id="menuButton" role="button" href="#"
                aria-expanded="false" aria-controls="collapse-absensi">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="nav-link-text ms-2 text-light fw-bold">K Tiga Menu</span>
            </a>

            <!-- Menus -->
            <ul id="menu1" style="display: none;">
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(3) === 'ktiga' && !Request::is('hr/dashboard/ktiga/reportktiga*') ? 'active' : '' }}"
                        href="/../hr/dashboard/ktiga/list">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="nav-link-text ms-2 text-light fw-bold">K Tiga</span>
                    </a>
                </li>
            </ul>

            <ul id="menu2" style="display: none;">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('hr/dashboard/ktiga/reportktiga*') ? 'active' : '' }}"
                        href="/../hr/dashboard/ktiga/reportktiga">
                        <i class="fas fa-chart-line"></i>
                        <span class="nav-link-text ms-2 text-light fw-bold">Laporan K Tiga</span>

                    </a>
                </li>
            </ul>


          

        </ul>
    </div>

    {{-- <div class="sidenav-footer mb-2 mt-5 text-center text-secondary">
        <small class="fw-bold mt-6 w-100">Extrupack@2023</small>
    </div> --}}





</aside>
<main class="main-content position-relative h-auto mt-0 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-0 sticky-top bg-primary ms-0" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-0 px-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-0 px-0 me-sm-6 me-6">

                </ol>
                <h6 class="font-weight-bolder mb-0 text-light">Hello, HR</h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-1 d-flex align-items-center">
<h1 class="text-light fw-bold"><marquee>INI DI LOKAL </marquee></h1>
                </div>
                <ul class="navbar-nav justify-content-end m-0">
                    <li class="d-flex align-items-center">
                        <h6 class="me-2 text-center font-weight-bold text-light"></h6>
                        <a href="/../../hr/dashboard/profile/index" class="nav-link text-body font-weight-bold px-0">
                            <button class="btn text-light m-0 bg-none shadow-none ">
                                <i class="fa fa-user me-sm-1 logo-container"></i>
                            </button>
                        </a>
                    </li>
                    <li class="nav-item px-1 d-flex align-items-center justify-content-center">
                        <form class="" action="/logout" method="post">
                            @csrf
                            <button type="submit" class="btn text-light m-0 bg-none shadow-none ">
                                <i class="fa-solid fa-right-from-bracket logo-container"></i>
                            </button>
                        </form>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row">
            @yield('content')
        </div>
    </div>
</main>



<script>
$(document).ready(function() {
    var currentUrl = window.location.pathname;
    if (currentUrl.startsWith("/hr/dashboard/pegawai") ||
        currentUrl.startsWith("/hr/dashboard/pegawai/kontrak/laporan") ||
        currentUrl.startsWith("/hr/dashboard/absen") ||
        currentUrl.startsWith("/hr/dashboard/tgllibur") ||
        currentUrl.startsWith("/hr/dashboard/overt") ||
        currentUrl.startsWith("/hr/dashboard/tdkabsen") ||
        currentUrl.startsWith("/hr/fingerprint") ||
        currentUrl.startsWith("/hr/dashboard/reportabsen") ||
        currentUrl.startsWith("/hr/dashboard/reportcuti") ||
        currentUrl.startsWith("/hr/dashboard/penilaiankerja") ||
        currentUrl.startsWith("/hr/dashboard/penilaiankerjareport") ||
        currentUrl.startsWith("/hr/dashboard/reporticb") ||
        currentUrl.startsWith("/hr/dashboard/reportipamdt") ||
        currentUrl.startsWith("/hr/dashboard/reporttlak") ||
        currentUrl.startsWith("/hr/dashboard/reportlkk") ||
        currentUrl.startsWith("/hr/dashboard/srtcuti") ||
        currentUrl.startsWith("/hr/dashboard/reportkerajinan") ||
        currentUrl.startsWith("/hr/dashboard/onoff")) {
        $("#collapse-kepegawaian").addClass("show");
    }
});

function toggleKepegawaian() {
    var kepegawaianCollapse = document.getElementById("collapse-kepegawaian");
    kepegawaianCollapse.classList.toggle("show");
}


    // training


    $(document).ready(function() {
        var currentSegment = "{{ Request::segment(3) }}";
        var currentSubSegment = "{{ Request::segment(4) }}";

        if (currentSegment === "training") {
            $("#collapse-training").addClass("show");

            if (currentSubSegment === "tr") {
                $("#tr-link").addClass("active");
            } else if (currentSubSegment === "materi") {
                $("#materi-link").addClass("active");
            } else if (currentSubSegment === "target") {
                $("#target-link").addClass("active");
            } else if (currentSubSegment === "bagian") {
                $("#bagian-link").addClass("active");
                $("#collapse-laporan").addClass("show"); // Menampilkan sub-menu laporan saat bagian aktif
            }
        } else if ((currentSegment === "materi" || currentSegment === "target") &&
            (currentSubSegment === "laporan" || currentSubSegment === "list")) {
            $("#collapse-training").addClass("show");
            $("#collapse-laporan").addClass("show");

            if (currentSegment === "materi") {
                $("#materi-laporan-link").addClass("active");
            } else if (currentSegment === "target") {
                $("#target-laporan-link").addClass("active");
            }
        }
    });

    function toggleTraining() {
        var trainingCollapse = document.getElementById("collapse-training");
        trainingCollapse.classList.toggle("show");

        // Menutup sub-menu laporan saat training menu ditoggle
        var laporanCollapse = document.getElementById("collapse-laporan");
        laporanCollapse.classList.remove("show");
    }

    function toggleLaporan() {
        var laporanCollapse = document.getElementById("collapse-laporan");
        laporanCollapse.classList.toggle("show");
    }




    // ktiga
    $(document).ready(function() {
        var isMenuVisible = false;

        // Tampilkan submenu jika halaman aktif adalah submenu
        if (window.location.pathname.includes('/hr/dashboard/ktiga/list') || window.location.pathname.includes(
                '/hr/dashboard/ktiga/reportktiga')) {
            $("#menu1").show();
            $("#menu2").show();
            isMenuVisible = true;
        }

        $("#menuButton").click(function() {
            if (isMenuVisible) {
                $("#menu1").hide();
                $("#menu2").hide();
                isMenuVisible = false;
            } else {
                $("#menu1").show();
                $("#menu2").show();
                isMenuVisible = true;
            }
        });
    });
</script>
<style>
    @keyframes slide {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }

        100% {
            transform: translateY(0);
        }
    }

    .sidenav-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        animation: slide 3s infinite;
    }



    /*  */
    .menu-button {
        background-color: #f8f9fa00;
        border: none;
        border-radius: 4px;
        color: #333;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 8px 12px;
        transition: background-color 0.3s ease;
    }

    .menu-button:hover {
        background-color: #367fa9;
    }

    .menu-button i {
        margin-right: 8px;
    }

    /* */
    .nav-item .nav-link:hover {
        background-color: #a5d8f6;
        color: #ffffff;
        transition: background-color 0.3s ease;
    }

    .nav-item .nav-link.active {
        background-color: #44e0ff;
        color: rgb(0, 0, 0);
        font-weight: bold;
    }

    .nav-item .nav-link.active i {
        color: rgb(23, 23, 23);
    }

    /*  */
    /* WA */
    ::-webkit-scrollbar {
        width: 0.1em;
        /* Ubah lebar scrollbar sesuai kebutuhan */
        height: 0.1em;
        /* Ubah tinggi scrollbar sesuai kebutuhan */
    }

    ::-webkit-scrollbar-thumb {
        background-color: transparent;
    }

    /*  */

    .logo-container {
        border-radius: 50%;
        /* Menjadikan latar belakang bulat */
    }

    .logo-container:hover {
        transform: scale(1.4);
    }
</style>
