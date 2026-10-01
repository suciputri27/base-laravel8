{{--
    KONTRAK DATA (sesuaikan dengan controller kamu):
    - Variabel: $inovasi -> single model inovasi, eager load relasi foto
      (diasumsikan nama relasi 'berkasInovasis' -> ganti kalau beda).
    - Kolom yang dipakai: judul, deskripsi, jenis, dan
      $inovasi->berkasInovasis (collection, tiap item punya ->berkas).
--}}
@extends('layouts.frontend')

@section('title', ($inovasi->judul ?? 'Detail Inovasi') . ' - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/detail_inovasi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Detail Inovasi</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> /
            <a href="{{ url('inovasi') }}" class="text-decoration-none color-primary">Inovasi</a> /
            Detail
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <a href="{{ url('inovasi') }}" class="btn btn-sm btn-custom mb-4">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Inovasi
                </a>

                @php
                $fotos = $inovasi->berkas ?? collect();
                $fotoUtama = asset('storage/'. optional($fotos->first())->berkas);
                @endphp

                {{-- Gambar utama --}}
                <img id="inovasi-main-image"
                    src="{{ $fotoUtama ? asset($fotoUtama) : '' }}"
                    alt="{{ $inovasi->judul }}"
                    class="inovasi-detail-main">

                {{-- Strip thumbnail, klik buat ganti gambar utama --}}
                @if ($fotos->count() > 1)
                <div class="inovasi-detail-thumbs justify-content-center">
                    @foreach ($fotos as $i => $foto)
                    <img src="{{ asset('storage/'. $foto->berkas) }}"
                        alt="{{ $inovasi->judul }} - foto {{ $i + 1 }}"
                        class="{{ $i === 0 ? 'active' : '' }}"
                        onclick="document.getElementById('inovasi-main-image').src = this.src;
                                              document.querySelectorAll('.inovasi-detail-thumbs img').forEach(el => el.classList.remove('active'));
                                              this.classList.add('active');">
                    @endforeach
                </div>
                @endif

                <div class="inovasi-detail-meta">
                    <span class="jenis-badge">
                        {{ $inovasi->jenis == 1 ? 'Inovasi' : 'Layanan Jemput Bola' }}
                    </span>
                </div>

                <h1 class="h3 inovasi-detail-title">{{ $inovasi->judul }}</h1>

                <div class="inovasi-detail-content">
                    <p>{{ $inovasi->deskripsi }}</p>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection