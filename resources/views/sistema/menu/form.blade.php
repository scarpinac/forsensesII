@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="descricao">{{ __('labels.menu.description') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $menu->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="icone">{{ __('labels.menu.form.icon') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="icone" id="icone" class="form-control @error('icone') is-invalid @enderror" value="{{ old('icone', $menu->icone ?? null) }}">
        @error('icone')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label for="rota">{{ __('labels.menu.form.route') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="rota" id="rota" class="form-control @error('rota') is-invalid @enderror" value="{{ old('rota', $menu->rota ?? null) }}">
        @error('rota')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="menuPai_id">{{ __('labels.menu.form.parent_menu') }}</label>
        <select name="menuPai_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="menuPai_id" class="form-control @error('menuPai_id') is-invalid @enderror">
            <option value="">{{ __('labels.menu.form.none') }}</option>
            @foreach($menus as $parentMenu)
                <option value="{{ $parentMenu->id }}" {{ old('menuPai_id', $menu->menuPai_id ?? null) == $parentMenu->id ? 'selected' : '' }}>
                    {{ $parentMenu->descricao }}
                </option>
            @endforeach
        </select>
        @error('menuPai_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label for="permissao_id">{{ __('labels.menu.form.permission') }}</label>
        <select name="permissao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="permissao_id" class="form-control @error('permissao_id') is-invalid @enderror {{isset($bloquearCampos) && $bloquearCampos ? '' : 'select2'}}" >
            <option value="">{{ __('labels.menu.form.select_permission') }}</option>
            @foreach($permissoes as $permissao)
                <option value="{{ $permissao->id }}" {{ old('permissao_id', $menu->permissao_id ?? null) == $permissao->id ? 'selected' : '' }}>
                    {{ $permissao->descricao }}
                </option>
            @endforeach
        </select>
        @error('permissao_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="situacao_id">{{ __('labels.menu.form.situation') }}</label>
        <select name="situacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="situacao_id" class="form-control @error('situacao_id') is-invalid @enderror">
             <option value="">{{ __('labels.menu.form.select_situation') }}</option>
            @foreach($situacoes as $situacao)
                <option value="{{ $situacao->id }}" {{ old('situacao_id', $menu->situacao_id ?? null) == $situacao->id ? 'selected' : '' }}>
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
