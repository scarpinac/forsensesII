@csrf

<!-- Seção: Informações Básicas -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.revenda.sections.basic_info') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="tipoRevenda_id">{{ __('labels.revenda.fields.tipoRevenda_id') }} <span class="text-danger">*</span></label>
                <select name="tipoRevenda_id" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} id="tipoRevenda_id" class="form-control @error('tipoRevenda_id') is-invalid @enderror">
                    @foreach($tiposRevenda as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipoRevenda_id', $revenda->tipoRevenda_id ?? null) == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->descricao }}
                        </option>
                    @endforeach
                </select>
                @error('tipoRevenda_id')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="nomeFantasia">{{ __('labels.revenda.fields.nomeFantasia') }} <span class="text-danger">*</span></label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="nomeFantasia" id="nomeFantasia" class="form-control @error('nomeFantasia') is-invalid @enderror" value="{{ old('nomeFantasia', $revenda->nomeFantasia ?? null) }}" maxlength="80">
                @error('nomeFantasia')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="razaoSocial">{{ __('labels.revenda.fields.razaoSocial') }} <span class="text-danger">*</span></label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="razaoSocial" id="razaoSocial" class="form-control @error('razaoSocial') is-invalid @enderror" value="{{ old('razaoSocial', $revenda->razaoSocial ?? null) }}" maxlength="80">
                @error('razaoSocial')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="matrizRevenda_id">{{ __('labels.revenda.fields.matrizRevenda_id') }}</label>
                <select name="matrizRevenda_id" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} id="matrizRevenda_id" class="form-control @error('matrizRevenda_id') is-invalid @enderror">
                    <option value="">Selecione </option>
                    @foreach($matrizesRevenda as $matriz)
                        <option value="{{ $matriz->id }}" {{ old('matrizRevenda_id', $revenda->matrizRevenda_id ?? null) == $matriz->id ? 'selected' : '' }}>
                            {{ $matriz->nomeFantasia }}
                        </option>
                    @endforeach
                </select>
                <small>Caso necessário</small>
                @error('matrizRevenda_id')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="dataCriacao">{{ __('labels.revenda.fields.dataCriacao') }} <span class="text-danger">*</span></label>
                <input type="datetime-local" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="dataCriacao" id="dataCriacao" class="form-control @error('dataCriacao') is-invalid @enderror" value="{{ old('dataCriacao', $revenda->dataCriacao?->format('Y-m-d\TH:i') ?? null) }}">
                @error('dataCriacao')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="cnpj">{{ __('labels.revenda.fields.cnpj') }} <span class="text-danger">*</span></label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="cnpj" id="cnpj" class="form-control @error('cnpj') is-invalid @enderror" value="{{ old('cnpj', $revenda->cnpj ?? null) }}">
                @error('cnpj')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="inscricaoEstadual">{{ __('labels.revenda.fields.inscricaoEstadual') }} <span class="text-danger">*</span></label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="inscricaoEstadual" id="inscricaoEstadual" class="form-control @error('inscricaoEstadual') is-invalid @enderror" value="{{ old('inscricaoEstadual', $revenda->inscricaoEstadual ?? null) }}" maxlength="20">
                @error('inscricaoEstadual')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="inscricaoMunicipal">{{ __('labels.revenda.fields.inscricaoMunicipal') }} <span class="text-danger">*</span></label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="inscricaoMunicipal" id="inscricaoMunicipal" class="form-control @error('inscricaoMunicipal') is-invalid @enderror" value="{{ old('inscricaoMunicipal', $revenda->inscricaoMunicipal ?? null) }}" maxlength="20">
                @error('inscricaoMunicipal')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="optanteSimples_id">{{ __('labels.revenda.fields.optanteSimples_id') }} <span class="text-danger">*</span></label>
                <select name="optanteSimples_id" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} id="optanteSimples_id" class="form-control @error('optanteSimples_id') is-invalid @enderror">
                    @foreach($optantesSimples as $optante)
                        <option value="{{ $optante->id }}" {{ old('optanteSimples_id', $revenda->optanteSimples_id ?? null) == $optante->id ? 'selected' : '' }}>
                            {{ $optante->descricao }}
                        </option>
                    @endforeach
                </select>
                @error('optanteSimples_id')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="situacao_id">{{ __('labels.revenda.fields.situacao_id') }} <span class="text-danger">*</span></label>
                <select name="situacao_id" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror">
                    @foreach($situacoes as $situacao)
                        <option value="{{ $situacao->id }}" {{ old('situacao_id', $revenda->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
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
    </div>
</div>

<!-- Seção: Responsável -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.revenda.sections.responsible') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="responsavelNome">{{ __('labels.revenda.fields.responsavelNome') }}</label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="responsavelNome" id="responsavelNome" class="form-control @error('responsavelNome') is-invalid @enderror" value="{{ old('responsavelNome', $revenda->responsavelNome ?? null) }}" maxlength="50">
                @error('responsavelNome')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="responsavelCpf">{{ __('labels.revenda.fields.responsavelCpf') }}</label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="responsavelCpf" id="responsavelCpf" class="form-control @error('responsavelCpf') is-invalid @enderror" value="{{ old('responsavelCpf', $revenda->responsavelCpf ?? null) }}">
                @error('responsavelCpf')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="responsavelRg">{{ __('labels.revenda.fields.responsavelRg') }}</label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="responsavelRg" id="responsavelRg" class="form-control @error('responsavelRg') is-invalid @enderror" value="{{ old('responsavelRg', $revenda->responsavelRg ?? null) }}" maxlength="15">
                @error('responsavelRg')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="responsavelRgOrgaoEmissor">{{ __('labels.revenda.fields.responsavelRgOrgaoEmissor') }}</label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="responsavelRgOrgaoEmissor" id="responsavelRgOrgaoEmissor" class="form-control @error('responsavelRgOrgaoEmissor') is-invalid @enderror" value="{{ old('responsavelRgOrgaoEmissor', $revenda->responsavelRgOrgaoEmissor ?? null) }}" maxlength="15">
                @error('responsavelRgOrgaoEmissor')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Seção: Descontos -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.revenda.sections.discounts') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="nivelDesconto">{{ __('labels.revenda.fields.nivelDesconto') }}</label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="nivelDesconto" id="nivelDesconto" class="form-control valor @error('nivelDesconto') is-invalid @enderror" value="{{ old('nivelDesconto', $revenda->nivelDesconto ?? null) }}">
                @error('nivelDesconto')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="dataUltimaAlteracaoDesconto">{{ __('labels.revenda.fields.dataUltimaAlteracaoDesconto') }}</label>
                <input type="datetime-local" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="dataUltimaAlteracaoDesconto" id="dataUltimaAlteracaoDesconto" class="form-control @error('dataUltimaAlteracaoDesconto') is-invalid @enderror" value="{{ old('dataUltimaAlteracaoDesconto', $revenda->dataUltimaAlteracaoDesconto?->format('Y-m-d\TH:i') ?? null) }}">
                @error('dataUltimaAlteracaoDesconto')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="descontoInicial">{{ __('labels.revenda.fields.descontoInicial') }}</label>
                <input type="text"  {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="descontoInicial" id="descontoInicial" class="form-control valor @error('descontoInicial') is-invalid @enderror" value="{{ old('descontoInicial', $revenda->descontoInicial ?? null) }}">
                @error('descontoInicial')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="descontoMaximo">{{ __('labels.revenda.fields.descontoMaximo') }}</label>
                <input type="text"  {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="descontoMaximo" id="descontoMaximo" class="form-control valor @error('descontoMaximo') is-invalid @enderror" value="{{ old('descontoMaximo', $revenda->descontoMaximo ?? null) }}">
                @error('descontoMaximo')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="descontoAtual">{{ __('labels.revenda.fields.descontoAtual') }}</label>
                <input type="text"  {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="descontoAtual" id="descontoAtual" class="form-control valor @error('descontoAtual') is-invalid @enderror" value="{{ old('descontoAtual', $revenda->descontoAtual ?? null) }}">
                @error('descontoAtual')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="descontoVitalicio" id="descontoVitalicio" class="form-check-input @error('descontoVitalicio') is-invalid @enderror" {{ old('descontoVitalicio', $revenda->descontoVitalicio ?? false) || (isset($bloquearCampos) && $bloquearCampos) ? 'checked' : '' }} {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }}>
                    <label class="form-check-label" for="descontoVitalicio">{{ __('labels.revenda.fields.descontoVitalicio') }} <span class="text-danger">*</span></label>
                </div>
                @error('descontoVitalicio')
                <div class="invalid-feedback font-weight-bold d-block" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Seção: Fornecedores -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.revenda.sections.suppliers') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-4">
                <label for="fornecedoresAudio">{{ __('labels.revenda.fields.fornecedoresAudio') }}</label>
                <textarea {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="fornecedoresAudio" id="fornecedoresAudio" class="form-control @error('fornecedoresAudio') is-invalid @enderror">{{ old('fornecedoresAudio', $revenda->fornecedoresAudio ?? null) }}</textarea>
                @error('fornecedoresAudio')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="fornecedoresVideo">{{ __('labels.revenda.fields.fornecedoresVideo') }}</label>
                <textarea {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="fornecedoresVideo" id="fornecedoresVideo" class="form-control @error('fornecedoresVideo') is-invalid @enderror">{{ old('fornecedoresVideo', $revenda->fornecedoresVideo ?? null) }}</textarea>
                @error('fornecedoresVideo')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="fornecedoresAutomacao">{{ __('labels.revenda.fields.fornecedoresAutomacao') }}</label>
                <textarea {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="fornecedoresAutomacao" id="fornecedoresAutomacao" class="form-control @error('fornecedoresAutomacao') is-invalid @enderror">{{ old('fornecedoresAutomacao', $revenda->fornecedoresAutomacao ?? null) }}</textarea>
                @error('fornecedoresAutomacao')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Seção: Showroom e Exposição -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.revenda.sections.showroom') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="tipoShowroom_id">{{ __('labels.revenda.fields.tipoShowroom_id') }} <span class="text-danger">*</span></label>
                <select name="tipoShowroom_id" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} id="tipoShowroom_id" class="form-control @error('tipoShowroom_id') is-invalid @enderror">
                    @foreach($tiposShowroom as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipoShowroom_id', $revenda->tipoShowroom_id ?? null) == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->descricao }}
                        </option>
                    @endforeach
                </select>
                @error('tipoShowroom_id')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="areaExposicao">{{ __('labels.revenda.fields.areaExposicao') }} <span class="text-danger">*</span></label>
                <input type="text"  {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="areaExposicao" id="areaExposicao" class="form-control valor @error('areaExposicao') is-invalid @enderror" value="{{ old('areaExposicao', $revenda->areaExposicao ?? null) }}">
                @error('areaExposicao')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="temJardim" id="temJardim" class="form-check-input @error('temJardim') is-invalid @enderror" {{ old('temJardim', $revenda->temJardim ?? false) || (isset($bloquearCampos) && $bloquearCampos) ? 'checked' : '' }} {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }}>
                    <label class="form-check-label" for="temJardim">{{ __('labels.revenda.fields.temJardim') }} <span class="text-danger">*</span></label>
                </div>
                @error('temJardim')
                <div class="invalid-feedback font-weight-bold d-block" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Seção: Outros -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.revenda.sections.other') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-4">
                <label for="dataAberturaRevenda">{{ __('labels.revenda.fields.dataAberturaRevenda') }} <span class="text-danger">*</span></label>
                <input type="date" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="dataAberturaRevenda" id="dataAberturaRevenda" class="form-control @error('dataAberturaRevenda') is-invalid @enderror" value="{{ old('dataAberturaRevenda', $revenda->dataAberturaRevenda?->format('Y-m-d') ?? null) }}">
                @error('dataAberturaRevenda')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="ramoAtividade">{{ __('labels.revenda.fields.ramoAtividade') }} <span class="text-danger">*</span></label>
                <input type="text" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="ramoAtividade" id="ramoAtividade" class="form-control @error('ramoAtividade') is-invalid @enderror" value="{{ old('ramoAtividade', $revenda->ramoAtividade ?? null) }}" maxlength="100">
                @error('ramoAtividade')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="tipoRegime_id">{{ __('labels.revenda.fields.tipoRegime_id') }} <span class="text-danger">*</span></label>
                <select name="tipoRegime_id" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} id="tipoRegime_id" class="form-control @error('tipoRegime_id') is-invalid @enderror">
                    @foreach($tiposRegime as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipoRegime_id', $revenda->tipoRegime_id ?? null) == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->descricao }}
                        </option>
                    @endforeach
                </select>
                @error('tipoRegime_id')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="dataAprovacaoCadastro">{{ __('labels.revenda.fields.dataAprovacaoCadastro') }} <span class="text-danger">*</span></label>
                <input type="datetime-local" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="dataAprovacaoCadastro" id="dataAprovacaoCadastro" class="form-control @error('dataAprovacaoCadastro') is-invalid @enderror" value="{{ old('dataAprovacaoCadastro', $revenda->dataAprovacaoCadastro?->format('Y-m-d\TH:i') ?? null) }}">
                @error('dataAprovacaoCadastro')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="origemCadastro_id">{{ __('labels.revenda.fields.origemCadastro_id') }} <span class="text-danger">*</span></label>
                <select name="origemCadastro_id" {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} id="origemCadastro_id" class="form-control @error('origemCadastro_id') is-invalid @enderror">
                    @foreach($origensCadastro as $origem)
                        <option value="{{ $origem->id }}" {{ old('origemCadastro_id', $revenda->origemCadastro_id ?? null) == $origem->id ? 'selected' : '' }}>
                            {{ $origem->descricao }}
                        </option>
                    @endforeach
                </select>
                @error('origemCadastro_id')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label for="observacao">{{ __('labels.revenda.fields.observacao') }}</label>
                <textarea {{ isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '' }} name="observacao" id="observacao" class="form-control @error('observacao') is-invalid @enderror">{{ old('observacao', $revenda->observacao ?? null) }}</textarea>
                @error('observacao')
                <div class="invalid-feedback font-weight-bold" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
</div>
