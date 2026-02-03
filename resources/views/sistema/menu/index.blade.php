@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.menu.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.menu.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.menu.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.menu.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.menu.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.menu.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-10">{{__('labels.menu.description')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.menu.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $menu->descricao }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('sistema.menu.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.menu.edit')}}"
                                           href="{{ URL::signedRoute('sistema.menu.edit', ['menu' => $menu]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('sistema.menu.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.menu.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.menu.show', ['menu' => $menu]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.menu.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.menu.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.menu.destroy', ['menu' => $menu]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.menu.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.menu.history')}}"
                                           href="{{ URL::signedRoute('sistema.menu.history', ['menu' => $menu]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">{{ __('labels.menu.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $menus->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
