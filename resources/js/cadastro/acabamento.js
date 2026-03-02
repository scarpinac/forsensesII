window.jQuery = window.$ = jQuery;

$(".detalhes").on('click', function() {
    let button = $(this);
    const url = button.data('details-url');

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const detailsContent = document.getElementById('detailsContent');
            detailsContent.innerHTML = '';

            const table = document.createElement('table');
            table.className = 'table table-bordered table-sm';

            const thead = document.createElement('thead');
            thead.innerHTML = `
                <tr>
                    <th class="whiteSpace-nowrap col-md-4">Campo</th>
                    <th class="whiteSpace-nowrap col-md-4">Valor Anterior</th>
                    <th class="whiteSpace-nowrap col-md-4">Novo Valor</th>
                </tr>
            `;
            table.appendChild(thead);

            const tbody = document.createElement('tbody');

            const allFields = new Set();
            if (data.dadosAnteriores) {
                Object.keys(data.dadosAnteriores).forEach(key => allFields.add(key));
            }
            if (data.dadosNovos) {
                Object.keys(data.dadosNovos).forEach(key => allFields.add(key));
            }

            allFields.forEach(field => {
                const row = document.createElement('tr');
                const fieldName = data.camposTabela[field] || field;

                const anterior = data.dadosAnteriores ? (data.dadosAnteriores[field] !== undefined ? data.dadosAnteriores[field] : '-') : '-';
                const novo = data.dadosNovos ? (data.dadosNovos[field] !== undefined ? data.dadosNovos[field] : '-') : '-';

                const formatValue = (value) => {
                    if (typeof value === 'object' && value !== null) {
                        return JSON.stringify(value);
                    }
                    return value;
                };

                const formatColorValue = (value) => {
                    if (field === 'cor_id' && value && value !== '-') {
                        // Se for um ID de cor, tenta encontrar a cor correspondente
                        if (typeof value === 'number') {
                            return `<span style="display: inline-block; width: 20px; height: 20px; background-color: #ccc; border: 1px solid #ccc; vertical-align: middle; margin-right: 5px;"></span>ID: ${formatValue(value)}`;
                        }
                    }
                    return formatValue(value);
                };

                row.innerHTML = `
                    <td class="whiteSpace-nowrap"><strong>${fieldName}</strong></td>
                    <td class="whiteSpace-nowrap">${formatColorValue(anterior)}</td>
                    <td class="whiteSpace-nowrap">${formatColorValue(novo)}</td>
                `;

                if (anterior !== novo) {
                    row.style.backgroundColor = '#fff3cd';
                }

                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            detailsContent.appendChild(table);

            $('#detailsModal').modal('show');
        })
        .catch(error => {
            console.error('Erro ao carregar detalhes:', error);
            document.getElementById('detailsContent').innerHTML = '<p class="text-danger">Erro ao carregar os detalhes.</p>';
            $('#detailsModal').modal('show');
        });
});
