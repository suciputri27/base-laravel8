{{--
    KONTRAK DATA (sesuaikan dengan controller kamu):
    - Variabel: $inovasis -> Collection dari model inovasi, eager load
      relasi foto (nama relasi diasumsikan 'berkasInovasis' -> ganti sesuai
      nama relasi yang kamu bikin di model, kalau beda).
    - Kolom yang dipakai: judul, slug, deskripsi, jenis (1=Inovasi, 2=Layanan
      Jemput Bola), dan $item->berkasInovasis->first()->berkas untuk thumbnail.
--}}
@extends('layouts.frontend')

@section('title', 'Inovasi Pelayanan - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/inovasi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Inovasi Pelayanan</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> / Inovasi
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <div class="row g-4" id="inovasi-container">
            @forelse ($inovasis ?? [] as $item)
            @php
            $foto = asset('storage/' . optional($item->berkasUtama)->berkas);
            @endphp
            <div class="col-md-6 col-lg-4 inovasi-item" data-jenis="{{ $item->jenis }}">
                <a href="{{ url('inovasi', $item->slug) }}" class="inovasi-card">
                    @if ($foto)
                    <img src="{{ asset($foto) }}" alt="{{ $item->judul }}">
                    @endif
                    <div class="inovasi-content">
                        <span class="jenis-badge">
                            {{ $item->jenis == 1 ? 'Inovasi' : 'Layanan Jemput Bola' }}
                        </span>
                        <h4>{{ $item->judul }}</h4>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12">
                <p class="text-secondary text-center">Belum ada data inovasi.</p>
            </div>
            @endforelse
        </div>

        <p id="inovasi-empty-filter" class="text-secondary text-center d-none mt-4">
            Tidak ada data untuk kategori ini.
        </p>

    </div>
</section>

@endsection