@csrf
<div class="row">
    <div class="form-group col-md-4">
        <label for="codigo">{{ __('labels.product_origin.code') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="codigo" id="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo', $origemProduto->codigo ?? null) }}" maxlength="3">
        @error('codigo')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="descricao">{{ __('labels.product_origin.description') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $origemProduto->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="situacao_id">{{ __('labels.product_origin.situacao') }} <span class="text-danger">*</span></label>
        <select name="situacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror">
            @foreach($situacoes as $situacao)
                <option value="{{ $situacao->id }}" {{ old('situacao_id', $origemProduto->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
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
