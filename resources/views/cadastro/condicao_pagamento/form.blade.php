@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="descricao">{{ __('labels.payment_condition.description') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $condicaoPagamento->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="revenda_id">{{ __('labels.payment_condition.revenda') }}</label>
        <select name="revenda_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="revenda_id" class="form-control @error('revenda_id') is-invalid @enderror">
            <option value="">{{ __('labels.payment_condition.form.select_revenda') }}</option>
            @foreach($revendas as $revenda)
                <option value="{{ $revenda->id }}" {{ old('revenda_id', $condicaoPagamento->revenda_id ?? null) == $revenda->id ? 'selected' : '' }}>
                    {{ $revenda->nomeFantasia }}
                </option>
            @endforeach
        </select>
        @error('revenda_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="diasEntreParcelas">{{ __('labels.payment_condition.days_between_installments') }} <span class="text-danger">*</span></label>
        <input type="number" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="diasEntreParcelas" id="diasEntreParcelas" class="form-control @error('diasEntreParcelas') is-invalid @enderror" value="{{ old('diasEntreParcelas', $condicaoPagamento->diasEntreParcelas ?? null) }}" min="0">
        @error('diasEntreParcelas')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="quantidadeParcelas">{{ __('labels.payment_condition.installments_quantity') }} <span class="text-danger">*</span></label>
        <input type="number" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="quantidadeParcelas" id="quantidadeParcelas" class="form-control @error('quantidadeParcelas') is-invalid @enderror" value="{{ old('quantidadeParcelas', $condicaoPagamento->quantidadeParcelas ?? null) }}" min="1">
        @error('quantidadeParcelas')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="situacao_id">{{ __('labels.payment_condition.situation') }} <span class="text-danger">*</span></label>
        <select name="situacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror">
            @foreach($situacoes as $situacao)
                <option value="{{ $situacao->id }}" {{ old('situacao_id', $condicaoPagamento->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
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
