@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.gerador.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.gerador.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.gerador.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.gerador.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.gerador.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.gerador.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-4">{{__('labels.gerador.classe')}}</th>
                            <th class="whiteSpace-nowrap col-md-3">{{__('labels.gerador.menuPai')}}</th>
                            <th class="whiteSpace-nowrap col-md-1">{{__('labels.gerador.criar_permissao')}}</th>
                            <th class="whiteSpace-nowrap col-md-1">{{__('labels.gerador._criar_menu')}}</th>
                            <th class="whiteSpace-nowrap col-md-1">{{__('labels.gerador.soft_delete')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.gerador.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($geradores as $gerador)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $gerador->classe }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $gerador->menu->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $gerador->criar_permissoes ? 'Sim' : 'Não' }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $gerador->criar_menu ? 'Sim' : 'Não' }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $gerador->soft_delete ? 'Sim' : 'Não' }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('sistema.gerador.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.gerador.edit')}}"
                                           href="{{ URL::signedRoute('sistema.gerador.edit', ['gerador' => $gerador]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('sistema.gerador.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.gerador.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.gerador.show', ['gerador' => $gerador]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.gerador.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.gerador.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.gerador.destroy', ['gerador' => $gerador]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.gerador.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.gerador.history')}}"
                                           href="{{ URL::signedRoute('sistema.gerador.history', ['gerador' => $gerador]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">{{ __('labels.gerador.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $geradores->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
