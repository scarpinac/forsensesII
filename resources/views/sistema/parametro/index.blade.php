@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.parametro.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.parametro.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.parametro.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.parametro.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.parametro.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.parametro.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-3">{{__('labels.parametro.name')}}</th>
                            <th class="whiteSpace-nowrap col-md-3">{{__('labels.parametro.type')}}</th>
                            <th class="whiteSpace-nowrap col-md-4">{{__('labels.parametro.value')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.parametro.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parametros as $parametro)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $parametro->nome }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $parametro->tipo->descricao ?? '-' }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ Str::limit($parametro->valor, 100) }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('sistema.parametro.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.parametro.edit')}}"
                                           href="{{ URL::signedRoute('sistema.parametro.edit', ['parametro' => $parametro]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('sistema.parametro.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.parametro.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.parametro.show', ['parametro' => $parametro]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.parametro.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.parametro.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.parametro.destroy', ['parametro' => $parametro]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.parametro.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.parametro.history')}}"
                                           href="{{ URL::signedRoute('sistema.parametro.history', ['parametro' => $parametro]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">{{ __('labels.parametro.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $parametros->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
