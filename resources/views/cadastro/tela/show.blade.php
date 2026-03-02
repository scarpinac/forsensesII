@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/cadastro/tela.js'])
@endsection
@section('title', __('labels.screen.title.show') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.screen.title.show') }} - {{ $tela->descricao }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.screen.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.tela.index') }}">{{ __('labels.screen.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.screen.title.show') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form">
                @include('cadastro.tela.form')
                <div class="d-flex justify-content-between">
                    <a href="{{ URL::signedRoute('cadastro.tela.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.screen.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
