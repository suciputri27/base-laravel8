@extends('layouts.app')

@section('title', 'Tambah Berita')
@section('page-title', 'Tambah Berita')

@section('breadcrumb')
    <a href="{{ route('posts.index') }}">Berita</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Tambah Berita</span>
@endsection

@section('content')
    @php
        $formAction = route('posts.store');
        $formMethod = 'POST';
        $post = null;
    @endphp

    @include('posts.form')
@endsection
