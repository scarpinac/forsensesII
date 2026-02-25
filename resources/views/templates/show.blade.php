@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/{{ $moduloLower }}/{{ $classeKebab }}.js'])
@endsection
@section('title', __('labels.{{ $variable }}.title.show') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.{{ $variable }}.title.show') }} - {{ ${{ $variable }}->nome }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.{{ $variable }}.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('{{ $routes.index }}') }}">{{ __('labels.{{ $variable }}.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.{{ $variable }}.title.show') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form">
                @include('{{ $formView }}')
                <div class="d-flex justify-content-between">
                    <a href="{{ URL::signedRoute('{{ $routes.index }}') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.{{ $variable }}.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
