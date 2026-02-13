@extends('adminlte::page')

@section('title', 'Gerações de Cadastros')

@section('content_header')
    <h1 class="m-0">Gerações de Cadastros</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Gerações</h3>
                    <div class="card-tools">
                        <a href="{{ route('geracoes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nova Geração
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Classe</th>
                                <th>Módulo Pai</th>
                                <th>Situação</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($geracoes as $geracao)
                                <tr>
                                    <td>{{ $geracao->id }}</td>
                                    <td>{{ $geracao->classe }}</td>
                                    <td>{{ $geracao->modulo_pai }}</td>
                                    <td>
                                        @if($geracao->situacao)
                                            <span class="badge badge-{{ $geracao->situacao->cor ?? 'secondary' }}">
                                                {{ $geracao->situacao->descricao }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Sem situação</span>
                                        @endif
                                    </td>
                                    <td>{{ $geracao->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('geracoes.show', $geracao) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('geracoes.edit', $geracao) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('geracoes.destroy', $geracao) }}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $geracoes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
