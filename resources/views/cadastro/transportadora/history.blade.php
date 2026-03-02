@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@push('js')
    @vite(['resources/js/cadastro/transportadora.js'])
@endpush
@section('title', __('labels.transportadora.title.history') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.transportadora.title.history') }} - {{ $transportadora->razaoSocial }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.transportadora.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('cadastro.transportadora.index') }}">{{ __('labels.transportadora.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.transportadora.title.history') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include('cadastro.transportadora.form')
            <h5 class="mt-4">{{ __('labels.transportadora.history.changes.title') }}</h5>
            <div class="table-responsive">
                <table class="table-system table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="whiteSpace-nowrap">{{__('labels.transportadora.history.table.date')}}</th>
                        <th class="whiteSpace-nowrap">{{__('labels.transportadora.history.table.user')}}</th>
                        <th class="whiteSpace-nowrap">{{__('labels.transportadora.history.table.type')}}</th>
                        <th class="whiteSpace-nowrap">{{__('labels.transportadora.history.table.actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transportadora->historicos as $historico)
                        <tr>
                            <td class="whiteSpace-nowrap">{{ $historico->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="whiteSpace-nowrap">{{ $historico->user->name ?? 'Sistema' }}</td>
                            <td class="whiteSpace-nowrap">{{ $historico->tipoAlteracao->descricao ?? '' }}</td>
                            <td class="whiteSpace-nowrap text-center">
                                <button class="btn btn-outline-info btn-sm detalhes" data-details-url="{{ URL::signedRoute('cadastro.transportadora.history.details', ['transportadora' => $transportadora->id, 'historico' => $historico->id]) }}">
                                    <i class="fas fa-eye"></i> {{ __('labels.transportadora.history.button.details') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('labels.transportadora.no.history') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ URL::signedRoute('cadastro.transportadora.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.transportadora.back') }}</a>
            </div>
        </div>
    </div>

    <!-- Modal para detalhes do histórico -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">{{ __('labels.transportadora.modal.details.title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detailsContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('labels.transportadora.modal.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
