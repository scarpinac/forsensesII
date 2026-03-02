@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.transportadora.title.destroy') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.transportadora.title.destroy') }} - {{ $transportadora->razaoSocial }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.transportadora.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.transportadora.index') }}">{{ __('labels.transportadora.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.transportadora.title.destroy') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('cadastro.transportadora.delete', ['transportadora' => $transportadora->id]) }}" method="POST">
                @method('DELETE')
                @include('cadastro.transportadora.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> {{ __('labels.transportadora.destroy') }}</button>
                    <a href="{{ URL::signedRoute('cadastro.transportadora.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.transportadora.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
