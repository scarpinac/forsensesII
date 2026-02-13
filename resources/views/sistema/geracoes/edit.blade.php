@extends('adminlte::page')

@section('title', 'Editar Geração')

@section('content_header')
    <h1 class="m-0">Editar Geração</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Geração</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('geracoes.update', $geracao) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="classe">Classe</label>
                                    <input type="text" id="classe" name="classe" class="form-control" value="{{ old('classe', $geracao->classe) }}" required>
                                    @error('classe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modulo_pai">Módulo Pai</label>
                                    <input type="text" id="modulo_pai" name="modulo_pai" class="form-control" value="{{ old('modulo_pai', $geracao->modulo_pai) }}" required>
                                    @error('modulo_pai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="campos">Campos (JSON)</label>
                                    <textarea id="campos" name="campos" class="form-control" rows="10">{{ old('campos', $geracao->campos) }}</textarea>
                                    @error('campos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="soft_delete">Soft Delete</label>
                                    <select id="soft_delete" name="soft_delete" class="form-control">
                                        <option value="1" {{ old('soft_delete', $geracao->soft_delete) == '1' ? 'selected' : '' }}>Sim</option>
                                        <option value="0" {{ old('soft_delete', $geracao->soft_delete) == '0' ? 'selected' : '' }}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="timestamps">Timestamps</label>
                                    <select id="timestamps" name="timestamps" class="form-control">
                                        <option value="1" {{ old('timestamps', $geracao->timestamps) == '1' ? 'selected' : '' }}>Sim</option>
                                        <option value="0" {{ old('timestamps', $geracao->timestamps) == '0' ? 'selected' : '' }}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="criar_permissoes">Criar Permissões</label>
                                    <select id="criar_permissoes" name="criar_permissoes" class="form-control">
                                        <option value="1" {{ old('criar_permissoes', $geracao->criar_permissoes) == '1' ? 'selected' : '' }}>Sim</option>
                                        <option value="0" {{ old('criar_permissoes', $geracao->criar_permissoes) == '0' ? 'selected' : '' }}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="criar_menu">Criar Menu</label>
                                    <select id="criar_menu" name="criar_menu" class="form-control">
                                        <option value="1" {{ old('criar_menu', $geracao->criar_menu) == '1' ? 'selected' : '' }}>Sim</option>
                                        <option value="0" {{ old('criar_menu', $geracao->criar_menu) == '0' ? 'selected' : '' }}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea id="observacoes" name="observacoes" class="form-control" rows="3">{{ old('observacoes', $geracao->observacoes) }}</textarea>
                                    @error('observacoes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="situacao_id">Situação</label>
                                    <select id="situacao_id" name="situacao_id" class="form-control" required>
                                        @foreach($situacoes as $situacao)
                                            <option value="{{ $situacao->id }}" {{ old('situacao_id', $geracao->situacao_id) == $situacao->id ? 'selected' : '' }}>{{ $situacao->descricao }}</option>
                                        @endforeach
                                    </select>
                                    @error('situacao_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                                <a href="{{ route('geracoes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
