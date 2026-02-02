import jQuery from 'jquery';
import tinymce from "tinymce/tinymce";
import 'tinymce/themes/silver/theme';
import 'tinymce/icons/default/icons';
import 'tinymce/models/dom/model';

// Plugins (apenas gratuitos)
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/table';
import 'tinymce/plugins/wordcount';

// Language pack
import 'tinymce-i18n/langs5/pt_BR';

window.jQuery = window.$ = jQuery;
window.tinymce = tinymce;

$(function() {
    // Inicializar TinyMCE
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#mensagem',
            height: 300,
            license_key: 'gpl',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | code | fullscreen',
            menubar: false,
            statusbar: false,
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding:10px; }',
            language: 'pt_BR',
            placeholder: 'Digite a mensagem da notificação aqui...',
            branding: false,
            promotion: false,
            skin: false,
            content_css: false,
            setup: function(editor) {
                editor.on('init', function() {
                    console.log('TinyMCE inicializado com sucesso');
                });
            }
        });
    }

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
    }).trigger('change'); // Executar no carregamento
});

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
