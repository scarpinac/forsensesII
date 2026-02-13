@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="nome">{{ __('labels.parametro.form.name') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome', $parametro->nome ?? null) }}">
        @error('nome')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="tipo_id">{{ __('labels.parametro.form.type') }}</label>
        <select name="tipo_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="tipo_id" class="form-control @error('tipo_id') is-invalid @enderror {{isset($bloquearCampos) && $bloquearCampos ? '' : 'select2'}}" >
            <option value="">{{ __('labels.parametro.form.select_type') }}</option>
            @foreach($tipos ?? [] as $tipo)
                <option value="{{ $tipo->id }}" {{ old('tipo_id', $parametro->tipo_id ?? null) == $tipo->id ? 'selected' : '' }}>
                    {{ $tipo->descricao }}
                </option>
            @endforeach
        </select>
        @error('tipo_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="descricao">{{ __('labels.parametro.form.description') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" rows="3">{{ old('descricao', $parametro->descricao ?? null) }}</textarea>
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="valor">{{ __('labels.parametro.form.value') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" rows="5">{{ old('valor', $parametro->valor ?? null) }}</textarea>
        @error('valor')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
