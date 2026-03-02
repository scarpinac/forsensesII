@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.price_table.breadcrumb.listing') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.price_table.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.price_table.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.price_table.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.tabela_preco.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.price_table.new') }}</a>
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
                            <th class="whiteSpace-nowrap">{{__('labels.price_table.description')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.price_table.vigenciaAte')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.price_table.situacao')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.price_table.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tabelasPreco as $tabelaPreco)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $tabelaPreco->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $tabelaPreco->vigenciaAte ? $tabelaPreco->vigenciaAte->format('d/m/Y H:i') : '-' }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $tabelaPreco->situacao->descricao }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('cadastro.tabela_preco.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.price_table.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.tabela_preco.edit', ['tabelaPreco' => $tabelaPreco]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.tabela_preco.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.price_table.visualize')}}"
                                           href="{{ URL::signedRoute('cadastro.tabela_preco.show', ['tabelaPreco' => $tabelaPreco]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.tabela_preco.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.price_table.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.tabela_preco.destroy', ['tabelaPreco' => $tabelaPreco]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.tabela_preco.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.price_table.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.tabela_preco.history', ['tabelaPreco' => $tabelaPreco]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('labels.price_table.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $tabelasPreco->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
