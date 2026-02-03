<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                {{ __('labels.auth.email') }}
            </label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input id="email"
                       type="email"
                       class="form-control with-icon"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Digite seu e-mail"
                       required
                       autofocus
                       autocomplete="username">
            </div>
            @error('email')
                <div class="alert alert-danger mt-2 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="form-label">
                {{ __('labels.auth.password') }}
            </label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input id="password"
                       type="password"
                       class="form-control with-icon"
                       name="password"
                       placeholder="Digite sua senha"
                       required
                       autocomplete="current-password">
            </div>
            @error('password')
                <div class="alert alert-danger mt-2 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-login btn-block">
                <i class="fas fa-sign-in-alt me-2"></i>{{ __('labels.auth.login') }}
            </button>
        </div>

        <!-- Forgot Password -->
        <div class="text-center mt-4">
            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    <i class="fas fa-question-circle me-1"></i>{{ __('labels.auth.forgot_password') }}
                </a>
            @endif
        </div>
    </form>

    <!-- Additional Info -->
    <div class="text-center mt-4">
        <small class="text-muted">
            <i class="fas fa-shield-alt me-1"></i>
            Acesso seguro e criptografado
        </small>
    </div>
</x-guest-layout>
