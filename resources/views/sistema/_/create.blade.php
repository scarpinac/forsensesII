@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/menu.js'])
@endsection
@section('title', __('labels.menu.title.create') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.menu.title.create') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.menu.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.menu.index') }}">{{ __('labels.menu.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.menu.title.create') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('sistema.menu.store') }}" method="POST">
                @include('sistema.menu.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('labels.menu.save') }}</button>
                    <a href="{{ URL::signedRoute('sistema.menu.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.menu.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
