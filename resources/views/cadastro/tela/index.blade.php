@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.screen.breadcrumb.listing') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.screen.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.screen.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.screen.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.tela.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.screen.new') }}</a>
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
                            <th class="whiteSpace-nowrap">{{__('labels.screen.description')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.screen.color')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.screen.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($telas as $tela)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $tela->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">
                                    <span class="badge" style="background-color: {{ $tela->cor->corHexadecimal }}; color: {{ $tela->cor->corHexadecimal }};">
                                        {{ $tela->cor->corHexadecimal }}
                                    </span>
                                </td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('cadastro.tela.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.screen.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.tela.edit', ['tela' => $tela]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.tela.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.screen.visualize')}}"
                                           href="{{ URL::signedRoute('cadastro.tela.show', ['tela' => $tela]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.tela.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.screen.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.tela.destroy', ['tela' => $tela]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.tela.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.screen.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.tela.history', ['tela' => $tela]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">{{ __('labels.screen.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $telas->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
