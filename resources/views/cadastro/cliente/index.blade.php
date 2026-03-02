@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.customer.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.customer.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.customer.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.customer.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.cliente.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.customer.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-5">{{__('labels.customer.form.name')}}</th>
                            <th class="whiteSpace-nowrap col-md-3">{{__('labels.customer.form.type')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.customer.form.situation')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.customer.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $cliente->nome }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $cliente->tipoCliente->descricao ?? '' }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $cliente->situacao->descricao ?? '' }}</td>
                                        <td class="whiteSpace-nowrap text-center">
                                    @if(auth()->user()->canAccess('cadastro.cliente.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.customer.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.cliente.edit', ['cliente' => $cliente]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(auth()->user()->canAccess('cadastro.cliente.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.customer.show')}}"
                                           href="{{ URL::signedRoute('cadastro.cliente.show', ['cliente' => $cliente]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif

                                    @if(auth()->user()->canAccess('cadastro.cliente.history'))
                                        <a class="btn btn-outline-info move btn-sm" title="{{__('labels.customer.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.cliente.history', ['cliente' => $cliente]) }}">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    @endif

                                    @if(auth()->user()->canAccess('cadastro.cliente.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.customer.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.cliente.destroy', ['cliente' => $cliente]) }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('labels.customer.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $clientes->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
