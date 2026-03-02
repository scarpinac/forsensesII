@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="nomeFantasia">{{ __('labels.transportadora.form.nomeFantasia') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="nomeFantasia" id="nomeFantasia" class="form-control @error('nomeFantasia') is-invalid @enderror" value="{{ old('nomeFantasia', $transportadora->nomeFantasia ?? null) }}" required>
        @error('nomeFantasia')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="razaoSocial">{{ __('labels.transportadora.form.razaoSocial') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="razaoSocial" id="razaoSocial" class="form-control @error('razaoSocial') is-invalid @enderror" value="{{ old('razaoSocial', $transportadora->razaoSocial ?? null) }}" required>
        @error('razaoSocial')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="form-group col-md-4">
        <label for="cnpj">{{ __('labels.transportadora.form.cnpj') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="cnpj" id="cnpj" class="form-control @error('cnpj') is-invalid @enderror" value="{{ old('cnpj', $transportadora->cnpj ?? null) }}" required>
        @error('cnpj')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group col-md-4">
        <label for="situacao_id">{{ __('labels.transportadora.form.situacao') }} <span class="text-danger">*</span></label>
        <select name="situacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror" required>
            <option value="">{{ __('labels.transportadora.select') }}</option>
            @foreach($situacoes ?? \App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::Situacoes)->get() as $situacao)
                <option value="{{ $situacao->id }}" {{ old('situacao_id', $transportadora->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
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

<div class="row">
    <div class="form-group col-12">
        <label for="observacao">{{ __('labels.transportadora.form.observacao') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="observacao" id="observacao" class="form-control @error('observacao') is-invalid @enderror" rows="3">{{ old('observacao', $transportadora->observacao ?? null) }}</textarea>
        @error('observacao')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h5 class="mb-3">{{ __('labels.transportadora.form.estadosAtendidos') }}</h5>
        @php
            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
            $estadosSelecionados = old('estadosAtendidos', explode(',', $transportadora->estadosAtendidos ?? ''));
            $grupos = array_chunk($estados, 9);
        @endphp

        @foreach($grupos as $grupo)
            <div class="row mb-2">
                @foreach($grupo as $estado)
                    <div class="col-md-1">
                        <div class="form-check">
                            <input type="checkbox" name="estadosAtendidos[]" value="{{ $estado }}" id="estado_{{ $estado }}" class="form-check-input" {{ in_array($estado, $estadosSelecionados) ? 'checked' : '' }} {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}}>
                            <label for="estado_{{ $estado }}" class="form-check-label">{{ $estado }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        @error('estadosAtendidos')
        <div class="invalid-feedback d-block font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
