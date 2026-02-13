@extends('adminlte::page')

@section('title', 'Visualizar Geração')

@section('content_header')
    <h1 class="m-0">Visualizar Geração</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalhes da Geração</h3>
                    <div class="card-tools">
                        <a href="{{ route('geracoes.edit', $geracao) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('geracoes.destroy', $geracao) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Excluir
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>ID:</strong> {{ $geracao->id }}
                        </div>
                        <div class="col-md-6">
                            <strong>Classe:</strong> {{ $geracao->classe }}
                        </div>
                        <div class="col-md-6">
                            <strong>Módulo Pai:</strong> {{ $geracao->modulo_pai }}
                        </div>
                        <div class="col-md-6">
                            <strong>Situação:</strong> 
                            @if($geracao->situacao)
                                <span class="badge badge-{{ $geracao->situacao->cor ?? 'secondary' }}">
                                    {{ $geracao->situacao->descricao }}
                                </span>
                            @else
                                <span class="badge badge-secondary">Sem situação</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Soft Delete:</strong> {{ $geracao->soft_delete ? 'Sim' : 'Não' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Timestamps:</strong> {{ $geracao->timestamps ? 'Sim' : 'Não' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Criar Permissões:</strong> {{ $geracao->criar_permissoes ? 'Sim' : 'Não' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Criar Menu:</strong> {{ $geracao->criar_menu ? 'Sim' : 'Não' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Observações:</strong> {{ $geracao->observacoes ?? 'Nenhuma' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Criado em:</strong> {{ $geracao->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Atualizado em:</strong> {{ $geracao->updated_at->format('d/m/Y H:i') }}
                        </div>
                        @if($geracao->campos)
                            <div class="col-md-12">
                                <strong>Campos:</strong>
                                <pre>{{ json_encode($geracao->campos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
