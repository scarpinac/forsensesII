@extends('layouts.adminlte-with-language')

@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', __('labels.notification.breadcrumb.listing') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.notification.breadcrumb.listing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.notification.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.notification.breadcrumb.listing') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ URL::signedRoute('sistema.notificacao.create') }}" class="btn btn-system"><i class="fas fa-plus"></i> {{ __('labels.notification.new') }}</a>
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
                            <th class="col-md-6">{{__('labels.notification.title')}}</th>
{{--                            <th class="col-md-2">{{__('labels.notification.sendTo')}}</th>--}}
                            <th class="col-md-3">{{__('labels.notification.sendAt')}}</th>
                            <th class="col-md-1">{{__('labels.notification.sended')}}</th>
                            <th class="col-md-2">{{__('labels.notification.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notificacoes as $notificacao)
                            <tr>
                                <td class="text-left">{{ $notificacao->titulo }}</td>
{{--                                <td class="text-left">{{ $notificacao->enviarNotificacaoPara->descricao }}</td>--}}
                                <td class="text-left">{{ $notificacao->enviar_em->format('d/m/Y H:i') }}</td>
                                <td class="text-left">{{ $notificacao->enviado ? 'Sim' : 'Não' }}</td>

                                <td class="text-center">
                                    @if(Auth::user()->canAccess('sistema.notificacao.edit'))
                                        <a class="btn btn-outline-primary move btn-sm" title="{{__('labels.notification.edit')}}"
                                           href="{{ URL::signedRoute('sistema.notificacao.edit', ['notificacao' => $notificacao]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->canAccess('sistema.notificacao.show'))
                                        <a class="btn btn-outline-orange move btn-sm" title="{{__('labels.notification.visualize')}}"
                                           href="{{ URL::signedRoute('sistema.notificacao.show', ['notificacao' => $notificacao]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.notificacao.destroy'))
                                        <a class="btn btn-outline-danger move btn-sm" title="{{__('labels.notification.destroy')}}"
                                           href="{{ URL::signedRoute('sistema.notificacao.destroy', ['notificacao' => $notificacao]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->canAccess('sistema.notificacao.history'))
                                        <a class="btn btn-outline-dark move btn-sm" title="{{__('labels.notification.history')}}"
                                           href="{{ URL::signedRoute('sistema.notificacao.history', ['notificacao' => $notificacao]) }}">
                                            <i class="fas fa-newspaper"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">{{ __('labels.notification.no.records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $notificacoes->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
