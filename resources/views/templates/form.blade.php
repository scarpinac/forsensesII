@csrf
<form method="POST" action="{{ route($route) }}">
    <div class="row">
        {{ $fieldsHtml }}
        <div class="col-12">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar
            </button>
            <a href="{{ route($backRoute) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</form>
