@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.exemplo.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.exemplo.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.exemplo.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.exemplo.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('template.exemplo.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.exemplo.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-8">{{__('labels.exemplo.description')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.exemplo.status')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.exemplo.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exemplos as $exemplo)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $exemplo->nome }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if($exemplo->ativo)
                                        <span class="badge badge-success">Ativo</span>
                                    @else
                                        <span class="badge badge-danger">Inativo</span>
                                    @endif
                                </td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('template.exemplo.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.exemplo.edit')}}"
                                           href="{{ URL::signedRoute('template.exemplo.edit', ['exemplo' => $exemplo]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('template.exemplo.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.exemplo.visualize')}}"
                                           href="{{ URL::signedRoute('template.exemplo.show', ['exemplo' => $exemplo]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('template.exemplo.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.exemplo.destroy')}}"
                                           href="{{ URL::signedRoute('template.exemplo.destroy', ['exemplo' => $exemplo]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('template.exemplo.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.exemplo.history')}}"
                                           href="{{ URL::signedRoute('template.exemplo.history', ['exemplo' => $exemplo]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">{{ __('labels.exemplo.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $exemplos->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
