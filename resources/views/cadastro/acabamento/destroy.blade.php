@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/cadastro/acabamento.js'])
@endsection
@section('title', __('labels.finish.title.destroy') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.finish.title.destroy') }} - {{ $acabamento->descricao }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.finish.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.acabamento.index') }}">{{ __('labels.finish.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.finish.title.destroy') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('cadastro.acabamento.delete', ['acabamento' => $acabamento->id]) }}" method="POST">
                @method('DELETE')
                @include('cadastro.acabamento.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> {{ __('labels.finish.destroy') }}</button>
                    <a href="{{ URL::signedRoute('cadastro.acabamento.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.finish.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
