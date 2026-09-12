@extends('layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('breadcrumb')
    <a href="{{ route('profiledinas.index') }}">Profile</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Edit Profile</span>
@endsection

@section('content')
    @php
        $formAction = route('profiledinas.update', ['profiledinas' => $profiledinas->encrypted_id]);
        $formMethod = 'PUT';
    @endphp

    @include('profiledinas.form')
@endsection
