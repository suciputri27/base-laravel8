@extends('layouts.app')

@section('title', 'Tambah Struktur Organisasi')
@section('page-title', 'Tambah Stuktur Organisasi')

@section('breadcrumb')
    <a href="{{ route('struktur_organisasi.index') }}">Berita</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Tambah Struktur Organisasi</span>
@endsection

@section('content')
    @php
        $formAction = route('struktur_organisasi.store');
        $formMethod = 'POST';
        $post = null;
    @endphp

    @include('struktur_organisasi.form')
@endsection
