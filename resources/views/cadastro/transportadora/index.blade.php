@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.transportadora.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.transportadora.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.transportadora.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.transportadora.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.transportadora.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.transportadora.new') }}</a>
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
                        <th class="whiteSpace-nowrap col-md-4">{{__('labels.transportadora.form.nomeFantasia')}}</th>
                        <th class="whiteSpace-nowrap col-md-4">{{__('labels.transportadora.form.razaoSocial')}}</th>
                        <th class="whiteSpace-nowrap col-md-2">{{__('labels.transportadora.form.situacao')}}</th>
                        <th class="whiteSpace-nowrap col-md-2">{{__('labels.transportadora.actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transportadoras as $transportadora)
                        <tr>
                            <td class="whiteSpace-nowrap text-left">{{ $transportadora->nomeFantasia }}</td>
                            <td class="whiteSpace-nowrap text-left">{{ $transportadora->razaoSocial }}</td>
                            <td class="whiteSpace-nowrap text-left">{{ $transportadora->situacao->descricao ?? '' }}</td>
                            <td class="whiteSpace-nowrap text-center">
                                @if(auth()->user()->canAccess('cadastro.transportadora.edit'))
                                    <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.transportadora.edit')}}"
                                       href="{{ URL::signedRoute('cadastro.transportadora.edit', ['transportadora' => $transportadora]) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endif

                                @if(auth()->user()->canAccess('cadastro.transportadora.show'))
                                    <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.transportadora.show')}}"
                                       href="{{ URL::signedRoute('cadastro.transportadora.show', ['transportadora' => $transportadora]) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif

                                @if(auth()->user()->canAccess('cadastro.transportadora.destroy'))
                                    <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.transportadora.destroy')}}"
                                       href="{{ URL::signedRoute('cadastro.transportadora.destroy', ['transportadora' => $transportadora]) }}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endif

                                @if(auth()->user()->canAccess('cadastro.transportadora.history'))
                                    <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.transportadora.history.description')}}"
                                       href="{{ URL::signedRoute('cadastro.transportadora.history', ['transportadora' => $transportadora]) }}">
                                        <i class="fas fa-newspaper"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('labels.transportadora.no.records') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $transportadoras->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
