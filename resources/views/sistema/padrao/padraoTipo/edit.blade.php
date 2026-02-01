@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/padraoTipo.js'])
@endsection
@section('title', __('labels.padraoTipo.breadcrumb.edit') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.padraoTipo.breadcrumb.edit') }} - {{$padraoTipo->descricao}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.padraoTipo.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.padrao.index') }}">{{ __('labels.padrao.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.padrao.padraoTipo.index', $padrao) }}">{{ __('labels.padraoTipo.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.padraoTipo.breadcrumb.edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('sistema.padrao.padraoTipo.update', [$padrao, $padraoTipo]) }}" method="POST">
                @method('PUT')
                @include('sistema.padrao.padraoTipo.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{ __('labels.padraoTipo.save_changes') }}</button>
                    <a href="{{ URL::signedRoute('sistema.padrao.padraoTipo.index', $padrao) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.padraoTipo.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
