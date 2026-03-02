@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush

@section('js')
    @vite(['resources/js/cadastro/revenda.js'])
@endsection

@section('title', __('labels.revenda.title.create') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.revenda.title.create') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.revenda.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.revenda.index') }}">{{ __('labels.revenda.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.revenda.title.create') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('cadastro.revenda.store') }}" method="POST">
                @include('cadastro.revenda.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('labels.revenda.save') }}</button>
                    <a href="{{ URL::signedRoute('cadastro.revenda.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.revenda.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
