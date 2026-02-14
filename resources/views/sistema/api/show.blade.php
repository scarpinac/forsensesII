@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/api.js'])
@endsection
@section('title', __('labels.api.title.show') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.api.title.show') }} - {{$api->api->descricao}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.api.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.api.index') }}">{{ __('labels.api.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.api.title.show') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form">
                @include('sistema.api.form')
                <div class="d-flex justify-content-between">
                    <a href="{{ URL::signedRoute('sistema.api.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.api.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
