@extends('layouts.frontend')

@section('title', ($infos->title ?? 'Detail Informasi') . ' - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/detail-info.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Detail Berita</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> /
            <a href="{{ url('informasi') }}" class="text-decoration-none color-primary">Berita</a> /
            Detail
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <a href="{{ url('informasi') }}" class="btn btn-sm btn-custom mb-4">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Berita
                </a>

                @if ($infos->thumbnail)
                <img src="{{ asset('storage/' . $infos->thumbnail) }}" alt="{{ $infos->title }}" class="berita-detail-img">
                @endif

                <div class="berita-detail-meta">
                    <span class="kategori-badge">{{ $infos->kategori->name ?? 'Umum' }}</span>
                    <i class="ti ti-calendar me-1"></i>
                    {{ optional($infos->created_at)->translatedFormat('d F Y') }}
                </div>

                <h1 class="h3 berita-detail-title">{{ $infos->title }}</h1>

                {{--
                        Kolom 'konten' diasumsikan berisi HTML (hasil rich text
                        editor di admin), makanya pakai {!! !!} bukan {{ }}.
                Ini AMAN selama isi konten hanya bisa ditulis lewat
                admin panel yang terpercaya (bukan input publik tanpa
                sanitasi), karena {!! !!} tidak meng-escape HTML.
                --}}
                <div class="berita-detail-content">
                    {!! $infos->konten !!}
                </div>

            </div>
        </div>
    </div>
</section>

@endsection