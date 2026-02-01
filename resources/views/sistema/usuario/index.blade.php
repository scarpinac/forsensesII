@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.user.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.user.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.user.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.user.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.usuario.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.user.new') }}</a>
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
                            <th class="col-md-4">{{__('labels.user.name')}}</th>
                            <th class="col-md-4">{{__('labels.user.email')}}</th>
                            <th class="col-md-2">{{__('labels.user.admin')}}</th>
                            <th class="col-md-2">{{__('labels.user.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td class="text-left">{{ $usuario->name }}</td>
                                <td class="text-left">{{ $usuario->email }}</td>
                                <td class="text-left">{{ $usuario->admin ? 'Sim' : 'Não' }}</td>
                                <td class="text-center">
                                    @if(Auth::user()->canAccess('sistema.usuario.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.user.edit')}}"
                                           href="{{ URL::signedRoute('sistema.usuario.edit', ['usuario' => $usuario]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('sistema.usuario.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.user.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.usuario.show', ['usuario' => $usuario]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.usuario.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.user.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.usuario.destroy', ['usuario' => $usuario]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.usuario.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.user.history')}}"
                                           href="{{ URL::signedRoute('sistema.usuario.history', ['usuario' => $usuario]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">{{ __('labels.user.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $usuarios->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
