window.jQuery = window.$ = jQuery;

// Validador de cor hexadecimal e preview da cor
$(document).ready(function() {
    $('#corHexadecimal').on('input', function() {
        let value = $(this).val();
        
        // Valida se é uma cor hexadecimal válida
        if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(value)) {
            $(this).removeClass('is-invalid').addClass('is-valid');
            // Adiciona preview da cor
            if (!$('#colorPreview').length) {
                $(this).after('<div id="colorPreview" style="margin-top: 5px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">Preview: <span style="display: inline-block; width: 50px; height: 20px; border: 1px solid #ccc; vertical-align: middle;"></span></div>');
            }
            $('#colorPreview span').css('background-color', value);
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
            $('#colorPreview').remove();
        }
    });
    
    // Formatar a cor ao carregar a página (em caso de edição)
    var corAtual = $('#corHexadecimal').val();
    if (corAtual) {
        $('#corHexadecimal').trigger('input');
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

                // Para campo de cor, adiciona preview visual
                const formatColorValue = (value) => {
                    if (field === 'corHexadecimal' && value && value !== '-') {
                        return `<span style="display: inline-block; width: 20px; height: 20px; background-color: ${value}; border: 1px solid #ccc; vertical-align: middle; margin-right: 5px;"></span>${formatValue(value)}`;
                    }
                    return formatValue(value);
                };

                row.innerHTML = `
                    <td class="whiteSpace-nowrap"><strong>${fieldName}</strong></td>
                    <td class="whiteSpace-nowrap">${formatColorValue(anterior)}</td>
                    <td class="whiteSpace-nowrap">${formatColorValue(novo)}</td>
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
