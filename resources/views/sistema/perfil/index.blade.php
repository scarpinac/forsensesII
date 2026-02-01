@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.access_level.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.access_level.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.access_level.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.access_level.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.perfil.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.access_level.new') }}</a>
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
                            <th class="col-md-10">{{__('labels.access_level.description')}}</th>
                            <th class="col-md-2">{{__('labels.access_level.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perfis as $perfil)
                            <tr>
                                <td class="text-left">{{ $perfil->descricao }}</td>
                                <td class="text-center">
                                    @if(Auth::user()->canAccess('sistema.perfil.associate'))
                                        <a class="btn btn-outline-secondary move btn-sm" title="{{__('labels.access_level.associate')}}"
                                           href="{{ URL::signedRoute('sistema.perfil.edit', ['perfil' => $perfil]) }}">
                                            <i class="fas fa-people-arrows"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.perfil.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.access_level.edit')}}"
                                           href="{{ URL::signedRoute('sistema.perfil.edit', ['perfil' => $perfil]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('sistema.perfil.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.access_level.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.perfil.show', ['perfil' => $perfil]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.perfil.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.access_level.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.perfil.destroy', ['perfil' => $perfil]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.perfil.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.access_level.history')}}"
                                           href="{{ URL::signedRoute('sistema.perfil.history', ['perfil' => $perfil]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">{{ __('labels.access_level.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $perfis->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
