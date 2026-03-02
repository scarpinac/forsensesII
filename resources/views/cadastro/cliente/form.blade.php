@csrf
<div class="row">
    <!-- Tipo de Cliente (Primeiro campo) -->
    <div class="form-group col-md-6 required">
        <label for="tipoCliente_id">{{ __('labels.customer.type') }} <span class="text-danger">*</span></label>
        <select name="tipoCliente_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="tipoCliente_id" class="form-control @error('tipoCliente_id') is-invalid @enderror" required>
            <option value="">{{ __('labels.customer.select') }}</option>
            @foreach(\App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::TiposCliente)->get() as $tipoCliente)
                <option value="{{ $tipoCliente->id }}" {{ old('tipoCliente_id', $cliente->tipoCliente_id ?? null) == $tipoCliente->id ? 'selected' : '' }}>
                    {{ $tipoCliente->descricao }}
                </option>
            @endforeach
        </select>
        @error('tipoCliente_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Nome (Sempre visível) -->
    <div class="form-group col-md-6">
        <label for="nome">{{ __('labels.customer.name') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome', $cliente->nome ?? null) }}" required>
        @error('nome')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<!-- Campos Pessoa Física -->
<div id="campos_pf" class="row campos-tipo" style="display: none;">
    <div class="form-group col-md-6">
        <label for="nomePreferencia">{{ __('labels.customer.preferred_name') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="nomePreferencia" id="nomePreferencia" class="form-control @error('nomePreferencia') is-invalid @enderror" value="{{ old('nomePreferencia', $cliente->nomePreferencia ?? null) }}">
        @error('nomePreferencia')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="dataNascimento">{{ __('labels.customer.birth_date') }}</label>
        <input type="date" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="dataNascimento" id="dataNascimento" class="form-control @error('dataNascimento') is-invalid @enderror" value="{{ old('dataNascimento', $cliente->dataNascimento ? $cliente->dataNascimento->format('Y-m-d') : null) }}">
        @error('dataNascimento')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="cpf">{{ __('labels.customer.cpf') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="cpf" id="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf', $cliente->cpf ?? null) }}">
        @error('cpf')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-3">
        <label for="rg">{{ __('labels.customer.rg') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="rg" id="rg" class="form-control @error('rg') is-invalid @enderror" value="{{ old('rg', $cliente->rg ?? null) }}">
        @error('rg')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-3">
        <label for="rgOrgaoEmissor">{{ __('labels.customer.rg_issuer') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="rgOrgaoEmissor" id="rgOrgaoEmissor" class="form-control @error('rgOrgaoEmissor') is-invalid @enderror" value="{{ old('rgOrgaoEmissor', $cliente->rgOrgaoEmissor ?? null) }}">
        @error('rgOrgaoEmissor')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<!-- Campos Pessoa Jurídica -->
<div id="campos_pj" class="row campos-tipo" style="display: none;">
    <div class="form-group col-md-6">
        <label for="nomeFantasia">{{ __('labels.customer.trade_name') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="nomeFantasia" id="nomeFantasia" class="form-control @error('nomeFantasia') is-invalid @enderror" value="{{ old('nomeFantasia', $cliente->nomeFantasia ?? null) }}">
        @error('nomeFantasia')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="cnpj">{{ __('labels.customer.cnpj') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="cnpj" id="cnpj" class="form-control @error('cnpj') is-invalid @enderror" value="{{ old('cnpj', $cliente->cnpj ?? null) }}">
        @error('cnpj')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="inscricaoEstadual">{{ __('labels.customer.state_registration') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="inscricaoEstadual" id="inscricaoEstadual" class="form-control @error('inscricaoEstadual') is-invalid @enderror" value="{{ old('inscricaoEstadual', $cliente->inscricaoEstadual ?? null) }}">
        @error('inscricaoEstadual')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="inscricaoMunicipal">{{ __('labels.customer.city_registration') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="inscricaoMunicipal" id="inscricaoMunicipal" class="form-control @error('inscricaoMunicipal') is-invalid @enderror" value="{{ old('inscricaoMunicipal', $cliente->inscricaoMunicipal ?? null) }}">
        @error('inscricaoMunicipal')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="optanteSimples_id">{{ __('labels.customer.simple_option') }}</label>
        <select name="optanteSimples_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="optanteSimples_id" class="form-control @error('optanteSimples_id') is-invalid @enderror">
            <option value="">{{ __('labels.customer.select') }}</option>
            @foreach(\App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::OpcoesSimNao)->get() as $optanteSimples)
                <option value="{{ $optanteSimples->id }}" {{ old('optanteSimples_id', $cliente->optanteSimples_id ?? null) == $optanteSimples->id ? 'selected' : '' }}>
                    {{ $optanteSimples->descricao }}
                </option>
            @endforeach
        </select>
        @error('optanteSimples_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<!-- Responsável (Apenas PJ) -->
<div id="campos_responsavel" class="row campos-tipo" style="display: none;">
    <div class="col-12">
        <h5 class="mb-3">{{ __('labels.customer.responsible') }}</h5>
    </div>

    <div class="form-group col-md-6">
        <label for="responsavelNome">{{ __('labels.customer.responsible_name') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="responsavelNome" id="responsavelNome" class="form-control @error('responsavelNome') is-invalid @enderror" value="{{ old('responsavelNome', $cliente->responsavelNome ?? null) }}">
        @error('responsavelNome')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-3">
        <label for="responsavelCpf">{{ __('labels.customer.responsible_cpf') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="responsavelCpf" id="responsavelCpf" class="form-control @error('responsavelCpf') is-invalid @enderror" value="{{ old('responsavelCpf', $cliente->responsavelCpf ?? null) }}">
        @error('responsavelCpf')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-2">
        <label for="responsavelRg">{{ __('labels.customer.responsible_rg') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="responsavelRg" id="responsavelRg" class="form-control @error('responsavelRg') is-invalid @enderror" value="{{ old('responsavelRg', $cliente->responsavelRg ?? null) }}">
        @error('responsavelRg')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-1">
        <label for="responsavelRgOrgaoEmissor">{{ __('labels.customer.responsible_rg_issuer') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="responsavelRgOrgaoEmissor" id="responsavelRgOrgaoEmissor" class="form-control @error('responsavelRgOrgaoEmissor') is-invalid @enderror" value="{{ old('responsavelRgOrgaoEmissor', $cliente->responsavelRgOrgaoEmissor ?? null) }}">
        @error('responsavelRgOrgaoEmissor')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<!-- Campos Adicionais (Sempre visíveis) -->
<div class="row">
    <div class="form-group col-md-6">
        <label for="origem_id">{{ __('labels.customer.origin') }}</label>
        <select name="origem_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="origem_id" class="form-control @error('origem_id') is-invalid @enderror">
            <option value="">{{ __('labels.customer.select') }}</option>
            @foreach(\App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::OrigensCliente)->get() as $origem)
                <option value="{{ $origem->id }}" {{ old('origem_id', $cliente->origem_id ?? null) == $origem->id ? 'selected' : '' }}>
                    {{ $origem->descricao }}
                </option>
            @endforeach
        </select>
        @error('origem_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="situacao_id">{{ __('labels.customer.situation') }} <span class="text-danger">*</span></label>
        <select name="situacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror" required>
            @foreach(\App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::Situacoes)->get() as $situacao)
                <option value="{{ $situacao->id }}" {{ old('situacao_id', $cliente->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
                    {{ $situacao->descricao }}
                </option>
            @endforeach
        </select>
        @error('situacao_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<!-- Campos Financeiros -->
<div class="row">
    <div class="form-group col-md-4">
        <label for="data_cadastro">{{ __('labels.customer.registration_date') }}</label>
        <input type="date" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="data_cadastro" id="data_cadastro" class="form-control @error('data_cadastro') is-invalid @enderror" value="{{ old('data_cadastro', $cliente->data_cadastro ? $cliente->data_cadastro->format('Y-m-d') : null) }}">
        @error('data_cadastro')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-4">
        <label for="limite_credito">{{ __('labels.customer.credit_limit') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="limite_credito" id="limite_credito" class="form-control @error('limite_credito') is-invalid @enderror" value="{{ old('limite_credito', $cliente->limite_credito ? number_format($cliente->limite_credito, 2, ',', '.') : null) }}">
        @error('limite_credito')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-4">
        <label for="data_limite_credite">{{ __('labels.customer.credit_limit_date') }}</label>
        <input type="date" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="data_limite_credite" id="data_limite_credite" class="form-control @error('data_limite_credite') is-invalid @enderror" value="{{ old('data_limite_credite', $cliente->data_limite_credite ? $cliente->data_limite_credite->format('Y-m-d') : null) }}">
        @error('data_limite_credite')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<!-- Observações -->
<div class="row">
    <div class="form-group col-12">
        <label for="observacoes">{{ __('labels.customer.observations') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="observacoes" id="observacoes" class="form-control @error('observacoes') is-invalid @enderror" rows="3">{{ old('observacoes', $cliente->observacoes ?? null) }}</textarea>
        @error('observacoes')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<!-- Seção de Endereços -->
<div class="row">
    <div class="col-12">
        <h5 class="mb-3">{{ __('labels.customer.addresses') }}</h5>
        <div id="enderecos-container">
            <!-- Endereços serão adicionados dinamicamente aqui -->
        </div>
        @if(!isset($bloquearCampos) || !$bloquearCampos)
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-endereco">
                <i class="fas fa-plus"></i> {{ __('labels.customer.add_address') }}
            </button>
        @endif
    </div>
</div>

<!-- Seção de Contatos -->
<div class="row mt-4">
    <div class="col-12">
        <h5 class="mb-3">{{ __('labels.customer.contacts') }}</h5>
        <div id="contatos-container">
            <!-- Contatos serão adicionados dinamicamente aqui -->
        </div>
        @if(!isset($bloquearCampos) || !$bloquearCampos)
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-contato">
                <i class="fas fa-plus"></i> {{ __('labels.customer.add_contact') }}
            </button>
        @endif
    </div>
</div>

<!-- Templates ocultos para adicionar dinamicamente -->
<template id="template-endereco">
    <div class="endereco-item border p-3 mb-3">
        <div class="row">
            <div class="col-md-2">
                <label>{{ __('labels.customer.address_type') }}</label>
                <select name="enderecos[INDEX][tipoEndereco_id]" class="form-control tipo-endereco">
                    <option value="">{{ __('labels.customer.select') }}</option>
                    @foreach(\App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::TiposEndereco)->get() as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->descricao }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>{{ __('labels.customer.zip_code') }}</label>
                <input type="text" name="enderecos[INDEX][cep]" class="form-control cep" placeholder="{{ __('labels.customer.zip_code_placeholder') }}">
            </div>
            <div class="col-md-4">
                <label>{{ __('labels.customer.street') }}</label>
                <input type="text" name="enderecos[INDEX][logradouro]" class="form-control logradouro">
            </div>
            <div class="col-md-1">
                <label>{{ __('labels.customer.number') }}</label>
                <input type="text" name="enderecos[INDEX][numero]" class="form-control numero">
            </div>
            <div class="col-md-2">
                <label>{{ __('labels.customer.complement') }}</label>
                <input type="text" name="enderecos[INDEX][complemento]" class="form-control complemento">
            </div>
            <div class="col-md-1">
                <label>&nbsp;</label><br>
                <button type="button" class="btn btn-sm btn-danger btn-remove-endereco">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3">
                <label>{{ __('labels.customer.neighborhood') }}</label>
                <input type="text" name="enderecos[INDEX][bairro]" class="form-control bairro">
            </div>
            <div class="col-md-4">
                <label>{{ __('labels.customer.city') }}</label>
                <input type="text" name="enderecos[INDEX][cidade]" class="form-control cidade">
            </div>
            <div class="col-md-2">
                <label>{{ __('labels.customer.state') }}</label>
                <input type="text" name="enderecos[INDEX][estado]" class="form-control estado" maxlength="2">
            </div>
            <div class="col-md-3">
                <label>{{ __('labels.customer.country') }}</label>
                <input type="text" name="enderecos[INDEX][pais]" class="form-control pais" value="Brasil">
            </div>
        </div>
    </div>
</template>

<template id="template-contato">
    <div class="contato-item border p-3 mb-3">
        <div class="row">
            <div class="col-md-3">
                <label>{{ __('labels.customer.contact_type') }}</label>
                <select name="contatos[INDEX][tipoContato_id]" class="form-control tipo-contato">
                    <option value="">{{ __('labels.customer.select') }}</option>
                    @foreach(\App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::TiposContato)->get() as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->descricao }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>{{ __('labels.customer.phone') }}</label>
                <input type="text" name="contatos[INDEX][telefone]" class="form-control telefone" placeholder="{{ __('labels.customer.phone_placeholder') }}">
            </div>
            <div class="col-md-3">
                <label>{{ __('labels.customer.contact_value') }}</label>
                <input type="text" name="contatos[INDEX][contato]" class="form-control contato">
            </div>
            <div class="col-md-2">
                <label>{{ __('labels.customer.email') }}</label>
                <input type="email" name="contatos[INDEX][email]" class="form-control email">
            </div>
            <div class="col-md-1">
                <label>&nbsp;</label><br>
                <button type="button" class="btn btn-sm btn-danger btn-remove-contato">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-11">
                <label>{{ __('labels.customer.contact_observations') }}</label>
                <input type="text" name="contatos[INDEX][observacoes]" class="form-control observacoes" placeholder="{{ __('labels.customer.contact_observations_placeholder') }}">
            </div>
        </div>
    </div>
</template>

