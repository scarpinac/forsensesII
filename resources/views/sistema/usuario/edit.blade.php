@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/usuario.js'])
@endsection
@section('title', __('labels.user.title.edit') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.user.title.edit') }} - {{$usuario->name}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.user.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.usuario.index') }}">{{ __('labels.user.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.user.title.edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('sistema.usuario.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('sistema.usuario.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{ __('labels.user.save_changes') }}</button>
                    <a href="{{ URL::signedRoute('sistema.usuario.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.user.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
