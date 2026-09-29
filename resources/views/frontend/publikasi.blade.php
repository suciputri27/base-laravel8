{{--
    KONTRAK DATA:
    - Controller: App\Http\Controllers\Frontend\F_PublikasiController
    - index()  -> cuma render halaman ini, tanpa kirim data
    - data()   -> endpoint AJAX (route: publikasi.data), dipanggil dari JS
                  di public/js/frontend/publikasi/index.js
    - Field yang dipakai: judul, deskripsi, berkas
--}}
@extends('layouts.frontend')

@section('title', 'Publikasi - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/publikasi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Publikasi</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> / Publikasi
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <div class="publikasi-search">
            <i class="ti ti-search"></i>
            <input type="text" id="publikasi-search-input" class="form-control"
                placeholder="Cari judul atau deskripsi publikasi...">
        </div>

        <div id="publikasi-container" class="row g-4">
            {{-- Diisi otomatis lewat JS (fetch ke route('publikasi.data')) --}}
        </div>

        <div id="publikasi-pagination" class="publikasi-pagination"></div>

    </div>
</section>

@endsection

@push('js')
<script>
    // URL endpoint AJAX dikirim dari Blade supaya JS-nya tetap generic
    // dan tidak hardcode path di file .js
    window.publikasiDataUrl = "{{ url('publikasi/data') }}";
</script>
<script src="{{ asset('js/frontend/publikasi.js') }}"></script>
@endpush