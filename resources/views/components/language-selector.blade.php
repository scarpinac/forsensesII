@php
    $currentLocale = app()->getLocale();
@endphp

<div class="language-selector ml-3">
    <div class="btn-group" role="group">
        <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" name="locale" value="pt_BR" 
                    class="btn btn-sm {{ $currentLocale === 'pt_BR' ? 'btn-primary' : 'btn-outline-primary' }}"
                    title="Português (Brasil)">
                🇧🇷 PT
            </button>
        </form>
        <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" name="locale" value="it" 
                    class="btn btn-sm {{ $currentLocale === 'it' ? 'btn-primary' : 'btn-outline-primary' }}"
                    title="Italiano">
                🇮🇹 IT
            </button>
        </form>
    </div>
</div>
