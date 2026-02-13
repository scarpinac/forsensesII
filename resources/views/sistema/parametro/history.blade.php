@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/parametro.js'])
@endsection
@section('title', __('labels.parametro.title.history') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.parametro.title.history') }} - {{$parametro->descricao}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.parametro.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.parametro.index') }}">{{ __('labels.parametro.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.parametro.title.history') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.parametro.history.data.title') }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form class="form">
            @include('sistema.parametro.form')
        </form>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.parametro.history.changes.title') }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table-system table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="whiteSpace-nowrap col-md-3">{{ __('labels.parametro.history.table.date') }}</th>
                        <th class="whiteSpace-nowrap col-md-3">{{ __('labels.parametro.history.table.user') }}</th>
                        <th class="whiteSpace-nowrap col-md-4">{{ __('labels.parametro.history.table.type') }}</th>
                        <th class="whiteSpace-nowrap col-md-2 text-center">{{ __('labels.parametro.history.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parametro->historicos as $historico)
                        <tr>
                            <td>{{ $historico->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $historico->user->name }}</td>
                            <td>{{ $historico->tipoAlteracao->descricao }}</td>
                            <td class="whiteSpace-nowrap text-center">
                                <button type="button" class="detalhes btn btn-outline-info btn-sm"
                                        data-details-url="{{ URL::signedRoute('sistema.parametro.history.details', ['parametro' => $historico->menu->id, 'historico' => $historico->id]) }}"
                                        title="{{ __('labels.parametro.history.button.details') }}">
                                    <i class="fas fa-search-plus"></i> {{ __('labels.parametro.history.button.details') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('labels.parametro.no.history') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{ URL::signedRoute('sistema.parametro.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.parametro.back') }}</a>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Modal de Detalhes -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">{{ __('labels.parametro.modal.details.title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detailsContent" class="table-responsive">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('labels.parametro.modal.close') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection
