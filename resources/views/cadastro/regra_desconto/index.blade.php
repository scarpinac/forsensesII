@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.discount_rule.breadcrumb.listing') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.discount_rule.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.discount_rule.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.discount_rule.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.regra_desconto.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.discount_rule.new') }}</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table-system table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="whiteSpace-nowrap">{{__('labels.discount_rule.description')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.discount_rule.period')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.discount_rule.valorBase')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.discount_rule.descontoAcrescido')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.discount_rule.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($regrasDesconto as $regraDesconto)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $regraDesconto->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $regraDesconto->periodo }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ number_format($regraDesconto->valorBase, 2, ',', '.') }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ number_format($regraDesconto->descontoAcrescido, 2, ',', '.') }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('cadastro.regra_desconto.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.discount_rule.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.regra_desconto.edit', ['regraDesconto' => $regraDesconto]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.regra_desconto.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.discount_rule.visualize')}}"
                                           href="{{ URL::signedRoute('cadastro.regra_desconto.show', ['regraDesconto' => $regraDesconto]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.regra_desconto.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.discount_rule.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.regra_desconto.destroy', ['regraDesconto' => $regraDesconto]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.regra_desconto.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.discount_rule.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.regra_desconto.history', ['regraDesconto' => $regraDesconto]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">{{ __('labels.discount_rule.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $regrasDesconto->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
