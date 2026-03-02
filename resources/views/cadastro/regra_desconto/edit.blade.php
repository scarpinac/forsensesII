@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/cadastro/regra_desconto.js'])
@endsection
@section('title', __('labels.discount_rule.title.edit') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.discount_rule.title.edit') }} - {{ $regraDesconto->descricao }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.discount_rule.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.regra_desconto.index') }}">{{ __('labels.discount_rule.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.discount_rule.title.edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('cadastro.regra_desconto.update', $regraDesconto->id) }}" method="POST">
                @method('PUT')
                @include('cadastro.regra_desconto.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{ __('labels.discount_rule.save_changes') }}</button>
                    <a href="{{ URL::signedRoute('cadastro.regra_desconto.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.discount_rule.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
