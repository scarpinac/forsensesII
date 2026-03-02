@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@push('js')
    @vite(['resources/js/cadastro/cliente.js'])
@endpush
@section('title', __('labels.customer.title.destroy') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.customer.title.destroy') }} - {{ $cliente->nome }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.customer.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.cliente.index') }}">{{ __('labels.customer.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.customer.title.destroy') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('cadastro.cliente.delete', ['cliente' => $cliente->id]) }}" method="POST">
                @method('DELETE')
                @include('cadastro.cliente.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> {{ __('labels.customer.destroy') }}</button>
                    <a href="{{ URL::signedRoute('cadastro.cliente.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.customer.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
