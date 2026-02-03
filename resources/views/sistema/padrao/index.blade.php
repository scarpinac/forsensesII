@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.padrao.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.padrao.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.padrao.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.padrao.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.padrao.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.padrao.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-10">{{__('labels.padrao.fields.descricao')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.padrao.fields.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($padroes as $padrao)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $padrao->descricao }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('sistema.padrao.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.padrao.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.show', ['padrao' => $padrao]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.padrao.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.padrao.edit')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.edit', ['padrao' => $padrao]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.padrao.padraoTipo.index'))
                                        <a class="btn btn-outline-info move btn-sm" title="{{__('labels.padrao.padraoTipo')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.padraoTipo.index', ['padrao' => $padrao]) }}">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.padrao.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.padrao.history')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.history', ['padrao' => $padrao]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.padrao.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.padrao.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.padrao.destroy', ['padrao' => $padrao]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">{{ __('labels.padrao.no_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $padroes->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
