@extends('layouts.app')

@section('title', 'Edit Publikasi')
@section('page-title', 'Edit Publikasi')

@section('breadcrumb')
    <a href="{{ route('publikasi.index') }}">Publikasi</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Edit Publikasi</span>
@endsection

@section('content')
    @php
        $formAction = route('publikasi.update', ['publikasi' => $publikasi->encrypted_id]);
        $formMethod = 'PUT';
    @endphp

    @include('publikasi.form')
@endsection
