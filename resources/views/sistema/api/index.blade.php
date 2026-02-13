@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.api.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.api.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.api.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.api.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.api.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.api.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-3">{{__('labels.api.api_type')}}</th>
                            <th class="whiteSpace-nowrap col-md-5">{{__('labels.api.credential')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.api.situation')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.api.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($apis as $api)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $api->descricao ?? '-' }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ Str::limit($api->credencial, 50) }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $api->situacao->descricao ?? '-' }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('sistema.api.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.api.edit')}}"
                                           href="{{ URL::signedRoute('sistema.api.edit', ['api' => $api]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('sistema.api.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.api.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.api.show', ['api' => $api]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.api.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.api.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.api.destroy', ['api' => $api]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.api.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.api.history')}}"
                                           href="{{ URL::signedRoute('sistema.api.history', ['api' => $api]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('labels.api.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $apis->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
