@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/cadastro/tabela_preco.js'])
@endsection
@section('title', __('labels.price_table.title.show') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.price_table.title.show') }} - {{ $tabelaPreco->descricao }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.price_table.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.tabela_preco.index') }}">{{ __('labels.price_table.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.price_table.title.show') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form">
                @include('cadastro.tabela_preco.form')
                <div class="d-flex justify-content-between">
                    <a href="{{ URL::signedRoute('cadastro.tabela_preco.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.price_table.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
