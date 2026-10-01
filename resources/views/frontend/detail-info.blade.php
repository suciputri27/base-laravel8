@extends('layouts.frontend')

@section('title', ($post->title ?? 'Detail Informasi') . ' - DISDUKCAPIL Kabupaten Agam')

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

                @php $foto = $post->berkas->first(); @endphp
                @if ($foto)
                <img src="{{ storage_url($foto->berkas) }}" alt="{{ $post->title }}" class="berita-detail-img">
                @endif

                <div class="berita-detail-meta">
                    <span class="kategori-badge">{{ $post->category->name ?? 'Umum' }}</span>
                    <i class="ti ti-calendar me-1"></i>
                    {{ optional($post->published_at)->translatedFormat('d F Y') }}
                </div>

                <h1 class="h3 berita-detail-title">{{ $post->title }}</h1>

                {{--
                        Kolom 'content' diasumsikan berisi HTML (hasil rich text
                        editor di admin), makanya pakai {!! !!} bukan {{ }}.
                Ini AMAN selama isi konten hanya bisa ditulis lewat
                admin panel yang terpercaya (bukan input publik tanpa
                sanitasi), karena {!! !!} tidak meng-escape HTML.
                --}}
                <div class="berita-detail-content">
                    {!! $post->content !!}
                </div>

            </div>
        </div>
    </div>

@endsection