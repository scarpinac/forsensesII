@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/perfil.js'])
@endsection
@section('title', __('labels.access_level.title.destroy') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.access_level.title.destroy') }} - {{$perfil->descricao}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.access_level.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.perfil.index') }}">{{ __('labels.access_level.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.access_level.title.destroy') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('sistema.perfil.delete', ['perfil' => $perfil->id]) }}" method="POST">
                @method('DELETE')
                @include('sistema.perfil.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> {{ __('labels.access_level.destroy') }}</button>
                    <a href="{{ URL::signedRoute('sistema.perfil.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.access_level.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
