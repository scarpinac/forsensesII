@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="valor">{{ __('labels.commission.value') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor', $comissao->valor ?? null) }}">
        @error('valor')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="tipoComissao_id">{{ __('labels.commission.form.situation') }} <span class="text-danger">*</span></label>
        <select name="tipoComissao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="tipoComissao_id" class="form-control @error('tipoComissao_id') is-invalid @enderror">
            @foreach(\App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::TiposComissao)->get() as $tipoComissao)
                <option value="{{ $tipoComissao->id }}" {{ old('tipoComissao_id', $comissao->tipoComissao_id ?? null) == $tipoComissao->id ? 'selected' : '' }}>
                    {{ $tipoComissao->descricao }}
                </option>
            @endforeach
        </select>
        @error('tipoComissao_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

