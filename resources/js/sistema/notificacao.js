window.jQuery = window.$ = jQuery;

/**
 * Exibe os detalhes de uma alteração do histórico em uma modal.
 * @param {HTMLElement} button - O elemento do botão que foi clicado.
 */
$(".detalhes").on('click', function() {
    let button = $(this);
    const url = button.data('details-url');

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Limpa o conteúdo anterior
            const detailsContent = document.getElementById('detailsContent');
            detailsContent.innerHTML = '';

            // Cria a tabela de comparação
            const table = document.createElement('table');
            table.className = 'table table-bordered table-sm';

            // Cabeçalho
            const thead = document.createElement('thead');
            thead.innerHTML = `
                <tr>
                    <th>Campo</th>
                    <th>Valor Anterior</th>
                    <th>Novo Valor</th>
                </tr>
            `;
            table.appendChild(thead);

            // Corpo da tabela
            const tbody = document.createElement('tbody');

            // Encontra todos os campos possíveis
            const allFields = new Set();
            if (data.dadosAnteriores) {
                Object.keys(data.dadosAnteriores).forEach(key => allFields.add(key));
            }
            if (data.dadosNovos) {
                Object.keys(data.dadosNovos).forEach(key => allFields.add(key));
            }

            // Adiciona as linhas para cada campo
            allFields.forEach(field => {
                const row = document.createElement('tr');
                const fieldName = data.camposTabela[field] || field;

                const anterior = data.dadosAnteriores ? (data.dadosAnteriores[field] !== undefined ? data.dadosAnteriores[field] : '-') : '-';
                const novo = data.dadosNovos ? (data.dadosNovos[field] !== undefined ? data.dadosNovos[field] : '-') : '-';

                // Se o valor for um objeto (como timestamps), formata para string
                const formatValue = (value) => {
                    if (typeof value === 'object' && value !== null) {
                        return JSON.stringify(value);
                    }
                    return value;
                };

                row.innerHTML = `
                    <td><strong>${fieldName}</strong></td>
                    <td>${formatValue(anterior)}</td>
                    <td>${formatValue(novo)}</td>
                `;

                // Destaca as linhas onde houve alteração
                if (anterior !== novo) {
                    row.style.backgroundColor = '#fff3cd'; // Amarelo claro para destacar
                }

                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            detailsContent.appendChild(table);

            // Abre o modal
            $('#detailsModal').modal('show');
        })
        .catch(error => {
            console.error('Erro ao carregar detalhes:', error);
            document.getElementById('detailsContent').innerHTML = '<p class="text-danger">Erro ao carregar os detalhes.</p>';
            $('#detailsModal').modal('show');
        });
});

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
