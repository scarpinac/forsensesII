@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/permissao.js'])
@endsection
@section('title', __('labels.permission.title.destroy') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.permission.title.destroy') }} - {{$permissao->descricao}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.permission.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.permissao.index') }}">{{ __('labels.permission.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.permission.title.destroy') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('sistema.permissao.delete', ['permissao' => $permissao->id]) }}" method="POST">
                @method('DELETE')
                @include('sistema.permissao.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> {{ __('labels.permission.destroy') }}</button>
                    <a href="{{ URL::signedRoute('sistema.permissao.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.permission.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
