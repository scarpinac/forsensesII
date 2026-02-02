@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="titulo">{{ __('labels.notification.title') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $notificacao->titulo ?? null) }}" maxlength="50">
        @error('titulo')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="tipoNotificacao_id">{{ __('labels.notification.type') }}</label>
        <select name="tipoNotificacao_id" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} id="tipoNotificacao_id" class="form-control @error('tipoNotificacao_id') is-invalid @enderror {{isset($bloquearCampos) && $bloquearCampos ? '' : 'select2'}}">
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
        <label for="mensagem">{{ __('labels.notification.message') }}</label>
        <textarea {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="mensagem" id="mensagem" class="form-control @error('mensagem') is-invalid @enderror" rows="5" placeholder="{{ __('labels.notification.menssage_placeholder') }}">{{ old('mensagem', $notificacao->mensagem ?? '') }}</textarea>
        @error('mensagem')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="enviar_em">{{ __('labels.notification.send_at') }}</label>
        <input type="datetime-local" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="enviar_em" id="enviar_em" class="form-control @error('enviar_em') is-invalid @enderror" value="{{ old('enviar_em', $notificacao->enviar_em ? $notificacao->enviar_em->format('Y-m-d\TH:i') : '') }}" required>
        @error('enviar_em')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
        <small class="form-text text-muted">{{ __('labels.notification.send_at_help') }}</small>
    </div>

    <div class="form-group col-md-6">
        <label for="icone">{{ __('labels.notification.icon') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="icone" id="icone" class="form-control @error('icone') is-invalid @enderror" value="{{ old('icone', $notificacao->icone ?? null) }}" maxlength="30" placeholder="fas fa-bell">
        @error('icone')
        <div class="invalid-feedback font-weight-bold" role="alert">
            {{ $message }}
        </div>
        @enderror
        <small class="form-text text-muted">{{ __('labels.notification.icon_help') }}</small>
    </div>
</div>

{{--@if(!isset($notificacao) || !$notificacao->id)--}}
{{--    <div class="row">--}}
{{--        <div class="form-group col-md-12">--}}
{{--            <label for="enviarNotificacaoPara_id">{{ __('labels.notification.destiny') }}</label>--}}
{{--            <select name="enviarNotificacaoPara_id" id="enviarNotificacaoPara_id" class="form-control @error('enviarNotificacaoPara_id') is-invalid @enderror" required>--}}
{{--                @foreach($tiposDestino as $tipo)--}}
{{--                    <option value="{{ $tipo->id }}" {{ old('enviarNotificacaoPara_id', '15') == $tipo->id ? 'selected' : '' }}>--}}
{{--                        {{ $tipo->descricao }}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            @error('enviarNotificacaoPara_id')--}}
{{--            <div class="invalid-feedback font-weight-bold" role="alert">--}}
{{--                {{ $message }}--}}
{{--            </div>--}}
{{--            @enderror--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="row" id="usuarios-container" style="display: none;">--}}
{{--        <div class="form-group col-md-12">--}}
{{--            <label for="usuarios">{{ __('labels.notification.usuarios') }}</label>--}}
{{--            <select name="usuarios[]" id="usuarios" class="form-control select2" multiple="multiple">--}}
{{--                @foreach($usuarios as $usuario)--}}
{{--                    <option value="{{ $usuario->id }}" {{ in_array($usuario->id, old('usuarios', [])) ? 'selected' : '' }}>--}}
{{--                        {{ $usuario->name }} ({{ $usuario->email }})--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            <small class="form-text text-muted">{{ __('labels.notification.usuarios_help') }}</small>--}}
{{--            @error('usuarios')--}}
{{--            <div class="invalid-feedback font-weight-bold" role="alert">--}}
{{--                {{ $message }}--}}
{{--            </div>--}}
{{--            @enderror--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="row" id="perfis-container" style="display: none;">--}}
{{--        <div class="form-group col-md-12">--}}
{{--            <label for="perfis">{{ __('labels.notification.perfis') }}</label>--}}
{{--            <select name="perfis[]" id="perfis" class="form-control select2" multiple="multiple">--}}
{{--                @foreach($perfis as $perfil)--}}
{{--                    <option value="{{ $perfil->id }}" {{ in_array($perfil->id, old('perfis', [])) ? 'selected' : '' }}>--}}
{{--                        {{ $perfil->descricao }}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            <small class="form-text text-muted">{{ __('labels.notification.perfis_help') }}</small>--}}
{{--            @error('perfis')--}}
{{--            <div class="invalid-feedback font-weight-bold" role="alert">--}}
{{--                {{ $message }}--}}
{{--            </div>--}}
{{--            @enderror--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}

