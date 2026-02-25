@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.exemplo.title.destroy') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.exemplo.title.destroy') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.exemplo.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('template.exemplo.index') }}">{{ __('labels.exemplo.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.exemplo.title.destroy') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> {{ __('labels.exemplo.confirm_delete') }}</h5>
                <p>{{ __('labels.exemplo.delete_warning', ['name' => $exemplo->nome]) }}</p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <strong>{{ __('labels.exemplo.name') }}:</strong> {{ $exemplo->nome }}
                </div>
                <div class="col-md-6">
                    <strong>{{ __('labels.exemplo.status') }}:</strong> {{ $exemplo->status }}
                </div>
            </div>
            
            <form action="{{ URL::signedRoute('template.exemplo.delete', $exemplo->id) }}" method="POST" class="mt-3">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> {{ __('labels.exemplo.confirm_delete_button') }}
                </button>
                <a href="{{ URL::signedRoute('template.exemplo.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('labels.exemplo.cancel') }}
                </a>
            </form>
        </div>
    </div>
@endsection
