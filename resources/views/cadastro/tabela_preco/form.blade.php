@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="descricao">{{ __('labels.price_table.description') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $tabelaPreco->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="vigenciaAte">{{ __('labels.price_table.vigenciaAte') }}</label>
        <input type="datetime-local" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="vigenciaAte" id="vigenciaAte" class="form-control @error('vigenciaAte') is-invalid @enderror" value="{{ old('vigenciaAte', $tabelaPreco->vigenciaAte ? $tabelaPreco->vigenciaAte->format('Y-m-d\TH:i') : null) }}">
        @error('vigenciaAte')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="situacao_id">{{ __('labels.price_table.situacao') }} <span class="text-danger">*</span></label>
        <select name="situacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror">
            @foreach($situacoes as $situacao)
                <option value="{{ $situacao->id }}" {{ old('situacao_id', $tabelaPreco->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
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
