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

let campoIndex = 0;

$(document).ready(function() {
    carregarModulos();
    carregarTabelas();

    $('#geradorForm').on('submit', function(e) {
        e.preventDefault();
        gerarCadastro();
    });
});

function carregarModulos() {
    $.get(window.geradorUrls.modulos)
        .done(function(data) {
            let select = $('#modulo_pai');
            select.empty().append('<option value="">Selecione...</option>');
            data.forEach(function(modulo) {
                select.append('<option value="' + modulo + '">' + modulo + '</option>');
            });
        })
        .fail(function() {
            toastr.error('Erro ao carregar módulos');
        });
}

function carregarTabelas() {
    $.get(window.geradorUrls.tabelas)
        .done(function(data) {
            window.tabelasDisponiveis = data;
        })
        .fail(function() {
            console.error('Erro ao carregar tabelas');
        });
}

function adicionarCampo() {
    let template = $('#campoTemplate').html();
    let container = $('#camposContainer');

    // Substituir índices
    template = template.replace(/campos\[\]/g, 'campos[' + campoIndex + ']');

    let campoDiv = $('<div>').html(template);
    container.append(campoDiv);

    campoIndex++;

    // Carregar relacionamentos disponíveis
    atualizarRelacionamentos();
}

function removerCampo(button) {
    $(button).closest('.campo-item').remove();
}

function atualizarOpcoesCampo(select) {
    let campoDiv = $(select).closest('.campo-item');
    let tipo = $(select).val();
    let relacionamentoSelect = campoDiv.find('.campo-relacionamento');
    let maxInput = campoDiv.find('.campo-max');

    if (tipo === 'select') {
        relacionamentoSelect.prop('disabled', false);
        maxInput.prop('disabled', true).val('');
    } else {
        relacionamentoSelect.prop('disabled', true).val('');
        maxInput.prop('disabled', false);
    }

    // Definir tamanho padrão para strings
    if (tipo === 'string' && !maxInput.val()) {
        maxInput.val(255);
    }
}

function atualizarRelacionamentos() {
    if (!window.tabelasDisponiveis) return;

    $('.campo-relacionamento').each(function() {
        let select = $(this);
        if (select.prop('disabled')) return;

        let currentValue = select.val();
        select.empty().append('<option value="">Nenhum</option>');

        window.tabelasDisponiveis.forEach(function(tabela) {
            // Converter nome da tabela para nome da classe (plural para singular)
            let classe = tabela.replace(/s$/, '');
            classe = classe.charAt(0).toUpperCase() + classe.slice(1);
            select.append('<option value="' + classe + '">' + classe + '</option>');
        });

        select.val(currentValue);
    });
}

function gerarCadastro() {
    let formData = $('#geradorForm').serializeArray();
    let data = {};

    // Agrupar campos
    formData.forEach(function(item) {
        if (item.name.startsWith('campos[')) {
            let match = item.name.match(/campos\[(\d+)\]\[(.+)\]/);
            if (match) {
                let index = match[1];
                let field = match[2];

                if (!data.campos) data.campos = [];
                if (!data.campos[index]) data.campos[index] = {};

                if (item.name.includes('obrigatorio') || item.name.includes('unique')) {
                    data.campos[index][field] = item.value === 'on';
                } else {
                    data.campos[index][field] = item.value;
                }
            }
        } else {
            data[item.name] = item.value;
        }
    });

    // Converter checkboxes
    data.criar_permissoes = data.criar_permissoes === 'on';
    data.criar_menu = data.criar_menu === 'on';
    data.soft_delete = data.soft_delete === 'on';
    data.timestamps = data.timestamps === 'on';

    // Validar campos
    if (!data.classe || !data.modulo_pai) {
        toastr.error('Preencha todos os campos obrigatórios');
        return;
    }

    if (!data.campos || data.campos.length === 0) {
        toastr.error('Adicione pelo menos um campo');
        return;
    }

    // Enviar requisição
    $.ajax({
        url: window.geradorUrls.generate,
        method: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .done(function(response) {
        if (response.success) {
            exibirResultado(response);
            toastr.success(response.message);
        } else {
            toastr.error(response.message);
        }
    })
    .fail(function(xhr) {
        let message = 'Erro ao gerar cadastro';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }
        toastr.error(message);
    });
}

function exibirResultado(response) {
    let html = '<div class="alert alert-success">';
    html += '<h5><i class="fas fa-check"></i> Cadastro gerado com sucesso!</h5>';
    html += '</div>';

    if (response.files && response.files.length > 0) {
        html += '<h6>Arquivos Criados:</h6><ul>';
        response.files.forEach(function(file) {
            html += '<li><code>' + file + '</code></li>';
        });
        html += '</ul>';
    }

    if (response.permissoes && response.permissoes.length > 0) {
        html += '<h6>Permissões Criadas:</h6><ul>';
        response.permissoes.forEach(function(permissao) {
            html += '<li><code>' + permissao + '</code></li>';
        });
        html += '</ul>';
    }

    if (response.menu) {
        html += '<h6>Menu Criado:</h6><ul>';
        html += '<li><strong>' + response.menu.descricao + '</strong> (' + response.menu.rota + ')</li>';
        html += '</ul>';
    }

    $('#resultadoContent').html(html);
    $('#resultadoContainer').show();

    // Scroll para o resultado
    $('html, body').animate({
        scrollTop: $('#resultadoContainer').offset().top
    }, 1000);
}

function limparFormulario() {
    $('#geradorForm')[0].reset();
    $('#camposContainer').empty();
    $('#resultadoContainer').hide();
    campoIndex = 0;
}
</script>
@endpush
