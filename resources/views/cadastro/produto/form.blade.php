@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="descricao">{{ __('labels.product.description') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $produto->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="codigo">{{ __('labels.product.code') }} <span class="text-danger">*</span></label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="codigo" id="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo', $produto->codigo ?? null) }}">
        @error('codigo')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="quantidadeVolumes">{{ __('labels.product.quantidadeVolumes') }} <span class="text-danger">*</span></label>
        <input type="number" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="quantidadeVolumes" id="quantidadeVolumes" class="form-control @error('quantidadeVolumes') is-invalid @enderror" value="{{ old('quantidadeVolumes', $produto->quantidadeVolumes ?? null) }}" min="0">
        @error('quantidadeVolumes')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="peso">{{ __('labels.product.peso') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="peso" id="peso" class="form-control @error('peso') is-invalid @enderror" value="{{ old('peso', $produto->peso ?? null) }}" min="0">
        @error('peso')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="altura">{{ __('labels.product.altura') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="altura" id="altura" class="form-control @error('altura') is-invalid @enderror" value="{{ old('altura', $produto->altura ?? null) }}" min="0">
        @error('altura')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="largura">{{ __('labels.product.largura') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="largura" id="largura" class="form-control @error('largura') is-invalid @enderror" value="{{ old('largura', $produto->largura ?? null) }}" min="0">
        @error('largura')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="comprimento">{{ __('labels.product.comprimento') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="comprimento" id="comprimento" class="form-control @error('comprimento') is-invalid @enderror" value="{{ old('comprimento', $produto->comprimento ?? null) }}" min="0">
        @error('comprimento')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="precoUnitario">{{ __('labels.product.precoUnitario') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="precoUnitario" id="precoUnitario" class="form-control @error('precoUnitario') is-invalid @enderror" value="{{ old('precoUnitario', $produto->precoUnitario ?? null) }}" min="0">
        @error('precoUnitario')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="descontoProduto">{{ __('labels.product.descontoProduto') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descontoProduto" id="descontoProduto" class="form-control @error('descontoProduto') is-invalid @enderror" value="{{ old('descontoProduto', $produto->descontoProduto ?? null) }}" min="0" max="99.99">
        @error('descontoProduto')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="familia_id">{{ __('labels.product.familia') }} <span class="text-danger">*</span></label>
        <select name="familia_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="familia_id" class="form-control @error('familia_id') is-invalid @enderror">
            @foreach($familias as $familia)
                <option value="{{ $familia->id }}" {{ old('familia_id', $produto->familia_id ?? null) == $familia->id ? 'selected' : '' }}>
                    {{ $familia->descricao }}
                </option>
            @endforeach
        </select>
        @error('familia_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="acabamento_id">{{ __('labels.product.acabamento') }} <span class="text-danger">*</span></label>
        <select name="acabamento_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="acabamento_id" class="form-control @error('acabamento_id') is-invalid @enderror">
            @foreach($acabamentos as $acabamento)
                <option value="{{ $acabamento->id }}" {{ old('acabamento_id', $produto->acabamento_id ?? null) == $acabamento->id ? 'selected' : '' }}>
                    {{ $acabamento->descricao }}
                </option>
            @endforeach
        </select>
        @error('acabamento_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="tela_id">{{ __('labels.product.tela') }} <span class="text-danger">*</span></label>
        <select name="tela_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="tela_id" class="form-control @error('tela_id') is-invalid @enderror">
            @foreach($telas as $tela)
                <option value="{{ $tela->id }}" {{ old('tela_id', $produto->tela_id ?? null) == $tela->id ? 'selected' : '' }}>
                    {{ $tela->descricao }}
                </option>
            @endforeach
        </select>
        @error('tela_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="produtoBase">{{ __('labels.product.history.fields.produtoBase') }} <span class="text-danger">*</span></label>
        <select name="produtoBase" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="produtoBase" class="form-control @error('produtoBase') is-invalid @enderror">
            <option value="1" {{ old('produtoBase', $produto->produtoBase ?? null) == 1 ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ old('produtoBase', $produto->produtoBase ?? null) == 0 ? 'selected' : '' }}>Não</option>
        </select>
        @error('produtoBase')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="produtoBase_id">{{ __('labels.product.history.fields.produtoBase_id') }}</label>
        <select name="produtoBase_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="produtoBase_id" class="form-control @error('produtoBase_id') is-invalid @enderror">
            <option value="">Selecione...</option>
            @foreach($produtosBase as $produtoBase)
                <option value="{{ $produtoBase->id }}" {{ old('produtoBase_id', $produto->produtoBase_id ?? null) == $produtoBase->id ? 'selected' : '' }}>
                    {{ $produtoBase->descricao }}
                </option>
            @endforeach
        </select>
        @error('produtoBase_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="permitirVenda">{{ __('labels.product.history.fields.permitirVenda') }} <span class="text-danger">*</span></label>
        <select name="permitirVenda" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="permitirVenda" class="form-control @error('permitirVenda') is-invalid @enderror">
            <option value="1" {{ old('permitirVenda', $produto->permitirVenda ?? null) == 1 ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ old('permitirVenda', $produto->permitirVenda ?? null) == 0 ? 'selected' : '' }}>Não</option>
        </select>
        @error('permitirVenda')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="permitirTela">{{ __('labels.product.history.fields.permitirTela') }} <span class="text-danger">*</span></label>
        <select name="permitirTela" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="permitirTela" class="form-control @error('permitirTela') is-invalid @enderror">
            <option value="1" {{ old('permitirTela', $produto->permitirTela ?? null) == 1 ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ old('permitirTela', $produto->permitirTela ?? null) == 0 ? 'selected' : '' }}>Não</option>
        </select>
        @error('permitirTela')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label for="especificacao">{{ __('labels.product.history.fields.especificacao') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="especificacao" id="especificacao" class="form-control @error('especificacao') is-invalid @enderror" rows="3">{{ old('especificacao', $produto->especificacao ?? null) }}</textarea>
        @error('especificacao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="observacao">{{ __('labels.product.history.fields.observacao') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="observacao" id="observacao" class="form-control @error('observacao') is-invalid @enderror" rows="3">{{ old('observacao', $produto->observacao ?? null) }}</textarea>
        @error('observacao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="codigoBarras">{{ __('labels.product.history.fields.codigoBarras') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="codigoBarras" id="codigoBarras" class="form-control @error('codigoBarras') is-invalid @enderror" value="{{ old('codigoBarras', $produto->codigoBarras ?? null) }}">
        @error('codigoBarras')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="ncm">{{ __('labels.product.history.fields.ncm') }}</label>
        <input type="number" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="ncm" id="ncm" class="form-control @error('ncm') is-invalid @enderror" value="{{ old('ncm', $produto->ncm ?? null) }}" min="0">
        @error('ncm')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="cst">{{ __('labels.product.history.fields.cst') }}</label>
        <input type="number" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="cst" id="cst" class="form-control @error('cst') is-invalid @enderror" value="{{ old('cst', $produto->cst ?? null) }}" min="0">
        @error('cst')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="cest">{{ __('labels.product.history.fields.cest') }}</label>
        <input type="number" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="cest" id="cest" class="form-control @error('cest') is-invalid @enderror" value="{{ old('cest', $produto->cest ?? null) }}" min="0">
        @error('cest')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="origem_id">{{ __('labels.product.history.fields.origem_id') }}</label>
        <select name="origem_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="origem_id" class="form-control @error('origem_id') is-invalid @enderror">
            <option value="">Selecione...</option>
            @foreach($origens as $origem)
                <option value="{{ $origem->id }}" {{ old('origem_id', $produto->origem_id ?? null) == $origem->id ? 'selected' : '' }}>
                    {{ $origem->descricao }}
                </option>
            @endforeach
        </select>
        @error('origem_id')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
