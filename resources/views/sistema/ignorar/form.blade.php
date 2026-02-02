@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="titulo">{{ __('labels.notification.titulo') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $notificacao->titulo ?? null) }}" maxlength="50">
        @error('titulo')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="tipoNotificacao_id">{{ __('labels.notification.tipo') }}</label>
        <select name="tipoNotificacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="tipoNotificacao_id" class="form-control @error('tipoNotificacao_id') is-invalid @enderror {{isset($bloquearCampos) && $bloquearCampos ? '' : 'select2'}}">
            <option value="">{{ __('labels.notification.selecione_tipo') }}</option>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}" {{ old('tipoNotificacao_id', $notificacao->tipoNotificacao_id ?? null) == $tipo->id ? 'selected' : '' }}>
                    {{ $tipo->descricao }}
                </option>
            @endforeach
        </select>
        @error('tipoNotificacao_id')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label for="mensagem">{{ __('labels.notification.mensagem') }}</label>
        <textarea name="mensagem" id="mensagem" class="form-control @error('mensagem') is-invalid @enderror" rows="5" placeholder="{{ __('labels.notification.mensagem_placeholder') }}">{{ old('mensagem', $notificacao->mensagem ?? '') }}</textarea>
        @error('mensagem')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="icone">{{ __('labels.notification.icone') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="icone" id="icone" class="form-control @error('icone') is-invalid @enderror" value="{{ old('icone', $notificacao->icone ?? null) }}" maxlength="50" placeholder="fas fa-bell">
        @error('icone')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
        <small class="form-text text-muted">{{ __('labels.notification.icone_help') }}</small>
    </div>

    <div class="form-group col-md-6">
        <label for="expira_em">{{ __('labels.notification.expira_em') }}</label>
        <input type="date" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="expira_em" id="expira_em" class="form-control @error('expira_em') is-invalid @enderror" value="{{ old('expira_em', $notificacao->expira_em ? $notificacao->expira_em->format('Y-m-d\TH:i') : '') }}">
        @error('expira_em')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
        <small class="form-text text-muted">{{ __('labels.notification.expira_em_help') }}</small>
    </div>
</div>

@if(!isset($notificacao) || !$notificacao->id)
<div class="row">
    <div class="form-group col-md-12">
        <label>{{ __('labels.notification.destino') }}</label>
        <div class="border rounded p-3">
            <div class="form-check mb-2">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="tipo_destino" value="todos" {{ old('tipo_destino', 'todos') == 'todos' ? 'checked' : '' }}>
                    {{ __('labels.notification.destino_todos') }}
                </label>
            </div>
            <div class="form-check mb-2">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="tipo_destino" value="especifico" {{ old('tipo_destino') == 'especifico' ? 'checked' : '' }}>
                    {{ __('labels.notification.destino_usuarios') }}
                </label>
            </div>
            <div class="form-check mb-2">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="tipo_destino" value="perfil" {{ old('tipo_destino') == 'perfil' ? 'checked' : '' }}>
                    {{ __('labels.notification.destino_perfis') }}
                </label>
            </div>
        </div>
        @error('tipo_destino')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row" id="usuarios-container" style="display: none;">
    <div class="form-group col-md-12">
        <label for="usuarios">{{ __('labels.notification.usuarios') }}</label>
        <select name="usuarios[]" id="usuarios" class="form-control select2" multiple="multiple">
            @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}" {{ in_array($usuario->id, old('usuarios', [])) ? 'selected' : '' }}>
                    {{ $usuario->name }} ({{ $usuario->email }})
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">{{ __('labels.notification.usuarios_help') }}</small>
        @error('usuarios')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row" id="perfis-container" style="display: none;">
    <div class="form-group col-md-12">
        <label for="perfis">{{ __('labels.notification.perfis') }}</label>
        <select name="perfis[]" id="perfis" class="form-control select2" multiple="multiple">
            @foreach($perfis as $perfil)
                <option value="{{ $perfil->id }}" {{ in_array($perfil->id, old('perfis', [])) ? 'selected' : '' }}>
                    {{ $perfil->descricao }}
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">{{ __('labels.notification.perfis_help') }}</small>
        @error('perfis')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
@endif
<script>
$(document).ready(function() {
    // Mostrar/esconder campos baseado no tipo de destino
    $('input[name="tipo_destino"]').on('change', function() {
        var selectedValue = $(this).val();

        $('#usuarios-container').toggle(selectedValue === 'especifico');
        $('#perfis-container').toggle(selectedValue === 'perfil');
    }).trigger('change'); // Executar no carregamento
});
</script>
