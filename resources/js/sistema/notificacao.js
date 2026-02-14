window.jQuery = window.$ = jQuery;

$(function() {
    // Mostrar o textarea normalmente
    const mensagemElement = $('#mensagem');

    // Adicionar toolbar simples acima do textarea
    const toolbar = $(`
        <div class="mensagem-toolbar" style="margin-bottom: 10px; padding: 8px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-action="bold" title="Negrito">
                    <i class="fas fa-bold"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-action="italic" title="Itálico">
                    <i class="fas fa-italic"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-action="underline" title="Sublinhado">
                    <i class="fas fa-underline"></i>
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-list"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" data-action="ul">Lista não ordenada</a>
                        <a class="dropdown-item" data-action="ol">Lista ordenada</a>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-action="link" title="Inserir Link">
                    <i class="fas fa-link"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-action="clear" title="Limpar Formatação">
                    <i class="fas fa-eraser"></i>
                </button>
            </div>
        </div>
    `);

    // Inserir toolbar antes do textarea
    mensagemElement.before(toolbar);

    // Função para inserir texto formatado
    function insertText(tag, value = '') {
        const textarea = mensagemElement[0];
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);

        let newText = '';
        if (tag === 'ul') {
            newText = '\\n• ' + (selectedText || 'Item da lista');
        } else if (tag === 'ol') {
            newText = '\\n1. ' + (selectedText || 'Item da lista');
        } else if (tag === 'link') {
            const url = value || prompt('Digite a URL do link:', 'https://');
            const text = selectedText || prompt('Digite o texto do link:', 'Clique aqui');
            if (url && text) {
                newText = `[${text}](${url})`;
            }
        } else if (tag === 'clear') {
            // Remove formatação HTML simples
            newText = selectedText.replace(/<[^>]*>/g, '');
        } else {
            newText = `<${tag}>${selectedText || 'texto'}</${tag}>`;
        }

        // Inserir o texto
        textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(end);

        // Reposicionar o cursor
        const newCursorPos = start + newText.length;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
        textarea.focus();

        // Disparar evento change
        $(textarea).trigger('input');
    }

    // Event handlers para os botões
    toolbar.find('[data-action]').on('click', function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        insertText(action);
    });

    // Melhorar o textarea
    mensagemElement.addClass('form-control mensagem-textarea');
    mensagemElement.attr('rows', '8');

    // Inicializar Select2
    if (typeof $.fn.select2 === 'function') {
        $('.select2').select2({
            theme: 'bootstrap4',
            allowClear: true
        });
    }

    // Mostrar/esconder campos baseado no tipo de destino
    $('input[name="tipo_destino"]').on('change', function() {
        var selectedValue = $(this).val();

        $('#usuarios-container').toggle(selectedValue === 'especifico');
        $('#perfis-container').toggle(selectedValue === 'perfil');
    }).trigger('change');
});
