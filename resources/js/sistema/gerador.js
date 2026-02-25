window.jQuery = window.$ = jQuery;

let campoIndex = 0;

function adicionarCampo() {
    let template = $('#campoTemplate').html();
    let container = $('#camposContainer');

    // Substituir índices
    template = template.replace(/campos\[\]/g, 'campos[' + campoIndex + ']');

    let campoDiv = $('<div>').html(template);
    container.append(campoDiv);

    campoIndex++;

    atualizarRelacionamentos();
}

window.adicionarCampo = adicionarCampo;

function removerCampo(button) {
    $(button).closest('.campo-item').remove();
}

window.removerCampo = removerCampo;

function atualizarOpcoesCampo(select) {
    let campoDiv = $(select).closest('.campo-item');
    let tipo = $(select).val();
    let relacionamentoSelect = campoDiv.find('.campo-relacionamento');
    let maxInput = campoDiv.find('.campo-max');

    if (tipo === 'select') {
        relacionamentoSelect.prop('disabled', false);
        maxInput.prop('disabled', true).val('');
    } else {
        relacionamentoSelect.prop('disabled', true).val('');
        maxInput.prop('disabled', false);
    }

    // Definir tamanho padrão para strings
    if (tipo === 'string' && !maxInput.val()) {
        maxInput.val(255);
    }
}

// Ensure function is globally accessible
window.atualizarOpcoesCampo = atualizarOpcoesCampo;

function atualizarRelacionamentos() {
    if (!window.tabelasDisponiveis) return;

    $('.campo-relacionamento').each(function() {
        let select = $(this);
        if (select.prop('disabled')) return;

        let currentValue = select.val();
        select.empty().append('<option value="">Nenhum</option>');

        $("#tabelasDisponiveis").val().forEach(function(tabela) {
            // Converter nome da tabela para nome da classe (plural para singular)
            let classe = tabela.replace(/s$/, '');
            classe = classe.charAt(0).toUpperCase() + classe.slice(1);
            select.append('<option value="' + classe + '">' + classe + '</option>');
        });

        select.val(currentValue);
    });
}

$(function() {
    if (typeof $.fn.select2 === 'function') {
        $('.select2').select2({
            theme: 'bootstrap4',
            allowClear: true
        });
    }

});

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
                    <th class="whiteSpace-nowrap col-md-4">Campo</th>
                    <th class="whiteSpace-nowrap col-md-4">Valor Anterior</th>
                    <th class="whiteSpace-nowrap col-md-4">Novo Valor</th>
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
                    <td class="whiteSpace-nowrap">><strong>${fieldName}</strong></td>
                    <td class="whiteSpace-nowrap">>${formatValue(anterior)}</td>
                    <td class="whiteSpace-nowrap">>${formatValue(novo)}</td>
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
