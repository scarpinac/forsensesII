$(document).ready(function() {
    let enderecoIndex = 0;
    let contatoIndex = 0;

    // Controle de tipo de cliente (PF/PJ)
    $('#tipoCliente_id').on('change', function() {
        const tipoCliente = $(this).find('option:selected').text().toLowerCase();
        
        // Esconder todos os campos específicos
        $('.campos-tipo').hide();
        
        // Limpar campos obrigatórios
        $('.campos-tipo input[required]').prop('required', false);
        
        if (tipoCliente.includes('física') || tipoCliente.includes('pessoa física')) {
            $('#campos_pf').show();
            $('#cpf').prop('required', true);
        } else if (tipoCliente.includes('jurídica') || tipoCliente.includes('pessoa jurídica')) {
            $('#campos_pj').show();
            $('#campos_responsavel').show();
            $('#cnpj').prop('required', true);
        }
    });

    // Máscaras manuais (igual ao comissao.js)
    // Máscara CPF
    $('#cpf').on('input', function() {
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

    // Máscara CPF do Responsável
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

    // Máscaras de telefone (dinâmicas para contatos)
    function applyPhoneMask(element) {
        element.off('keyup.mask').on('keyup.mask', function() {
            let value = $(this).val().replace(/\D/g, '');
            let formatted = '';
            
            if (value.length <= 11) {
                if (value.length <= 2) {
                    formatted = value;
                } else if (value.length <= 7) {
                    formatted = '(' + value.substring(0, 2) + ') ' + value.substring(2);
                } else {
                    formatted = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7, 11);
                }
            } else {
                if (value.length <= 2) {
                    formatted = value;
                } else if (value.length <= 3) {
                    formatted = '+' + value.substring(0, 2) + ' ' + value.substring(2);
                } else if (value.length <= 7) {
                    formatted = '+' + value.substring(0, 2) + ' (' + value.substring(2, 4) + ') ' + value.substring(4);
                } else if (value.length <= 11) {
                    formatted = '+' + value.substring(0, 2) + ' (' + value.substring(2, 4) + ') ' + value.substring(4, 8) + '-' + value.substring(8, 12);
                } else {
                    formatted = '+' + value.substring(0, 2) + ' (' + value.substring(2, 4) + ') ' + value.substring(4, 9) + '-' + value.substring(9, 13);
                }
            }
            
            $(this).val(formatted);
        });
    }

    // Adicionar endereço
    $('#btn-add-endereco').on('click', function() {
        const template = $('#template-endereco').html();
        const newHtml = template.replace(/INDEX/g, enderecoIndex);
        
        $('#enderecos-container').append(newHtml);
        
        // Aplicar máscara CEP no novo campo
        $('#enderecos-container .endereco-item:last .cep').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length <= 5) {
                $(this).val(value);
            } else {
                $(this).val(value.substring(0, 5) + '-' + value.substring(5, 8));
            }
        });
        
        // Aplicar máscara estado (apenas letras maiúsculas)
        $('#enderecos-container .endereco-item:last .estado').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });
        
        enderecoIndex++;
    });

    // Remover endereço
    $(document).on('click', '.btn-remove-endereco', function() {
        $(this).closest('.endereco-item').remove();
    });

    // Adicionar contato
    $('#btn-add-contato').on('click', function() {
        const template = $('#template-contato').html();
        const newHtml = template.replace(/INDEX/g, contatoIndex);
        
        $('#contatos-container').append(newHtml);
        
        // Aplicar máscara de telefone no novo campo
        const telefoneField = $('#contatos-container .contato-item:last .telefone');
        applyPhoneMask(telefoneField);
        
        contatoIndex++;
    });

    // Remover contato
    $(document).on('click', '.btn-remove-contato', function() {
        $(this).closest('.contato-item').remove();
    });

    // Validação de CPF/CNPJ
    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false;
        }
        
        let soma = 0;
        let resto;
        
        for (let i = 1; i <= 9; i++) {
            soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
        }
        
        resto = (soma * 10) % 11;
        
        if (resto === 10 || resto === 11) {
            resto = 0;
        }
        
        if (resto !== parseInt(cpf.substring(9, 10))) {
            return false;
        }
        
        soma = 0;
        
        for (let i = 1; i <= 10; i++) {
            soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
        }
        
        resto = (soma * 10) % 11;
        
        if (resto === 10 || resto === 11) {
            resto = 0;
        }
        
        return resto === parseInt(cpf.substring(10, 11));
    }

    function validarCNPJ(cnpj) {
        cnpj = cnpj.replace(/\D/g, '');
        
        if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
            return false;
        }
        
        let tamanho = cnpj.length - 2;
        let numeros = cnpj.substring(0, tamanho);
        let digitos = cnpj.substring(tamanho);
        let soma = 0;
        let pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        
        let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        
        if (resultado !== parseInt(digitos.charAt(0))) {
            return false;
        }
        
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        
        resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        
        return resultado === parseInt(digitos.charAt(1));
    }

    // Validação em tempo real
    $('#cpf').on('blur', function() {
        const cpf = $(this).val();
        if (cpf && !validarCPF(cpf)) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">CPF inválido</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    $('#cnpj').on('blur', function() {
        const cnpj = $(this).val();
        if (cnpj && !validarCNPJ(cnpj)) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">CNPJ inválido</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // Formatar CPF ao carregar a página (em caso de edição)
    var cpfAtual = $('#cpf').val();
    if (cpfAtual) {
        var cpfLimpo = cpfAtual.replace(/\D/g, '');
        if (cpfLimpo.length === 11) {
            $('#cpf').val(cpfLimpo.substring(0, 3) + '.' + cpfLimpo.substring(3, 6) + '.' + cpfLimpo.substring(6, 9) + '-' + cpfLimpo.substring(9, 11));
        }
    }

    // Formatar CNPJ ao carregar a página (em caso de edição)
    var cnpjAtual = $('#cnpj').val();
    if (cnpjAtual) {
        var cnpjLimpo = cnpjAtual.replace(/\D/g, '');
        if (cnpjLimpo.length === 14) {
            $('#cnpj').val(cnpjLimpo.substring(0, 2) + '.' + cnpjLimpo.substring(2, 5) + '.' + cnpjLimpo.substring(5, 8) + '/' + cnpjLimpo.substring(8, 12) + '-' + cnpjLimpo.substring(12, 14));
        }
    }

    // Formatar CPF do Responsável ao carregar a página (em caso de edição)
    var cpfResponsavelAtual = $('#responsavelCpf').val();
    if (cpfResponsavelAtual) {
        var cpfResponsavelLimpo = cpfResponsavelAtual.replace(/\D/g, '');
        if (cpfResponsavelLimpo.length === 11) {
            $('#responsavelCpf').val(cpfResponsavelLimpo.substring(0, 3) + '.' + cpfResponsavelLimpo.substring(3, 6) + '.' + cpfResponsavelLimpo.substring(6, 9) + '-' + cpfResponsavelLimpo.substring(9, 11));
        }
    }

    // Máscara monetária para limite de crédito (igual ao comissao.js)
    $('#limite_credito').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        value = (value / 100).toFixed(2) + '';
        value = value.replace(".", ",");
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        $(this).val(value);
    });
    
    // Formatar o valor ao carregar a página (em caso de edição)
    var limiteAtual = $('#limite_credito').val();
    if (limiteAtual) {
        // Remove formatação e aplica a máscara
        var limiteNumerico = parseFloat(limiteAtual).toFixed(2);
        limiteNumerico = limiteNumerico.replace(".", ",");
        limiteNumerico = limiteNumerico.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        $('#limite_credito').val(limiteNumerico);
    }

    // Busca de CEP (Via CEP)
    $(document).on('blur', '.cep', function() {
        const cep = $(this).val().replace(/\D/g, '');
        const $enderecoItem = $(this).closest('.endereco-item');
        
        if (cep.length === 8) {
            $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                if (!data.erro) {
                    $enderecoItem.find('.logradouro').val(data.logradouro);
                    $enderecoItem.find('.bairro').val(data.bairro);
                    $enderecoItem.find('.cidade').val(data.localidade);
                    $enderecoItem.find('.estado').val(data.uf);
                    $enderecoItem.find('.numero').focus();
                }
            }).fail(function() {
                console.log('Erro ao buscar CEP');
            });
        }
    });

    // Inicialização
    if ($('#tipoCliente_id').val()) {
        $('#tipoCliente_id').trigger('change');
    }
});

// Mantido o código de detalhes para histórico
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
                    <td class="whiteSpace-nowrap"><strong>${fieldName}</strong></td>
                    <td class="whiteSpace-nowrap">${formatValue(anterior)}</td>
                    <td class="whiteSpace-nowrap">${formatValue(novo)}</td>
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
