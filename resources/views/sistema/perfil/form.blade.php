@csrf
<div class="row">
    <div class="form-group col-md-12">
        <label for="descricao">{{ __('labels.access_level.description') }}</label>
        <input type="text" {{isset($bloquearCampos) && $bloquearCampos ? 'disabled' : ''}} name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao', $perfil->descricao ?? null) }}">
        @error('descricao')
            <div class="invalid-feedback font-weight-bold" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

@if(isset($htmlPermissoes))
<style>
.permissions-tree .form-check {
    margin-bottom: 0.5rem;
    position: relative;
}

.permissions-tree .form-check-input {
    position: relative;
    margin-top: 0;
    margin-right: 0.5rem;
}

.permissions-tree .form-check-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    width: 100%;
}

.permissions-tree .ml-4 {
    margin-left: 1.5rem !important;
    border-left: 1px solid #e9ecef;
    padding-left: 1rem;
}

.permissions-tree [id$="_children"] {
    margin-top: 0.5rem;
}
</style>

<div class="row">
    <div class="form-group col-md-12">
        <label>{{ __('labels.access_level.permissions') }}</label>
        <div class="card">
            <div class="card-body p-3">
                @if(!isset($bloquearCampos) || !$bloquearCampos)
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="marcarDesmarcarTodos(true)">
                            <i class="fas fa-check-square"></i> {{ __('labels.access_level.select_all') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="marcarDesmarcarTodos(false)">
                            <i class="fas fa-square"></i> {{ __('labels.access_level.deselect_all') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="expandirTodos()">
                            <i class="fas fa-expand"></i> {{ __('labels.access_level.expand_all') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="colapsarTodos()">
                            <i class="fas fa-compress"></i> {{ __('labels.access_level.collapse_all') }}
                        </button>
                    </div>
                @endif
                <div class="permissions-tree" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                    {!! $htmlPermissoes !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para colapsar/expandir nós da árvore
function toggleCollapse(itemId) {
    const children = document.getElementById(itemId + '_children');
    const icon = document.getElementById(itemId + '_icon');
    
    if (children.style.display === 'none') {
        children.style.display = 'block';
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    } else {
        children.style.display = 'none';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    }
}

// Função para marcar/desmarcar todos os filhos em cascata
function toggleChildren(itemId, checked) {
    const children = document.getElementById(itemId + '_children');
    if (!children) return;
    
    // Encontrar todos os checkboxes de permissão dentro dos filhos
    const checkboxes = children.querySelectorAll('input[name="permissoes[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = checked;
    });
    
    // Aplicar recursivamente aos checkboxes pais dentro dos filhos
    const parentCheckboxes = children.querySelectorAll('input[type="checkbox"][id$="_check"]');
    parentCheckboxes.forEach(checkbox => {
        checkbox.checked = checked;
        checkbox.indeterminate = false;
        toggleChildren(checkbox.id.replace('_check', ''), checked);
    });
    
    // Atualizar estado dos pais
    updateParentStates();
}

// Atualizar estado dos checkboxes pais (marcado/desmarcado/indeterminado)
function updateParentStates() {
    document.querySelectorAll('input[type="checkbox"][id$="_check"]').forEach(checkbox => {
        const itemId = checkbox.id.replace('_check', '');
        const children = document.getElementById(itemId + '_children');
        
        if (children) {
            const childCheckboxes = children.querySelectorAll('input[name="permissoes[]"]');
            const checkedCount = Array.from(childCheckboxes).filter(cb => cb.checked).length;
            
            if (checkedCount === 0) {
                checkbox.checked = false;
                checkbox.indeterminate = false;
            } else if (checkedCount === childCheckboxes.length) {
                checkbox.checked = true;
                checkbox.indeterminate = false;
            } else {
                checkbox.checked = false;
                checkbox.indeterminate = true;
            }
        }
    });
}

// Marcar/desmarcar todos os checkboxes
function marcarDesmarcarTodos(marcar) {
    const checkboxes = document.querySelectorAll('input[name="permissoes[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = marcar;
    });
    
    const parentCheckboxes = document.querySelectorAll('input[type="checkbox"][id$="_check"]');
    parentCheckboxes.forEach(checkbox => {
        checkbox.checked = marcar;
        checkbox.indeterminate = false;
    });
}

// Expandir todos os nós
function expandirTodos() {
    document.querySelectorAll('[id$="_children"]').forEach(children => {
        children.style.display = 'block';
    });
    document.querySelectorAll('[id$="_icon"]').forEach(icon => {
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    });
}

// Colapsar todos os nós
function colapsarTodos() {
    document.querySelectorAll('[id$="_children"]').forEach(children => {
        children.style.display = 'none';
    });
    document.querySelectorAll('[id$="_icon"]').forEach(icon => {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    });
}

// Atualizar estados quando checkboxes individuais mudam
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="permissoes[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateParentStates();
        });
    });
    
    // Configurar checkboxes indeterminate iniciais
    updateParentStates();
});
</script>
@endif
