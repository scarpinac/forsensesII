@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.finish.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.finish.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.finish.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.finish.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('cadastro.acabamento.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.finish.new') }}</a>
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
                            <th class="whiteSpace-nowrap">{{__('labels.finish.description')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.finish.type')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.finish.color')}}</th>
                            <th class="whiteSpace-nowrap">{{__('labels.finish.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($acabamentos as $acabamento)
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ $acabamento->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">{{ $acabamento->tipoAcabamento->descricao }}</td>
                                <td class="whiteSpace-nowrap text-left">
                                    <span class="badge" style="background-color: {{ $acabamento->cor->corHexadecimal }}; color: {{ $acabamento->cor->corHexadecimal }};">
                                        {{ $acabamento->cor->corHexadecimal }}
                                    </span>
                                </td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('cadastro.acabamento.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.finish.edit')}}"
                                           href="{{ URL::signedRoute('cadastro.acabamento.edit', ['acabamento' => $acabamento]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('cadastro.acabamento.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.finish.visualize')}}"
                                           href="{{ URL::signedRoute('cadastro.acabamento.show', ['acabamento' => $acabamento]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.acabamento.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.finish.destroy')}}"
                                           href="{{ URL::signedRoute('cadastro.acabamento.destroy', ['acabamento' => $acabamento]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('cadastro.acabamento.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.finish.history.description')}}"
                                           href="{{ URL::signedRoute('cadastro.acabamento.history', ['acabamento' => $acabamento]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('labels.finish.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $acabamentos->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
