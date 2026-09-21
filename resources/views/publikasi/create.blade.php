@extends('layouts.app')

@section('title', 'Tambah Publikasi')
@section('page-title', 'Tambah Publikasi')

@section('breadcrumb')
    <a href="{{ route('publikasi.index') }}">Publikasi</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Tambah Publikasi</span>
@endsection

@section('content')
    @php
        $formAction = route('publikasi.store');
        $formMethod = 'POST';
        $post = null;
    @endphp

    @include('publikasi.form')
@endsection
