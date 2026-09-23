@extends('layouts.app')

@section('title', 'Tambah Informasi')
@section('page-title', 'Tambah Informasi')

@section('breadcrumb')
    <a href="{{ route('posts.index') }}">Informasi</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Tambah Informasi</span>
@endsection

@section('content')
    @php
        $formAction = route('posts.store');
        $formMethod = 'POST';
        $post = null;
    @endphp

    @include('posts.form')
@endsection
