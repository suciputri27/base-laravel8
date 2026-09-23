@extends('layouts.app')

@section('title', 'Tambah Inovasi')
@section('page-title', 'Tambah Inovasi')

@section('breadcrumb')
    <a href="{{ route('inovasi.index') }}">Inovasi</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Tambah Inovasi</span>
@endsection

@section('content')
    @php
        $formAction = route('inovasi.store');
        $formMethod = 'POST';
        $post = null;
    @endphp

    @include('inovasi.form')
@endsection
