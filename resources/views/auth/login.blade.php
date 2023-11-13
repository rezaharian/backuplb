@extends('layouts.layoutLogin')
@extends('layouts.main')


@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <nav
                    class="navbar bg-gradient-info navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../pages/dashboard.html">
                            PT.EXTRUPACK
                        </a>


                        {{-- export otomatis --}}
                        <form action="{{ route('run.script') }}" method="POST">
                            @csrf
                            <button id="runScriptButton" class="btn btn-sm m-0" type="submit" name="runScript"></button>
                        </form>
                        <div id="countdown"></div>

                        {{-- end export otomatis --}}



                    </div>
                </nav>
                <!-- End Navbar -->


                @if (session('success'))
                    <div class="alert alert-info mt-5" id="success-message">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            document.getElementById('success-message').style.display = 'none';
                        }, 3000);
                    </script>
                @endif

            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header mt-3  min-vh-75">
                <div class="container">
                    <div class="row">

                        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain border-info  shadow   mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    {{-- alert --}}
                                    @if (session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    {{-- end alert --}}
                                    <h2 class="font-weight-bolder text-info text-gradient">Login Extrupack</h2>
                                    <p class="mb-0">Enter your email and password to sign in</p>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="/">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        {{-- <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div> --}}
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign
                                                in</button>
                                        </div>
                                    </form>


                                </div>
                                {{-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Don't have an account?
                    <a href="javascript:;" class="text-info text-gradient font-weight-bold">Sign up</a>
                  </p>
                </div> --}}
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="oblique position-absolute top-0 h-100 d-md-block d-none ">
                                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                                    style="background-image:url('../../../../assets/img/bglogin2.jpg')"></div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <p class="mb-0 text-secondary">
                        Copyright ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> Extrupack.com
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>



    {{-- tombol --}}
    <script>
        const button = document.getElementById('runScriptButton');
        const countdownElement = document.getElementById('countdown');

        // Fungsi untuk mendapatkan waktu saat ini dan waktu target (jam 17:00)
        function getTargetTime() {
            const currentTime = new Date();
            const targetTime = new Date();
            targetTime.setHours(17);
            targetTime.setMinutes(0);
            targetTime.setSeconds(0);

            // Jika waktu target sudah lewat, tambahkan 1 hari ke waktu target
            if (currentTime > targetTime) {
                targetTime.setDate(targetTime.getDate() + 1);
            }

            return targetTime;
        }

        // Fungsi untuk menghitung dan menampilkan waktu mundur
        function updateCountdown() {
            const currentTime = new Date();
            const targetTime = getTargetTime();
            const remainingTime = targetTime.getTime() - currentTime.getTime();

            // Menghentikan waktu mundur jika tombol sudah diklik
            if (remainingTime <= 0) {
                clearInterval(countdownInterval);
                return;
            }

            // Menghitung jam, menit, dan detik yang tersisa
            const hours = Math.floor(remainingTime / (1000 * 60 * 60));
            const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            // Menampilkan waktu mundur dalam format yang diinginkan
            countdownElement.textContent =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        // Memperbarui waktu mundur setiap detik
        const countdownInterval = setInterval(updateCountdown, 1000);

        // Mulai polling saat halaman dimuat
        function checkAndClickButton() {
            const currentTime = new Date();
            const targetTime = getTargetTime();

            if (currentTime >= targetTime) {
                button.click();
            } else {
                setTimeout(checkAndClickButton, 1000);
            }
        }

        checkAndClickButton();
    </script>
@endsection
