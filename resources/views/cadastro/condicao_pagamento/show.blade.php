@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/cadastro/condicao_pagamento.js'])
@endsection
@section('title', __('labels.payment_condition.title.show') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.payment_condition.title.show') }} - {{ $condicaoPagamento->descricao }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.payment_condition.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.condicao_pagamento.index') }}">{{ __('labels.payment_condition.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.payment_condition.title.show') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form">
                @include('cadastro.condicao_pagamento.form')
                <div class="d-flex justify-content-between">
                    <a href="{{ URL::signedRoute('cadastro.condicao_pagamento.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.payment_condition.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
