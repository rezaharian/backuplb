<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
<title>Bootstrap Example</title>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



<aside
    class="sidenav navbar bg-gradient-light navbar-vertical navbar-expand-xs border-0 border-radius my-0 fixed-start ms-0 me-0"
    id="sidenav-main">
    <div class="sidenav-header text-center mb-2">
        <i class="fas fa-times p-1 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <img src="{{ '/image/logos/logo-ext-nav.jpg' }}" alt="Logo" style="width: 100%;">
    </div>


    <div class="collapse  navbar-collapse  w-auto  max-height-vh-100 h-100 mt-2" id="sidenav-collapse-main">
        <ul class="navbar-nav mt-2">

            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin') !== false && strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn') === false && strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/user') === false ? 'active' : ''; ?>" href="/../superadmin">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-link-text ms-2">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <input type="checkbox" id="kerusakan-toggle" class="menu-toggle">
                <label class="nav-link toggle-label <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn') !== false || strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn/list') !== false ? 'active' : ''; ?>" for="kerusakan-toggle">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="nav-link-text ms-2">Kerusakan</span>
                </label>
                <ul class="submenu" <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn') !== false || strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn/list') !== false ? 'style="display: block;"' : ''; ?>>
                    <li class="m-1">
                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn') !== false && strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn/list') === false ? 'active' : ''; ?>" href="/../superadmin/dashboard/problemmsn">
                            <span class="nav-link-text ms-2">List Kerusakan</span>
                        </a>
                    </li>
                    <li class="m-1">
                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/problemmsn/list') !== false ? 'active' : ''; ?>" href="/../superadmin/dashboard/problemmsn/list">
                            <span class="nav-link-text ms-2">Input Kerusakan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/user') !== false && strpos($_SERVER['REQUEST_URI'], '/superadmin/dashboard/user/dashboard') === false ? 'active' : ''; ?>" href="/../superadmin/dashboard/user">
                    <i class="fas fa-user"></i>
                    <span class="nav-link-text ms-2">User</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
        <p class="btn bg-gradient-secondary mt-0 w-70" href="" type="button">Extrupack</p>
    </div>
</aside>
<main class="main-content position-relative max-height-vh-100 h-100 mt-0 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-0 sticky-top bg-primary ms-0" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-0 px-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-0 px-0 me-sm-6 me-6">

                </ol>
                <h6 class="font-weight-bolder mb-0 text-light">Hello, Admin</h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-1 d-flex align-items-center">

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
        <div class="row ">
            @yield('content')
        </div>
    </div>
</main>


<style>
    .nav-item:hover .nav-link {
        background-color: #f0f0f0;
    }

    .nav-item .submenu li:hover .nav-link {
        background-color: #f0f0f0;
    }

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

    form-control {}

    /* jhghjbn */
    .menu-toggle,
    .toggle-label {
        display: none;
    }

    .submenu {
        display: none;
    }

    .menu-toggle:checked~.submenu {
        display: block;
    }
</style>

{{-- <li class="nav-item">
          <a class="nav-link  " href="../pages/billing.html">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>credit-card</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(453.000000, 454.000000)">
                        <path class="color-background opacity-6" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"></path>
                        <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Billing</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="../pages/virtual-reality.html">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>box-3d-50</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(603.000000, 0.000000)">
                        <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                        <path class="color-background opacity-6" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"></path>
                        <path class="color-background opacity-6" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Virtual Reality</span>
          </a>
        </li> 
            <li class="nav-item " hidden>
          <a class="nav-link  " href="/../superadmin/dashboard/loogbok/line">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>office</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g id="office" transform="translate(153.000000, 2.000000)">
                        <path class="color-background opacity-6" d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z"></path>
                        <path class="color-background" d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">LogBook Extruder</span>
          </a>
        </li>
        <li class="nav-item" hidden>
          <a class="nav-link  " href="/../superadmin/dashboard/loogbok/linecap">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>office</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g id="office" transform="translate(153.000000, 2.000000)">
                        <path class="color-background opacity-6" d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z"></path>
                        <path class="color-background" d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">LogBook Capping</span>
          </a>
        </li> --}}
