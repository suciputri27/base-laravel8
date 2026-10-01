@extends('layouts.frontend')

@section('title', 'Beranda - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Beranda dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/beranda.css') }}">
@endpush

@section('content')

<section class="hero" data-parallax data-parallax-speed="0.4">
    <div class="container">
        <div class="hero-content">
            <span class="badge bg-light color-secondary mb-3 px-3 py-2"> Selamat Datang </span>
            <h1 class="hero-title"> DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL KABUPATEN AGAM </h1>
            <!-- <p class="hero-description mt-2"> Informasi layanan administrasi kependudukan, pelayanan online, berita, serta informasi terbaru Disdukcapil Kabupaten Agam. </p> -->
            <!-- <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ url('/persyaratan') }}" class="btn btn-light btn-lg px-4"> <i class="ti ti-file-text me-1"></i> Lihat Layanan </a>
                <a href="#" class="btn btn-outline-light btn-lg px-4"> <i class="ti ti-world me-1"></i> Layanan Online </a>
            </div> -->
        </div>
    </div>
    <img class="ornamen-hero" src="{{ asset('img/ornamen-capil.png') }}" alt="logo">
</section>

<!-- ===================================================== HERO SLIDER BERITA ===================================================== -->
<!-- <section class="berita-hero-slider">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-inner">

            @foreach ($beritaTerbaru ?? [] as $item)
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}"
                style="background-image: url('{{ asset('storage/' . $item->thumbnail) }}')">
                <div class="berita-hero-overlay"></div>
                <div class="container">
                    <div class="berita-hero-content mt-md-5">
                        <span class="badge bg-light color-secondary mb-3 px-3 py-2 berita-hero-animate" style="--delay:.1s">
                            {{ $item->kategori->name ?? 'Umum' }}
                        </span>
                        <h1 class="berita-hero-title berita-hero-animate" style="--delay:.3s">
                            {{ $item->title ?? 'Judul tidak ditemukan' }}
                        </h1>
                        <p class="berita-hero-animate berita-hero-description" style="--delay:.5s">{{ $item->excerpt ?? '' }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-4 berita-hero-animate" style="--delay:.7s">
                            <a href="{{ url('informasi', $item->slug) }}" class="btn btn-light btn-lg px-4">
                                <i class="ti ti-news me-1"></i> Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach


        </div>


        {{-- Indikator titik carousel, jumlahnya juga harus ikut dinamis --}}
        <div class="carousel-indicators berita-hero-indicators">
            @foreach ($beritaTerbaru ?? [] as $item)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $loop->index }}"
                class="{{ $loop->first ? 'active' : '' }}"
                aria-current="{{ $loop->first ? 'true' : 'false' }}"
                aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>

    </div>
</section> -->

<!-- ===================================================== MENU POPULER ===================================================== -->
<section class="section popular-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title"> Menu Populer </h2>
            <p class="section-description"> Akses cepat informasi dan layanan yang paling sering digunakan. </p>
        </div>
        <div class="row g-4">
            <!-- Formulir -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ url('/formulir') }}" class="service-card">
                    <div class="service-icon"> <i class="ti ti-file-text"></i> </div>
                    <h5> Formulir </h5>
                    <p> Download formulir administrasi kependudukan. </p>
                </a>
            </div>
            <!-- Persyaratan -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ url('/persyaratan') }}" class="service-card">
                    <div class="service-icon"> <i class="ti ti-list-check"></i> </div>
                    <h5> Persyaratan </h5>
                    <p> Informasi persyaratan setiap jenis layanan. </p>
                </a>
            </div>
            <!-- SILetON -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="#" class="service-card">
                    <div class="service-icon"> <i class="ti ti-world"></i> </div>
                    <h5> Layanan Online SILetON </h5>
                    <p> Ajukan layanan administrasi kependudukan secara online. </p>
                </a>
            </div>
            <!-- Survey -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ url('/skm') }}" class="service-card">
                    <div class="service-icon"> <i class="ti ti-star"></i> </div>
                    <h5> Survey Kepuasan </h5>
                    <p> Sampaikan penilaian terhadap pelayanan kami. </p>
                </a>
            </div>
            <!-- Jadwal -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="#" class="service-card">
                    <div class="service-icon"> <i class="ti ti-calendar"></i> </div>
                    <h5> Jadwal Layanan </h5>
                    <p> Jadwal layanan keliling dan layanan rutin. </p>
                </a>
            </div>
            <!-- Data -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="#" class="service-card">
                    <div class="service-icon"> <i class="ti ti-chart-bar"></i> </div>
                    <h5> Data Agregat </h5>
                    <p> Informasi data agregat kependudukan Kabupaten Agam. </p>
                </a>
            </div>
            <!-- Inovasi -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="#" class="service-card">
                    <div class="service-icon"> <i class="ti ti-bulb"></i> </div>
                    <h5> Inovasi </h5>
                    <p> Berbagai inovasi pelayanan Disdukcapil. </p>
                </a>
            </div>
            <!-- Info Pelayanan -->
            <div class="col-6 col-md-4 col-lg-3">
                <a href="#" class="service-card">
                    <div class="service-icon"> <i class="ti ti-info-circle"></i> </div>
                    <h5> Info Pelayanan </h5>
                    <p> Informasi lengkap mengenai pelayanan. </p>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ===================================================== BERITA ===================================================== -->
<section class="section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 gap-3 flex-wrap">
            <div>
                <h2 class="section-title"> Informasi </h2>
                <p class="section-description mb-0"> Informasi dan berita terbaru Disdukcapil Kabupaten Agam. </p>
            </div>
            <a href="{{ url('/informasi') }}" class="btn btn-custom">Lihat Semua <i class="ti ti-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @foreach ($beritaTerbaru ?? [] as $item)
            <div class="col-md-6 col-lg-4">
                <article class="news-card">
                    @php $foto = $item->berkas->first(); @endphp
                    @if ($foto)
                    <img src="{{ storage_url($foto->berkas) }}" alt="{{ $item->title }}" class="news-image">
                    @endif
                    <div class="p-4">
                        <div class="news-category mb-2"> {{ $item->category->name ?? 'Umum' }} </div>
                        <h3 class="news-title"> <a href="{{ url('informasi', $item->slug) }}"> {{ $item->title ?? 'Judul tidak ditemukan' }} </a> </h3>
                        <small class="text-secondary"> <i class="ti ti-calendar me-1"></i> {{ optional($item->created_at)->translatedFormat('d F Y') }} </small>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>

</section>

<!-- ===================================================== INOVASI ===================================================== -->
<section class="section popular-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 gap-3 flex-wrap">
            <div>
                <h2 class="section-title"> Inovasi Pelayanan </h2>
                <p class="section-description mb-0"> Inovasi untuk memberikan pelayanan yang lebih mudah dan cepat. </p>
            </div>
            <a href="{{ url('/inovasi') }}" class="btn btn-custom"> Semua Inovasi <i class="ti ti-arrow-right ms-1"></i> </a>
        </div>
        <div class="row g-4">
            @foreach ($inovasiAktif ?? [] as $ivs)
            <div class="col-lg-4">
                <a href="{{ url('inovasi', $ivs->slug) }}" class="text-decoration-none">
                    <div class="innovation-card">
                        <img src="{{ storage_url($ivs->berkas->first()->berkas ?? '') }}" alt="Inovasi">
                        <div class="innovation-content">
                            <small> INOVASI </small>
                            <h4 class="fw-bold"> {{ $ivs->judul ?? 'Judul tidak ditemukan' }} </h4>
                            <!-- <p class="mb-0 small"> Kemudahan akses layanan administrasi kependudukan secara digital. </p> -->
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection