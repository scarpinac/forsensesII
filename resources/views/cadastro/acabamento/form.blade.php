@csrf
<div class="row">
    <div class="form-group col-md-4">
        <label for="descricao">{{ __('labels.finish.description') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $acabamento->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="tipoAcabamento_id">{{ __('labels.finish.type') }} <span class="text-danger">*</span></label>
        <select name="tipoAcabamento_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="tipoAcabamento_id" class="form-control @error('tipoAcabamento_id') is-invalid @enderror">
            @foreach($tiposAcabamento as $tipoAcabamento)
                <option value="{{ $tipoAcabamento->id }}" {{ old('tipoAcabamento_id', $acabamento->tipoAcabamento_id ?? null) == $tipoAcabamento->id ? 'selected' : '' }}>
                    {{ $tipoAcabamento->descricao }}
                </option>
            @endforeach
        </select>
        @error('tipoAcabamento_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="cor_id">{{ __('labels.finish.color') }} <span class="text-danger">*</span></label>
        <select name="cor_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="cor_id" class="form-control @error('cor_id') is-invalid @enderror">
            @foreach($cores as $cor)
                <option value="{{ $cor->id }}" {{ old('cor_id', $acabamento->cor_id ?? null) == $cor->id ? 'selected' : '' }}>
                    {{ $cor->descricao }} ({{ $cor->corHexadecimal }})
                </option>
            @endforeach
        </select>
        @error('cor_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
