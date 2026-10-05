<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'DISDUKCAPIL Kabupaten Agam')</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo-only-capil.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">

    {{-- Font & icon --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poltawski+Nowy:ital,wght@0,400..700;1,400..700&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    {{-- CSS utama --}}
    <link rel="stylesheet" href="{{ asset('css/frontend/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar.css') }}">

    {{-- CSS halaman --}}
    @stack('css')
</head>

<body>
    <nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top navbar-dark navbar-transparent">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('beranda') }}">
                <img src="{{ asset('img/logo-only-capil.png') }}" alt="logo" height="60">
                <div class="d-flex flex-column">
                    <h5 class="fw-bold"> <strong>DISDUKCAPIL</strong></h5>
                    <span> Kabupaten Agam </span>
                </div>
            </a>
            <a class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar2" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </a>
            <div class="offcanvas offcanvas-end"
                tabindex="-1"
                id="offcanvasNavbar2"
                aria-labelledby="offcanvasNavbar2Label">

                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">
                        <img src="{{ asset('img/logo-capil-full.png') }}" alt="logo" height="60">
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close">
                    </button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('beranda') }}"><i class="ti ti-home"></i> Beranda</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ti ti-building"></i> Profil
                            </a>

                            <ul class="dropdown-menu">
                                {{-- TODO: ganti url('/...') dengan route() begitu masing-masing halaman & route-nya sudah dibuat --}}
                                <li><a class="dropdown-item" href="{{ url('profil/tentang') }}"><i class="ti ti-building color-primary"></i> Tentang Dinas</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/visi_misi') }}"><i class="ti ti-eye color-primary"></i> Visi dan Misi Kabupaten Agam</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/motto') }}"><i class="ti ti-quote color-primary"></i> Motto</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/tugas_fungsi') }}"><i class="ti ti-briefcase color-primary"></i> Tugas dan Fungsi</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/sejarah') }}"><i class="ti ti-history color-primary"></i> Sejarah</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/struktur_organisasi') }}"><i class="ti ti-users color-primary"></i> Struktur Organisasi</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/maklumat') }}"><i class="ti ti-file-text color-primary"></i> Maklumat Pelayanan</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/standar_pelayanan') }}"><i class="ti ti-award color-primary"></i> Standar Pelayanan Publik</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/sop') }}"><i class="ti ti-file-description color-primary"></i> SOP</a></li>
                                <li><a class="dropdown-item" href="{{ url('profil/alur_pengaduan') }}"><i class="ti ti-alert-triangle color-primary"></i> Alur Pengaduan</a></li>
                                <li><a class="dropdown-item" href="{{ url('/skm') }}"><i class="ti ti-mood-smile-beam color-primary"></i> Survey Kepuasan Masyarakat</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ti ti-message-chatbot"></i> Pelayanan
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/persyaratan') }}"><i class="ti ti-checklist color-primary"></i> Persyaratan</a></li>
                                <li><a class="dropdown-item" href="{{ url('/formulir') }}"><i class="ti ti-file-text color-primary"></i> Formulir</a></li>
                                <li><a class="dropdown-item" href="https://sileton.agamkab.go.id" target="_blank"><i class="ti ti-globe color-primary"></i> Layanan Online</a></li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/layanan-jemput-bola') }}">
                                        <i class="ti ti-truck color-primary"></i> Layanan Jemput Bola
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('under-construction') }}"><i class="ti ti-chart-bar"></i> Data Penduduk</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('informasi') }}"><i class="ti ti-news"></i> Informasi</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/publikasi') }}"><i class="ti ti-files"></i> Publikasi</a>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- ===================================================== FOOTER ===================================================== -->
    <div class="border-footer"></div>
    <footer class="pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-3 mb-3">
                    <img src="{{ asset('img/logo-capil-full-white.png') }}" alt="logo" height="70">
                    <!-- <h5 class="fw-bold mt-3"> DISDUKCAPIL KABUPATEN AGAM </h5>
                    <p class="mt-1"> Dinas Kependudukan dan Pencatatan Sipil Kabupaten Agam. </p> -->
                    <div class="mt-3"> <i class=" ti ti-map-pin me-2"></i> Kabupaten Agam, Sumatera Barat </div>
                </div>
                <div class="col-4 col-lg-2">
                    <h6 class="fw-bold"> Menu </h6>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"> <a href="{{ url('beranda') }}">Beranda</a> </li>
                        <li class="mb-2"> <a href="{{ url('/tentang') }}">Profil</a> </li>
                        <li class="mb-2"> <a href="{{ url('/persyaratan') }}">Layanan</a> </li>
                        <li class="mb-2"> <a href="{{ url('informasi') }}">Informasi</a> </li>
                    </ul>
                </div>
                <div class="col-4 col-lg-2">
                    <h6 class="fw-bold"> Layanan </h6>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"> <a href="{{ url('/formulir') }}">Formulir</a> </li>
                        <li class="mb-2"> <a href="{{ url('/persyaratan') }}">Persyaratan</a> </li>
                        <li class="mb-2"> <a href="https://sileton.agamkab.go.id" target="_blank">Layanan Online</a> </li>
                        <li class="mb-2"> <a href="{{ url('/skm') }}">Survey</a> </li>
                    </ul>
                </div>
                <div class="col-4 col-lg-2">
                    <h6 class="fw-bold"> Hubungi Kami </h6>
                    <p class="mt-3"> <i class="ti ti-phone me-2"></i> {{ optional($profil)->no_telepon ?? '-' }} </p>
                    <p> <i class="ti ti-mail me-2"></i> {{ optional($profil)->email ?? '-' }} </p>
                    <div class="d-flex gap-3 fs-4"> <a href="#"> <i class="ti ti-brand-facebook"></i> </a> <a href="#"> <i class="ti ti-brand-instagram"></i> </a> <a href="#"> <i class="ti ti-brand-youtube"></i> </a> </div>
                </div>
                <div class="col-lg-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6709.947537757278!2d100.02450535979727!3d-0.31328821926813155!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd50d7547651d5f%3A0x8a382c1ce6d8cca2!2sDinas%20Kependudukan%20dan%20Pencatatan%20Sipil!5e0!3m2!1sid!2sid!4v1790042203898!5m2!1sid!2sid" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center small"> © {{ date('Y') }} Disdukcapil Kabupaten Agam. Developed by Diskominfo Kabupaten Agam. All Rights Reserved. </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="{{ asset('js/frontend/app.js') }}"></script>
    <script src="{{ asset('js/frontend/parallax.js') }}"></script>

    <!-- JS khusus halaman ini, ditambahkan lewat @push('js') di masing-masing view -->
    @stack('js')
</body>

</html>