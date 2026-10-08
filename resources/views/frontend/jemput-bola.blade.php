{{--
    KONTRAK DATA:
    - Controller: App\Http\Controllers\Frontend\JemputBolaController::index()
    - $layanans   -> Collection inovasi jenis=2, eager load relasi 'berkas'
                     (tiap item: judul, deskripsi, berkas[]->berkas)
    - $siletonUrl -> URL aplikasi SILETON
--}}
@extends('layouts.frontend')

@section('title', 'Layanan Jemput Bola - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/jemput_bola.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Layanan Jemput Bola</h1>
        <p>
            <a href="{{ route('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> / Layanan Jemput Bola
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        @forelse ($layanans ?? [] as $layanan)
        @php
        $fotos = $layanan->berkas ?? collect();
        $fotoUtama = optional($fotos->first())->berkas;
        @endphp

        <div class="jb-intro-row">
            {{-- ===== Teks ===== --}}
            <div class="jb-intro-text text-sm-center text-md-start">
                <span class="jb-label text-sm-center"><i class="ti ti-car"></i> Layanan Jemput Bola</span>
                <h2>{{ $layanan->judul }}</h2>
                <p>{{ $layanan->deskripsi }}</p>

                {{-- Tombol ke SILETON. target=_blank + noopener noreferrer:
                             buka di tab baru & cegah halaman tujuan mengakses
                             window.opener situs kita. --}}
                <a href="{{ $siletonUrl }}" target="_blank" rel="noopener noreferrer" class="btn-sileton">
                    Ajukan lewat SILETON <i class="ti ti-arrow-up-right"></i>
                </a>
            </div>

            {{-- ===== Foto ===== --}}
            <div class="jb-intro-media">
                @if ($fotoUtama)
                <img src="{{ asset('storage/' . $fotoUtama) }}"
                    alt="{{ $layanan->judul }}"
                    class="jb-media-main"
                    id="jb-main-{{ $layanan->id }}">

                @if ($fotos->count() > 1)
                <div class="jb-media-thumbs justify-content-center" data-target="jb-main-{{ $layanan->id }}">
                    @foreach ($fotos as $i => $foto)
                    <img src="{{ asset('storage/' . $foto->berkas) }}"
                        alt="{{ $layanan->judul }} - foto {{ $i + 1 }}"
                        class="{{ $i === 0 ? 'active' : '' }}">
                    @endforeach
                </div>
                @endif
                @else
                <div class="jb-media-placeholder"><i class="ti ti-car"></i></div>
                @endif
            </div>
        </div>
        @empty
        <p class="text-secondary text-center py-5">
            Informasi layanan jemput bola belum tersedia.
        </p>
        @endforelse

        {{-- ===== Banner CTA bawah ===== --}}
        <div class="jb-cta">
            <div class="jb-cta-icon"><i class="ti ti-device-mobile-check"></i></div>
            <h3>Ajukan Layanan Jemput Bola Sekarang</h3>
            <p>
                Pengajuan dilakukan secara online melalui SILETON
                (Sistem Informasi Layanan Elektronik Terintegrasi Online).
            </p>
            <a href="{{ $siletonUrl }}" target="_blank" rel="noopener noreferrer" class="btn-sileton-light">
                Buka SILETON <i class="ti ti-arrow-up-right"></i>
            </a>
        </div>

    </div>
</section>

@endsection

@push('js')
<script>
    // Klik thumbnail -> ganti foto utama (tiap layanan punya galeri sendiri)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.jb-media-thumbs').forEach(function(strip) {
            const main = document.getElementById(strip.getAttribute('data-target'));
            strip.querySelectorAll('img').forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    main.src = thumb.src;
                    strip.querySelectorAll('img').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    thumb.classList.add('active');
                });
            });
        });
    });
</script>
@endpush