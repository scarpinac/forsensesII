@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.family.breadcrumb.listing') )
@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.family.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.family.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.family.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.familia.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.family.new') }}</a>
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
                            <th class="whiteSpace-nowrap">{{__('labels.family.description')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.family.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($familias as $familia)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $familia->descricao }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('cadastro.familia.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.family.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.familia.edit', ['familia' => $familia]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.familia.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.family.visualize')}}"
                                           href="{{ URL::signedRoute('cadastro.familia.show', ['familia' => $familia]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.familia.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.family.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.familia.destroy', ['familia' => $familia]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.familia.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.family.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.familia.history', ['familia' => $familia]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">{{ __('labels.family.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $familias->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
