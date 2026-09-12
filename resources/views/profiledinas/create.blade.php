@extends('layouts.app')

@section('title', 'Tambah Profile')
@section('page-title', 'Tambah Profile')

@section('breadcrumb')
    <a href="{{ route('profiledinas.index') }}">Profile</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Tambah Profile</span>
@endsection

@section('content')
    @php
        $formAction = route('profiledinas.store');
        $formMethod = 'POST';
        $post = null;
    @endphp

    @include('profiledinas.form')
@endsection
