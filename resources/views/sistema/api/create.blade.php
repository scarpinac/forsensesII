@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/api.js'])
@endsection
@section('title', __('labels.api.title.create') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.api.title.create') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.api.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.api.index') }}">{{ __('labels.api.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.api.title.create') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('sistema.api.store') }}" method="POST">
                @include('sistema.api.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('labels.api.save') }}</button>
                    <a href="{{ URL::signedRoute('sistema.api.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.api.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
