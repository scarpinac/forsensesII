@csrf

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">Erros encontrados:</h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="row">
    <div class="form-group col-md-6">
        <label for="classe">{{ __('labels.gerador.form.classe') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="classe" id="classe" class="form-control @error('classe') is-invalid @enderror" value="{{ old('classe', $gerador->classe ?? null) }}">
        @error('classe')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="menuPai_id">{{ __('labels.gerador.form.menuPai') }}</label>
        <select name="menuPai_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="menuPai_id" class="form-control @error('menuPai_id') is-invalid @enderror {{isset($bloquearCampos) && $bloquearCampos ? '' : 'select2'}}" >
            @foreach($menusPai ?? [] as $menuPai)
                <option value="{{ $menuPai->id }}" {{ old('menuPai_id', $gerador->menuPai_id ?? null) == $menuPai ? 'selected' : '' }}>
                    {{ $menuPai->descricao }}
                </option>
            @endforeach
        </select>
        @error('menuPai_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-check col-md-4">
        <input
            {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}}
            class="form-check-input @error('criar_permissoes') is-invalid @enderror" type="checkbox" id="criar_permissoes" name="criar_permissoes"
            {{ isset($gerador->criar_permissoes) && $gerador->criar_permissoes || old('criar_permissoes') ? 'checked' : ''}}>
        <label class="form-check-label" for="criar_permissoes">
            Criar Permissões Automaticamente
        </label>
        @error('criar_permissoes')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-check col-md-4">
        <input
            {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}}
            class="form-check-input @error('criar_menu') is-invalid @enderror" type="checkbox" id="criar_menu" name="criar_menu"
            {{ isset($gerador->criar_menu) && $gerador->criar_menu || old('criar_menu') ? 'checked' : ''}}>
        <label class="form-check-label" for="criar_menu">
            Criar Menu Automaticamente
        </label>
        @error('criar_menu')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-check col-md-4">
        <input
            {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}}
            class="form-check-input @error('soft_delete') is-invalid @enderror" type="checkbox" id="soft_delete" name="soft_delete"
            {{ isset($gerador->soft_delete) && $gerador->soft_delete || old('soft_delete') ? 'checked' : ''}}>
        <label class="form-check-label" for="soft_delete">
            Soft Delete
        </label>
        @error('soft_delete')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<hr>
@if(!isset($bloquearCampos) || !$bloquearCampos)
    <div class="row mt-1">
        <hr>
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-primary btn-sm" onclick="adicionarCampo()">
                <i class="fas fa-plus"></i> Adicionar Campo
            </button>
        </div>
    </div>
@endif
<div class="row mt-1">
    <div id="camposContainer" class="mb-3 col-md-12">
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
                        @foreach($tiposCampos as $tipoCampo)
                            <option value="{{ $tipoCampo->id }}">{{ $tipoCampo->descricao }}</option>
                        @endforeach
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
                        @foreach($arTabelas as $tabela)
                            <option value="{{$tabela}}">{{$tabela}}</option>
                        @endforeach
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

@if(isset($camposAntigos) && count($camposAntigos) > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const camposAntigos = @json($camposAntigos);

    // Função para preencher um campo específico
    function preencherCampo(campo, index) {
        console.log('Preenchendo campo:', index, campo);
        adicionarCampo();

        // Pequeno delay para garantir que o DOM seja atualizado
        setTimeout(() => {
            // O campo-item está dentro de um div criado pelo jQuery
            const ultimoCampoDiv = document.querySelector('#camposContainer > div:last-child');
            const campoItem = ultimoCampoDiv ? ultimoCampoDiv.querySelector('.campo-item') : null;

            console.log('Elemento encontrado:', campoItem);

            if (!campoItem) {
                console.error('Campo não encontrado:', index);
                console.log('Container content:', document.getElementById('camposContainer').innerHTML);
                return;
            }

            if (campo.nome) {
                const nomeInput = campoItem.querySelector('.campo-nome');
                if (nomeInput) {
                    nomeInput.value = campo.nome;
                    console.log('Nome preenchido:', campo.nome);
                }
            }

            if (campo.tipo) {
                const tipoSelect = campoItem.querySelector('.campo-tipo');
                if (tipoSelect) {
                    tipoSelect.value = campo.tipo;
                    atualizarOpcoesCampo(tipoSelect);
                    console.log('Tipo preenchido:', campo.tipo);
                }
            }

            if (campo.max) {
                const maxInput = campoItem.querySelector('.campo-max');
                if (maxInput) {
                    maxInput.value = campo.max;
                    console.log('Max preenchido:', campo.max);
                }
            }

            if (campo.relacionamento) {
                const relSelect = campoItem.querySelector('.campo-relacionamento');
                if (relSelect) {
                    relSelect.value = campo.relacionamento;
                    console.log('Relacionamento preenchido:', campo.relacionamento);
                }
            }

            if (campo.obrigatorio) {
                const obrigatorioCheck = campoItem.querySelector('.campo-obrigatorio');
                if (obrigatorioCheck) {
                    obrigatorioCheck.checked = true;
                    console.log('Obrigatório marcado');
                }
            }

            if (campo.unique) {
                const uniqueCheck = campoItem.querySelector('.campo-unique');
                if (uniqueCheck) {
                    uniqueCheck.checked = true;
                    console.log('Único marcado');
                }
            }
        }, 100);
    }

    // Preencher cada campo com um pequeno intervalo entre eles
    camposAntigos.forEach(function(campo, index) {
        setTimeout(() => preencherCampo(campo, index), index * 200);
    });
});
</script>
@endif
