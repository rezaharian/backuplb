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


    <div class="collapse navbar-collapse w-auto max-height-vh-100 h-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="m-1">
          <a class="nav-link" href="/qc" onclick="setActiveMenu(event, '/qc', 'list-kerusakan')">
            <span class="nav-link-text ms-2">List Kerusakan</span>
          </a>
        </li>
        <li class="m-1">
          <a class="nav-link" href="/qc/dashboard/problemmsn/list" onclick="setActiveMenu(event, '/qc/dashboard/problemmsn/list', 'input-kerusakan')">
            <span class="nav-link-text ms-2">Input Kerusakan</span>
          </a>
        </li>
      </ul>
    </div>
    
    <script>
      function setActiveMenu(event, url, menuId) {
        event.preventDefault();
        
        var menuItems = document.querySelectorAll('.navbar-nav .nav-link');
        menuItems.forEach(function(item) {
          item.classList.remove('active');
        });
        
        var selectedMenu = event.target;
        selectedMenu.classList.add('active');
        
        window.location.href = url;
      }
      
      // Pengecekan URL saat halaman dimuat
      window.addEventListener('DOMContentLoaded', function() {
        var currentURL = window.location.pathname;
        var menuItems = document.querySelectorAll('.navbar-nav .nav-link');
        menuItems.forEach(function(item) {
          if (item.getAttribute('href') === currentURL) {
            item.classList.add('active');
          }
        });
      });
    </script>
    


    </div>
    <div class="sidenav-footer mx-3 ">
        <a class="btn bg-gradient-secondary mt-0 w-100" href="" type="button">Extrupack</a>
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
                <h6 class="font-weight-bolder mb-0 text-light">Hello, QC</h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-1 d-flex align-items-center">

                </div>
                <ul class="navbar-nav justify-content-end m-0">
                    <li class="d-flex align-items-center">
                        <h6 class="me-2 text-center font-weight-bold text-light"></h6>
                        <a href="/../../qc/dashboard/profile/index" class="nav-link text-body font-weight-bold px-0">
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
