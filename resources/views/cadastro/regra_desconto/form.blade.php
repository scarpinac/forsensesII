@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="descricao">{{ __('labels.discount_rule.description') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $regraDesconto->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="periodo">{{ __('labels.discount_rule.period') }} <span class="text-danger">*</span></label>
        <input type="number" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="periodo" id="periodo" class="form-control @error('periodo') is-invalid @enderror" value="{{ old('periodo', $regraDesconto->periodo ?? null) }}" min="0">
        @error('periodo')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label for="valorBase">{{ __('labels.discount_rule.valorBase') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="valorBase" id="valorBase" class="form-control @error('valorBase') is-invalid @enderror" value="{{ old('valorBase', $regraDesconto->valorBase ?? null) }}" min="0">
        @error('valorBase')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="descontoAcrescido">{{ __('labels.discount_rule.descontoAcrescido') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descontoAcrescido" id="descontoAcrescido" class="form-control @error('descontoAcrescido') is-invalid @enderror" value="{{ old('descontoAcrescido', $regraDesconto->descontoAcrescido ?? null) }}" min="0" max="99.99">
        @error('descontoAcrescido')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
