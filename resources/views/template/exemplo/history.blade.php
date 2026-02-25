@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.exemplo.title.history') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.exemplo.title.history') }} - {{ $exemplo->nome }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.exemplo.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('template.exemplo.index') }}">{{ __('labels.exemplo.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.exemplo.title.history') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('labels.exemplo.history_records') }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('labels.exemplo.history_date') }}</th>
                            <th>{{ __('labels.exemplo.history_user') }}</th>
                            <th>{{ __('labels.exemplo.history_type') }}</th>
                            <th>{{ __('labels.exemplo.history_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historicos as $historico)
                            <tr>
                                <td>{{ $historico->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $historico->user->name ?? '-' }}</td>
                                <td>
                                    @if($historico->tipoAlteracao)
                                        <span class="badge badge-info">{{ $historico->tipoAlteracao->descricao }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('labels.exemplo.unknown') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ URL::signedRoute('template.exemplo.history.details', ['exemplo' => $exemplo, 'historico' => $historico]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> {{ __('labels.exemplo.view_details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('labels.exemplo.no_history') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $historicos->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
