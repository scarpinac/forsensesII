@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/cadastro/origem_produto.js'])
@endsection
@section('title', __('labels.product_origin.title.destroy') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.product_origin.title.destroy') }} - {{ $origemProduto->descricao }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.product_origin.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.origem_produto.index') }}">{{ __('labels.product_origin.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.product_origin.title.destroy') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('cadastro.origem_produto.delete', ['origemProduto' => $origemProduto->id]) }}" method="POST">
                @method('DELETE')
                @include('cadastro.origem_produto.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> {{ __('labels.product_origin.destroy') }}</button>
                    <a href="{{ URL::signedRoute('cadastro.origem_produto.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.product_origin.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
