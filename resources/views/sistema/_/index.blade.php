@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('title', 'Gerador de Cadastros')

@section('content_header')
    <h1 class="m-0">Gerador de Cadastros</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configurar Novo Cadastro</h3>
                </div>
                <div class="card-body">
                    <form id="geradorForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="classe">Nome da Classe <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="classe" name="classe" required
                                           placeholder="Ex: Comissao, Produto, Usuario" maxlength="50">
                                    <small class="form-text text-muted">Será usado para criar Controller, Model, pastas, etc.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modulo_pai">Módulo Pai <span class="text-danger">*</span></label>
                                    <select class="form-control" id="modulo_pai" name="modulo_pai" required>
                                        <option value="">Selecione...</option>
                                    </select>
                                    <small class="form-text text-muted">Pasta onde o cadastro será criado (Ex: Sistema, Cadastro)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="criar_permissoes" name="criar_permissoes" checked>
                                    <label class="form-check-label" for="criar_permissoes">
                                        Criar Permissões Automaticamente
                                    </label>
                                    <small class="form-text text-muted">Cria permissões: index, create, edit, show, destroy, history</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="criar_menu" name="criar_menu" checked>
                                    <label class="form-check-label" for="criar_menu">
                                        Criar Menu Automaticamente
                                    </label>
                                    <small class="form-text text-muted">Adiciona menu no sistema com ícone padrão</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="soft_delete" name="soft_delete" checked>
                                    <label class="form-check-label" for="soft_delete">
                                        Soft Delete
                                    </label>
                                    <small class="form-text text-muted">Inclui deleted_at na tabela</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Campos do Cadastro</h4>
                            <button type="button" class="btn btn-primary btn-sm" onclick="adicionarCampo()">
                                <i class="fas fa-plus"></i> Adicionar Campo
                            </button>
                        </div>

                        <div id="camposContainer" class="mb-3">
                            <!-- Campos serão adicionados dinamicamente aqui -->
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-cogs"></i> Gerar Cadastro
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg ml-2" onclick="limparFormulario()">
                                    <i class="fas fa-trash"></i> Limpar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultado -->
    <div class="row" id="resultadoContainer" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Resultado da Geração</h3>
                </div>
                <div class="card-body">
                    <div id="resultadoContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template de Campo -->
<template id="campoTemplate">
    <div class="card campo-item mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Nome do Campo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control campo-nome" name="campos[][nome]" required
                           placeholder="Ex: nome, email, descricao" maxlength="50">
                </div>
                <div class="col-md-2">
                    <label>Tipo <span class="text-danger">*</span></label>
                    <select class="form-control campo-tipo" name="campos[][tipo]" required onchange="atualizarOpcoesCampo(this)">
                        <option value="">Selecione...</option>
                        <option value="string">String</option>
                        <option value="integer">Inteiro</option>
                        <option value="double">Decimal/Valor</option>
                        <option value="date">Data</option>
                        <option value="boolean">Booleano</option>
                        <option value="text">Texto Longo</option>
                        <option value="file">Arquivo</option>
                        <option value="select">Select/Relacionamento</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Tamanho Máximo</label>
                    <input type="number" class="form-control campo-max" name="campos[][max]"
                           placeholder="Ex: 255" min="1">
                </div>
                <div class="col-md-2">
                    <label>Relacionamento</label>
                    <select class="form-control campo-relacionamento" name="campos[][relacionamento]">
                        <option value="">Nenhum</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Opções</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input campo-obrigatorio" type="checkbox" name="campos[][obrigatorio]">
                        <label class="form-check-label">Obrigatório</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input campo-unique" type="checkbox" name="campos[][unique]">
                        <label class="form-check-label">Único</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerCampo(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('js')
<script>
// URLs assinadas geradas pelo Blade
window.geradorUrls = {
    modulos: '{{ route("gerador.modulos") }}',
    tabelas: '{{ route("gerador.tabelas") }}',
    generate: '{{ route("gerador.generate") }}'
};
</script>
@endpush
