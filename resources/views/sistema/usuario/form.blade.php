@csrf
<div class="row">
    <div class="form-group col-md-6">
        <label for="name">{{ __('labels.user.name') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $usuario->name ?? null) }}">
        @error('name')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="email">{{ __('labels.user.email') }}</label>
        <input type="email" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email ?? null) }}">
        @error('email')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="password">{{ __('labels.user.password') }}</label>
        <input type="password" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="password" id="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="{{ !$usuario ? __('labels.user.password.required') : __('labels.user.password.optional') }}">
        @error('password')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="password_confirmation">{{ __('labels.user.password_confirmation') }}</label>
        <input type="password" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{ old('password_confirmation') }}" placeholder="{{ __('labels.user.password_confirmation.placeholder') }}">
        @error('password_confirmation')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="admin">{{ __('labels.user.admin') }}</label>
        <select {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="admin" id="admin" class="form-control @error('admin') is-invalid @enderror">
            <option value="0" {{ old('admin', $usuario->admin ?? null) == 0 ? 'selected' : '' }}>{{ __('labels.user.admin.no') }}</option>
            <option value="1" {{ old('admin', $usuario->admin ?? null) == 1 ? 'selected' : '' }}>{{ __('labels.user.admin.yes') }}</option>
        </select>
        @error('admin')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label for="avatar">{{ __('labels.user.avatar') }}</label>
        @if(!isset($bloquearCampos))
            <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/jpeg,image/png,image/gif,image/webp">
        @endif

        @if($usuario && $usuario->hasMedia('avatar'))
            <div class="mt-2">
                @php($arArquivoFoto = explode('storage', $usuario->avatar))
                @if(isset($arArquivoFoto[1]))
                    <img src="{{ '/storage' . $arArquivoFoto[1] }}" class="form-control" loading="lazy" alt="avatar" id="img" style="width:200px; height:auto"/>
                @else
                    <img src="{{ $usuario->avatar }}" class="form-control" loading="lazy" alt="avatar" id="img" style="width:200px; height:auto"/>
                @endif
                @if(!isset($bloquearCampos))
                    <br>
                    <small class="text-muted">{{ __('labels.user.avatar.current') }}</small>
                @endif
            </div>
        @endif

        @error('avatar')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
