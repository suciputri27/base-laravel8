@extends('layouts.app')

@section('title', 'Edit Berita')
@section('page-title', 'Edit Berita')

@section('breadcrumb')
    <a href="{{ route('posts.index') }}">Berita</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Edit Berita</span>
@endsection

@section('content')
    @php
        $formAction = route('posts.update', ['post' => $post->encrypted_id]);
        $formMethod = 'PUT';
    @endphp

    @include('posts.form')
@endsection
