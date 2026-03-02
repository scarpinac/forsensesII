@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/cadastro/comissao.js'])
@endsection
@section('title', __('labels.commission.title.edit') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.commission.title.edit') }} - {{number_format($comissao->valor, 2, ',', '.')}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.commission.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.comissao.index') }}">{{ __('labels.commission.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.commission.title.edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ URL::signedRoute('cadastro.comissao.update', $comissao->id) }}" method="POST">
                @method('PUT')
                @include('cadastro.comissao.form')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{ __('labels.commission.save_changes') }}</button>
                    <a href="{{ URL::signedRoute('cadastro.comissao.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.commission.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
