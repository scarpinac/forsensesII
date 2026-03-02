@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="descricao">{{ __('labels.color.description') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $cor->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="corHexadecimal">{{ __('labels.color.hexadecimal') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="corHexadecimal" id="corHexadecimal" class="form-control @error('corHexadecimal') is-invalid @enderror" value="{{ old('corHexadecimal', $cor->corHexadecimal ?? null) }}" placeholder="#000000">
        @error('corHexadecimal')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
