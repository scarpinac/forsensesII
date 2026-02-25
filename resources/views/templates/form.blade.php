@csrf
<form method="POST" action="{{ route($route) }}">
    <div class="row">
        {{ $fieldsHtml }}
        <div class="col-12">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> {{ __('labels.{{ $variable }}.save') }}
            </button>
            <a href="{{ route($backRoute) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('labels.{{ $variable }}.back') }}
            </a>
        </div>
    </div>
</form>
