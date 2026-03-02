@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.product_origin.breadcrumb.listing') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.product_origin.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.product_origin.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.product_origin.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.origem_produto.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.product_origin.new') }}</a>
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
                            <th class="whiteSpace-nowrap">{{__('labels.product_origin.code')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.product_origin.description')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.product_origin.situacao')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.product_origin.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($origensProduto as $origemProduto)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $origemProduto->codigo }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $origemProduto->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $origemProduto->situacao->descricao }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('cadastro.origem_produto.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.product_origin.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.origem_produto.edit', ['origemProduto' => $origemProduto]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.origem_produto.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.product_origin.visualize')}}"
                                           href="{{ URL::signedRoute('cadastro.origem_produto.show', ['origemProduto' => $origemProduto]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.origem_produto.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.product_origin.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.origem_produto.destroy', ['origemProduto' => $origemProduto]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.origem_produto.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.product_origin.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.origem_produto.history', ['origemProduto' => $origemProduto]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('labels.product_origin.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $origensProduto->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
