@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.padraoTipo.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.padraoTipo.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.padraoTipo.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.padrao.index') }}">{{ __('labels.padrao.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.padraoTipo.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.padrao.padraoTipo.create', $padrao) }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.padraoTipo.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-7">{{__('labels.padraoTipo.fields.descricao')}}</th>
                            <th class="whiteSpace-nowrap col-md-3">{{__('labels.padraoTipo.fields.padrao')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.padraoTipo.fields.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($padraoTipos as $padraoTipo)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $padraoTipo->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $padrao->descricao }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('sistema.padrao.padraoTipo.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.padraoTipo.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.padraoTipo.show', ['padrao' => $padrao, 'padraoTipo' => $padraoTipo]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.padrao.padraoTipo.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.padraoTipo.edit')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.padraoTipo.edit', ['padrao' => $padrao, 'padraoTipo' => $padraoTipo]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.padrao.padraoTipo.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.padraoTipo.history')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.padraoTipo.history', ['padrao' => $padrao, 'padraoTipo' => $padraoTipo]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.padrao.padraoTipo.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.padraoTipo.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.padraoTipo.destroy', ['padrao' => $padrao, 'padraoTipo' => $padraoTipo]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('labels.padraoTipo.no_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $padraoTipos->links('vendor.pagination.bootstrap-5') }}
            </div>
            <div class="mt-3">
                <a href="{{ URL::signedRoute('sistema.padrao.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('labels.padraoTipo.back') }}
                </a>
            </div>
        </div>
    </div>
@endsection
