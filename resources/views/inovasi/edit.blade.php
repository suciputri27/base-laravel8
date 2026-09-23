@extends('layouts.app')

@section('title', 'Edit Inovasi')
@section('page-title', 'Edit Inovasi')

@section('breadcrumb')
    <a href="{{ route('inovasi.index') }}">Inovasi</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Edit Publikasi</span>
@endsection

@section('content')
    @php
        $formAction = route('inovasi.update', ['inovasi' => $inovasi->encrypted_id]);
        $formMethod = 'PUT';
    @endphp

    @include('inovasi.form')
@endsection
