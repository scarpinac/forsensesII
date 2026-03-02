window.jQuery = window.$ = jQuery;

$(document).ready(function() {
    // Máscara CNPJ
    $('#cnpj').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 2) {
            $(this).val(value);
        } else if (value.length <= 5) {
            $(this).val(value.substring(0, 2) + '.' + value.substring(2));
        } else if (value.length <= 8) {
            $(this).val(value.substring(0, 2) + '.' + value.substring(2, 5) + '.' + value.substring(5));
        } else if (value.length <= 12) {
            $(this).val(value.substring(0, 2) + '.' + value.substring(2, 5) + '.' + value.substring(5, 8) + '/' + value.substring(8));
        } else {
            $(this).val(value.substring(0, 2) + '.' + value.substring(2, 5) + '.' + value.substring(5, 8) + '/' + value.substring(8, 12) + '-' + value.substring(12, 14));
        }
    });

    // Máscara CPF para responsavelCpf
    $('#responsavelCpf').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 3) {
            $(this).val(value);
        } else if (value.length <= 6) {
            $(this).val(value.substring(0, 3) + '.' + value.substring(3));
        } else if (value.length <= 9) {
            $(this).val(value.substring(0, 3) + '.' + value.substring(3, 6) + '.' + value.substring(6));
        } else {
            $(this).val(value.substring(0, 3) + '.' + value.substring(3, 6) + '.' + value.substring(6, 9) + '-' + value.substring(9, 11));
        }
    });

    // Formatar valores decimais (ex: descontoInicial, areaExposicao)
    $('.valor').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        value = (value / 100).toFixed(2) + '';
        value = value.replace(".", ",");
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        $(this).val(value);
    });

    // Código para detalhes do histórico
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

                    // Se o valor for um objeto, formata para string
                    const formatValue = (value) => {
                        if (typeof value === 'object' && value !== null) {
                            return JSON.stringify(value);
                        }
                        return value;
                    };

                    row.innerHTML = `
                        <td class="whiteSpace-nowrap"><strong>${fieldName}</strong></td>
                        <td class="whiteSpace-nowrap">${formatValue(anterior)}</td>
                        <td class="whiteSpace-nowrap">${formatValue(novo)}</td>
                    `;

                    // Destaca as linhas onde houve alteração
                    if (anterior !== novo) {
                        row.style.backgroundColor = '#fff3cd'; // Amarelo claro
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
});
