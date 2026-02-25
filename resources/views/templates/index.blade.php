@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.{{ $variable }}.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.{{ $variable }}.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.{{ $variable }}.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.{{ $variable }}.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('{{ $routes.create }}') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.{{ $variable }}.new') }}</a>
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
                            <th class="whiteSpace-nowrap col-md-10">{{__('labels.{{ $variable }}.description')}}</th>
                            <th class="whiteSpace-nowrap col-md-2">{{__('labels.{{ $variable }}.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(${{ $variablePlural }} as ${{ $variable }})
                            <tr>
                                <td class="whiteSpace-nowrap text-left">{{ ${{ $variable }}->nome ?? '-' }}</td>
                                <td class="whiteSpace-nowrap text-center">
                                    @if(Auth::user()->canAccess('{{ $routes.edit }}'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.{{ $variable }}.edit')}}"
                                           href="{{ URL::signedRoute('{{ $routes.edit }}', ['{{ $variable }}' => ${{ $variable }}]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('{{ $routes.show }}'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.{{ $variable }}.visualize')}}"
                                           href="{{ URL::signedRoute('{{ $routes.show }}', ['{{ $variable }}' => ${{ $variable }}]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('{{ $routes.destroy }}'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.{{ $variable }}.destroy')}}"
                                           href="{{ URL::signedRoute('{{ $routes.destroy }}', ['{{ $variable }}' => ${{ $variable }}]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('{{ $variable }}.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.{{ $variable }}.history')}}"
                                           href="{{ URL::signedRoute('{{ $variable }}.history', ['{{ $variable }}' => ${{ $variable }}]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">{{ __('labels.{{ $variable }}.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ ${{ $variablePlural }}->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
