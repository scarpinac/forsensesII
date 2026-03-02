@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.product.breadcrumb.listing') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.product.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.product.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.product.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.produto.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.product.new') }}</a>
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
                            <th class="whiteSpace-nowrap">{{__('labels.product.description')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.product.code')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.product.price')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.product.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produtos as $produto)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $produto->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $produto->codigo }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ number_format($produto->precoUnitario, 2, ',', '.') }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('cadastro.produto.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.product.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.produto.edit', ['produto' => $produto]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.produto.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.product.visualize')}}"
                                           href="{{ URL::signedRoute('cadastro.produto.show', ['produto' => $produto]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.produto.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.product.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.produto.destroy', ['produto' => $produto]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.produto.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.product.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.produto.history', ['produto' => $produto]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('labels.product.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $produtos->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
