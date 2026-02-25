@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('js')
    @vite(['resources/js/sistema/gerador.js'])
@endsection
@section('title', __('labels.gerador.title.show') )

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('labels.gerador.title.show') }} - {{$gerador->descricao}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('dashboard') }}">{{ __('labels.gerador.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('sistema.gerador.index') }}">{{ __('labels.gerador.breadcrumb.listing') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('labels.gerador.title.show') }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form">
                @include('sistema.gerador.form')

                @if($gerador->campos && $gerador->campos->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <h5>Campos Cadastrados</h5>
                        <div class="table-responsive">
                            <table class="table-system table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="whiteSpace-nowrap">Nome</th>
                                        <th class="whiteSpace-nowrap">Tipo</th>
                                        <th class="whiteSpace-nowrap">Tamanho Máximo</th>
                                        <th class="whiteSpace-nowrap">Relacionamento</th>
                                        <th class="whiteSpace-nowrap">Obrigatório</th>
                                        <th class="whiteSpace-nowrap">Único</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gerador->campos as $campo)
                                        <tr>
                                            <td class="whiteSpace-nowrap text-left">{{ $campo->nome }}</td>
                                            <td class="whiteSpace-nowrap text-left">{{ $campo->tipo->descricao ?? '-' }}</td>
                                            <td class="whiteSpace-nowrap text-left">{{ $campo->tamanho_maximo ?? '-' }}</td>
                                            <td class="whiteSpace-nowrap text-left">{{ $campo->relacionamento ?? '-' }}</td>
                                            <td class="whiteSpace-nowrap text-left">
                                                @if($campo->obrigatorio)
                                                    <span class="badge badge-success">Sim</span>
                                                @else
                                                    <span class="badge badge-secondary">Não</span>
                                                @endif
                                            </td>
                                            <td class="whiteSpace-nowrap text-left">
                                                @if($campo->unico)
                                                    <span class="badge badge-success">Sim</span>
                                                @else
                                                    <span class="badge badge-secondary">Não</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhum campo foi cadastrado para este gerador.
                        </div>
                    </div>
                </div>
                @endif

                @if(session()->has('gerador_logs'))
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h5>Logs da Geração de Arquivos</h5>
                        <div class="accordion" id="logsAccordion">
                            @foreach(session('gerador_logs') as $logSection => $logs)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $loop->index }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $loop->index }}" aria-expanded="true" aria-controls="collapse{{ $loop->index }}">
                                                {{ $logSection }}
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapse{{ $loop->index }}" class="collapse" aria-labelledby="heading{{ $loop->index }}" data-parent="#logsAccordion">
                                        <div class="card-body">
                                            <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; font-size: 12px; max-height: 400px; overflow-y: auto;">{{ implode("\n", $logs) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ URL::signedRoute('sistema.gerador.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('labels.gerador.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
