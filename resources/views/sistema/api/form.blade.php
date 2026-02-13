@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="api_id">{{ __('labels.api.form.api_type') }}</label>
        <select name="api_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="api_id" class="form-control @error('api_id') is-invalid @enderror {{isset($bloquearCampos) && $bloquearCampos ? '' : 'select2'}}" >
            <option value="">{{ __('labels.api.form.select_api_type') }}</option>
            @foreach($apiTypes ?? [] as $apiType)
                <option value="{{ $apiType->id }}" {{ old('api_id', $api->api_id ?? null) == $apiType->id ? 'selected' : '' }}>
                    {{ $apiType->descricao }}
                </option>
            @endforeach
        </select>
        @error('api_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="situacao_id">{{ __('labels.api.form.situation') }}</label>
        <select name="situacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror">
             <option value="">{{ __('labels.api.form.select_situation') }}</option>
            @foreach($situacoes ?? [] as $situacao)
                <option value="{{ $situacao->id }}" {{ old('situacao_id', $api->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
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
    <div class="form-group col-md-12">
        <label for="credencial">{{ __('labels.api.form.credential') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="credencial" id="credencial" class="form-control @error('credencial') is-invalid @enderror" rows="5">{{ old('credencial', $api->credencial ?? null) }}</textarea>
        @error('credencial')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
