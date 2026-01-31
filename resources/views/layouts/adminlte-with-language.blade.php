@extends('adminlte::page')

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona o seletor de idioma na navbar
    const navbar = document.querySelector('.navbar-nav');
    if (navbar) {
        const languageSelector = `
            <div class="language-selector ml-3">
                <div class="btn-group" role="group">
                    <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" name="locale" value="pt_BR"
                                class="btn btn-sm {{ app()->getLocale() === 'pt_BR' ? 'btn-system' : 'btn-outline-system' }}"
                                title="Português (Brasil)">
                            🇧🇷 PT
                        </button>
                    </form>
                    <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" name="locale" value="en"
                                class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-system' : 'btn-outline-system' }}"
                                title="English">
                            🇺🇸 En
                        </button>
                    </form>
                    <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" name="locale" value="it"
                                class="btn btn-sm {{ app()->getLocale() === 'it' ? 'btn-system' : 'btn-outline-system' }}"
                                title="Italiano">
                            🇮🇹 IT
                        </button>
                    </form>
                </div>
            </div>
        `;

        // Cria um elemento li para o seletor
        const li = document.createElement('li');
        li.className = 'nav-item';
        li.innerHTML = languageSelector;

        // Adiciona à navbar
        navbar.appendChild(li);
    }
});
</script>
@endpush
