<?php

return [
    'welcome' => 'Bem-vindo ao sistema',
    'no_records' => 'Nenhum registro encontrado',
    'confirm_delete' => 'Tem certeza que deseja excluir?',

    // Menu Form Request Validation Messages
    'menu' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 25 caracteres.',
            ],
            'icone' => [
                'string' => 'O campo ícone deve ser um texto.',
                'max' => 'O campo ícone não pode ter mais de 50 caracteres.',
            ],
            'rota' => [
                'required' => 'O campo rota é obrigatório.',
                'string' => 'O campo rota deve ser um texto.',
                'max' => 'O campo rota não pode ter mais de 80 caracteres.',
            ],
            'menuPai_id' => [
                'exists' => 'O menu pai selecionado é inválido.',
            ],
            'permissao_id' => [
                'required' => 'O campo permissão é obrigatório.',
                'exists' => 'A permissão selecionada é inválida.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
        ],
    ],

    // Permission Form Request Validation Messages
    'permission' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 80 caracteres.',
            ],
        ],
    ],

    // API Form Request Validation Messages
    'api' => [
        'validation' => [
            'api_id' => [
                'required' => 'O campo tipo de API é obrigatório.',
                'exists' => 'O tipo de API selecionado é inválido.',
            ],
            'credencial' => [
                'required' => 'O campo credencial é obrigatório.',
                'string' => 'O campo credencial deve ser um texto.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
        ],
    ],

    // Parâmetro Form Request Validation Messages
    'parametro' => [
        'validation' => [
            'nome' => [
                'required' => 'O campo nome é obrigatório.',
                'string' => 'O campo nome deve ser um texto.',
                'max' => 'O campo nome não pode ter mais de 80 caracteres.',
            ],
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
            ],
            'tipo_id' => [
                'required' => 'O campo tipo é obrigatório.',
                'exists' => 'O tipo selecionado é inválido.',
            ],
            'valor' => [
                'required' => 'O campo valor é obrigatório.',
                'string' => 'O campo valor deve ser um texto.',
            ],
        ],
    ],
    'usuario' => [
        'validation' => [
            'name' => [
                'required' => 'O campo nome é obrigatório.',
                'string' => 'O campo nome deve ser um texto.',
                'max' => 'O campo nome não pode ter mais de 255 caracteres.',
            ],
            'email' => [
                'required' => 'O campo e-mail é obrigatório.',
                'string' => 'O campo e-mail deve ser um texto.',
                'email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
                'max' => 'O campo e-mail não pode ter mais de 255 caracteres.',
                'unique' => 'O e-mail informado já está em uso.',
            ],
            'password' => [
                'required' => 'O campo senha é obrigatório.',
                'string' => 'O campo senha deve ser um texto.',
                'min' => 'O campo senha deve ter no mínimo 8 caracteres.',
                'confirmed' => 'A confirmação da senha não confere.',
            ],
            'avatar' => [
                'mimes' => 'O campo avatar deve ser uma imagem do tipo: jpeg, png, jpg, gif ou webp.',
                'max' => 'O campo avatar não pode ter mais de 5MB.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
        ],
    ],

    // Access Level Form Request Validation Messages
    'access_level' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 80 caracteres.',
            ],
            'permissoes' => [
                'array' => 'O campo permissões deve ser um array.',
                'integer' => 'Cada permissão deve ser um número inteiro.',
                'exists' => 'Uma das permissões selecionadas não é válida.',
            ],
        ],
    ],

    // Notificação Form Request Validation Messages
    'notification' => [
        'validation' => [
            'title' => [
                'required' => 'O campo título é obrigatório.',
                'string' => 'O campo título deve ser uma string.',
                'max' => 'O campo título não pode ter mais de 50 caracteres.',
            ],
            'message' => [
                'required' => 'O campo mensagem é obrigatório.',
                'string' => 'O campo mensagem deve ser uma string.',
            ],
            'notification_type' => [
                'required' => 'O campo tipo de notificação é obrigatório.',
                'exists' => 'O tipo de notificação selecionado é inválido.',
            ],
            'sent_notification_to' => [
                'required' => 'O campo destinatário é obrigatório.',
                'exists' => 'O destinatário selecionado é inválido.',
            ],
            'icon' => [
                'string' => 'O campo ícone deve ser uma string.',
                'max' => 'O campo ícone não pode ter mais de 30 caracteres.',
            ],
            'send_at' => [
                'required' => 'O campo data/hora de envio é obrigatório.',
                'date' => 'O campo data/hora de envio deve ser uma data válida.',
                'after' => 'A data/hora de envio deve ser posterior à data/hora atual.',
            ],
            'expired_at' => [
                'date' => 'O campo data de expiração deve ser uma data válida.',
                'after' => 'A data de expiração deve ser posterior à data/hora atual.',
            ],
            'send_to' => [
                'required' => 'O campo enviar para é obrigatório.',
                'string' => 'O campo enviar para deve ser uma string.',
            ],
            'users' => [
                'required_if' => 'O campo usuários é obrigatório quando o destinatário for "Usuários Específicos".',
                'array' => 'O campo usuários deve ser um array.',
                'exists' => 'Um ou mais usuários selecionados são inválidos.',
            ],
            'profiles' => [
                'required_if' => 'O campo perfis é obrigatório quando o destinatário for "Perfis Específicos".',
                'array' => 'O campo perfis deve ser um array.',
                'exists' => 'Um ou mais perfis selecionados são inválidos.',
            ],
        ],
    ],
    'comissao' => [
        'validation' => [
            'valor' => [
                'required' => 'O campo valor é obrigatório.',
                'numeric' => 'O campo valor deve ser um número.',
                'min' => 'O campo valor deve ser no mínimo 0.',
                'max' => 'O campo valor não pode ser maior que 99.999.999,99.',
            ],
            'tipoComissao_id' => [
                'required' => 'O campo tipo de comissão é obrigatório.',
                'exists' => 'O tipo de comissão selecionado é inválido.',
            ],
        ],
    ],

    'cor' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 50 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'corHexadecimal' => [
                'required' => 'O campo cor hexadecimal é obrigatório.',
                'string' => 'O campo cor hexadecimal deve ser um texto.',
                'max' => 'O campo cor hexadecimal não pode ter mais de 10 caracteres.',
            ],
        ],
    ],

    'payment_condition' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 50 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'revenda_id' => [
                'nullable' => 'O campo revenda é opcional.',
                'exists' => 'A revenda selecionada é inválida.',
            ],
            'diasEntreParcelas' => [
                'required' => 'O campo dias entre parcelas é obrigatório.',
                'integer' => 'O campo dias entre parcelas deve ser um número inteiro.',
                'min' => 'O campo dias entre parcelas deve ser no mínimo 0.',
            ],
            'quantidadeParcelas' => [
                'required' => 'O campo quantidade de parcelas é obrigatório.',
                'integer' => 'O campo quantidade de parcelas deve ser um número inteiro.',
                'min' => 'O campo quantidade de parcelas deve ser no mínimo 1.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
        ],
    ],

    'finish' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 50 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'tipoAcabamento_id' => [
                'required' => 'O campo tipo de acabamento é obrigatório.',
                'exists' => 'O tipo de acabamento selecionado é inválido.',
            ],
            'cor_id' => [
                'required' => 'O campo cor é obrigatório.',
                'exists' => 'A cor selecionada é inválida.',
            ],
        ],
    ],

    'family' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 50 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
        ],
    ],

    'screen' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 50 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'cor_id' => [
                'required' => 'O campo cor é obrigatório.',
                'exists' => 'A cor selecionada é inválida.',
            ],
        ],
    ],

    'product' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 100 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'codigo' => [
                'required' => 'O campo código é obrigatório.',
                'string' => 'O campo código deve ser um texto.',
                'max' => 'O campo código não pode ter mais de 50 caracteres.',
                'unique' => 'Este código já está sendo utilizado.',
            ],
            'quantidadeVolumes' => [
                'required' => 'O campo quantidade de volumes é obrigatório.',
                'integer' => 'O campo quantidade de volumes deve ser um número inteiro.',
                'min' => 'O campo quantidade de volumes deve ser no mínimo 0.',
            ],
            'peso' => [
                'required' => 'O campo peso é obrigatório.',
                'numeric' => 'O campo peso deve ser um número.',
                'min' => 'O campo peso deve ser no mínimo 0.',
            ],
            'altura' => [
                'required' => 'O campo altura é obrigatório.',
                'numeric' => 'O campo altura deve ser um número.',
                'min' => 'O campo altura deve ser no mínimo 0.',
            ],
            'largura' => [
                'required' => 'O campo largura é obrigatório.',
                'numeric' => 'O campo largura deve ser um número.',
                'min' => 'O campo largura deve ser no mínimo 0.',
            ],
            'comprimento' => [
                'required' => 'O campo comprimento é obrigatório.',
                'numeric' => 'O campo comprimento deve ser um número.',
                'min' => 'O campo comprimento deve ser no mínimo 0.',
            ],
            'precoUnitario' => [
                'required' => 'O campo preço unitário é obrigatório.',
                'numeric' => 'O campo preço unitário deve ser um número.',
                'min' => 'O campo preço unitário deve ser no mínimo 0.',
            ],
            'descontoProduto' => [
                'required' => 'O campo desconto é obrigatório.',
                'numeric' => 'O campo desconto deve ser um número.',
                'min' => 'O campo desconto deve ser no mínimo 0.',
                'max' => 'O campo desconto não pode ser maior que 99.99.',
            ],
            'familia_id' => [
                'required' => 'O campo família é obrigatório.',
                'exists' => 'A família selecionada é inválida.',
            ],
            'acabamento_id' => [
                'required' => 'O campo acabamento é obrigatório.',
                'exists' => 'O acabamento selecionado é inválido.',
            ],
            'tela_id' => [
                'required' => 'O campo tela é obrigatório.',
                'exists' => 'A tela selecionada é inválida.',
            ],
            'produtoBase' => [
                'required' => 'O campo produto base é obrigatório.',
                'boolean' => 'O campo produto base deve ser verdadeiro ou falso.',
            ],
            'produtoBase_id' => [
                'nullable' => 'O campo produto base é opcional.',
                'exists' => 'O produto base selecionado é inválido.',
            ],
            'especificacao' => [
                'string' => 'O campo especificação deve ser um texto.',
            ],
            'observacao' => [
                'string' => 'O campo observação deve ser um texto.',
            ],
            'permitirVenda' => [
                'required' => 'O campo permitir venda é obrigatório.',
                'boolean' => 'O campo permitir venda deve ser verdadeiro ou falso.',
            ],
            'permitirTela' => [
                'required' => 'O campo permitir tela é obrigatório.',
                'boolean' => 'O campo permitir tela deve ser verdadeiro ou falso.',
            ],
            'codigoBarras' => [
                'string' => 'O campo código de barras deve ser um texto.',
                'max' => 'O campo código de barras não pode ter mais de 50 caracteres.',
            ],
            'ncm' => [
                'integer' => 'O campo NCM deve ser um número inteiro.',
                'min' => 'O campo NCM deve ser no mínimo 0.',
            ],
            'cst' => [
                'integer' => 'O campo CST deve ser um número inteiro.',
                'min' => 'O campo CST deve ser no mínimo 0.',
            ],
            'cest' => [
                'integer' => 'O campo CEST deve ser um número inteiro.',
                'min' => 'O campo CEST deve ser no mínimo 0.',
            ],
            'origem_id' => [
                'nullable' => 'O campo origem é opcional.',
                'exists' => 'A origem selecionada é inválida.',
            ],
        ],
    ],

    'discount_rule' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 50 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'periodo' => [
                'required' => 'O campo período é obrigatório.',
                'integer' => 'O campo período deve ser um número inteiro.',
                'min' => 'O campo período deve ser no mínimo 0.',
            ],
            'valorBase' => [
                'required' => 'O campo valor base é obrigatório.',
                'numeric' => 'O campo valor base deve ser um número.',
                'min' => 'O campo valor base deve ser no mínimo 0.',
            ],
            'descontoAcrescido' => [
                'required' => 'O campo desconto acrescido é obrigatório.',
                'numeric' => 'O campo desconto acrescido deve ser um número.',
                'min' => 'O campo desconto acrescido deve ser no mínimo 0.',
                'max' => 'O campo desconto acrescido não pode ser maior que 99.99.',
            ],
        ],
    ],

    'price_table' => [
        'validation' => [
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 50 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'vigenciaAte' => [
                'nullable' => 'O campo vigência até é opcional.',
                'date' => 'O campo vigência até deve ser uma data válida.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
        ],
    ],

    'product_origin' => [
        'validation' => [
            'codigo' => [
                'required' => 'O campo código é obrigatório.',
                'string' => 'O campo código deve ser um texto.',
                'max' => 'O campo código não pode ter mais de 3 caracteres.',
                'unique' => 'Este código já está sendo utilizado.',
            ],
            'descricao' => [
                'required' => 'O campo descrição é obrigatório.',
                'string' => 'O campo descrição deve ser um texto.',
                'max' => 'O campo descrição não pode ter mais de 100 caracteres.',
                'unique' => 'Esta descrição já está sendo utilizada.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
        ],
    ],

    // Cliente
    'customer' => [
        'validation' => [
            // Campos gerais
            'tipoCliente_id' => [
                'required' => 'O campo tipo de cliente é obrigatório.',
                'exists' => 'O tipo de cliente selecionado é inválido.',
            ],
            'nome' => [
                'required' => 'O campo nome/razão social é obrigatório.',
                'string' => 'O campo nome/razão social deve ser uma string.',
                'max' => 'O campo nome/razão social não pode ter mais de 80 caracteres.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
            'nomePreferencia' => [
                'string' => 'O campo nome de preferência deve ser uma string.',
                'max' => 'O campo nome de preferência não pode ter mais de 80 caracteres.',
            ],
            'origem_id' => [
                'exists' => 'A origem selecionada é inválida.',
            ],
            'outra_origem' => [
                'string' => 'O campo outra origem deve ser uma string.',
                'max' => 'O campo outra origem não pode ter mais de 50 caracteres.',
            ],
            'data_cadastro' => [
                'date' => 'O campo data de cadastro deve ser uma data válida.',
            ],
            'limite_credito' => [
                'numeric' => 'O campo limite de crédito deve ser um número.',
                'min' => 'O campo limite de crédito deve ser no mínimo 0.',
                'max' => 'O campo limite de crédito não pode ser maior que 99.999.999,99.',
            ],
            'data_limite_credite' => [
                'date' => 'O campo data limite de crédito deve ser uma data válida.',
                'after_or_equal' => 'A data limite de crédito deve ser igual ou posterior à data de cadastro.',
            ],
            'observacoes' => [
                'string' => 'O campo observações deve ser uma string.',
                'max' => 'O campo observações não pode ter mais de 150 caracteres.',
            ],
            // Campos Pessoa Física
            'cpf' => [
                'required' => 'O campo CPF é obrigatório.',
                'string' => 'O campo CPF deve ser uma string.',
                'formato_cpf' => 'O formato do CPF é inválido.',
                'cpf' => 'O CPF informado é inválido.',
                'unique' => 'O CPF informado já está cadastrado.',
            ],
            'dataNascimento' => [
                'date' => 'O campo data de nascimento deve ser uma data válida.',
                'before' => 'A data de nascimento deve ser anterior à data atual.',
            ],
            'rg' => [
                'string' => 'O campo RG deve ser uma string.',
                'max' => 'O campo RG não pode ter mais de 20 caracteres.',
            ],
            'rgOrgaoEmissor' => [
                'string' => 'O campo órgão emissor RG deve ser uma string.',
                'max' => 'O campo órgão emissor RG não pode ter mais de 20 caracteres.',
            ],
            // Campos Pessoa Jurídica
            'cnpj' => [
                'required' => 'O campo CNPJ é obrigatório.',
                'string' => 'O campo CNPJ deve ser uma string.',
                'formato_cnpj' => 'O formato do CNPJ é inválido.',
                'cnpj' => 'O CNPJ informado é inválido.',
                'unique' => 'O CNPJ informado já está cadastrado.',
            ],
            'nomeFantasia' => [
                'string' => 'O campo nome fantasia deve ser uma string.',
                'max' => 'O campo nome fantasia não pode ter mais de 80 caracteres.',
            ],
            'inscricaoEstadual' => [
                'string' => 'O campo inscrição estadual deve ser uma string.',
                'max' => 'O campo inscrição estadual não pode ter mais de 14 caracteres.',
            ],
            'inscricaoMunicipal' => [
                'string' => 'O campo inscrição municipal deve ser uma string.',
                'max' => 'O campo inscrição municipal não pode ter mais de 14 caracteres.',
            ],
            'optanteSimples_id' => [
                'exists' => 'A opção de simples nacional selecionada é inválida.',
            ],
            'responsavelNome' => [
                'string' => 'O campo nome do responsável deve ser uma string.',
                'max' => 'O campo nome do responsável não pode ter mais de 80 caracteres.',
            ],
            'responsavelCpf' => [
                'string' => 'O campo CPF do responsável deve ser uma string.',
                'formato_cpf' => 'O formato do CPF do responsável é inválido.',
                'cpf' => 'O CPF do responsável informado é inválido.',
            ],
            'responsavelRg' => [
                'string' => 'O campo RG do responsável deve ser uma string.',
                'max' => 'O campo RG do responsável não pode ter mais de 20 caracteres.',
            ],
            'responsavelRgOrgaoEmissor' => [
                'string' => 'O campo órgão emissor RG do responsável deve ser uma string.',
                'max' => 'O campo órgão emissor RG do responsável não pode ter mais de 20 caracteres.',
            ],
            // Validação de endereços
            'endereco' => [
                'tipoEndereco_id' => [
                    'required' => 'O campo tipo de endereço é obrigatório.',
                    'exists' => 'O tipo de endereço selecionado é inválido.',
                ],
                'cep' => [
                    'required' => 'O campo CEP é obrigatório.',
                    'string' => 'O campo CEP deve ser uma string.',
                    'formato_cep' => 'O formato do CEP é inválido.',
                ],
                'logradouro' => [
                    'required' => 'O campo logradouro é obrigatório.',
                    'string' => 'O campo logradouro deve ser uma string.',
                    'max' => 'O campo logradouro não pode ter mais de 100 caracteres.',
                ],
                'numero' => [
                    'required' => 'O campo número é obrigatório.',
                    'string' => 'O campo número deve ser uma string.',
                    'max' => 'O campo número não pode ter mais de 10 caracteres.',
                ],
                'bairro' => [
                    'required' => 'O campo bairro é obrigatório.',
                    'string' => 'O campo bairro deve ser uma string.',
                    'max' => 'O campo bairro não pode ter mais de 50 caracteres.',
                ],
                'cidade' => [
                    'required' => 'O campo cidade é obrigatório.',
                    'string' => 'O campo cidade deve ser uma string.',
                    'max' => 'O campo cidade não pode ter mais de 50 caracteres.',
                ],
                'estado' => [
                    'required' => 'O campo estado é obrigatório.',
                    'string' => 'O campo estado deve ser uma string.',
                    'size' => 'O campo estado deve ter exatamente 2 caracteres.',
                ],
                'pais' => [
                    'string' => 'O campo país deve ser uma string.',
                    'max' => 'O campo país não pode ter mais de 50 caracteres.',
                ],
                'complemento' => [
                    'string' => 'O campo complemento deve ser uma string.',
                    'max' => 'O campo complemento não pode ter mais de 50 caracteres.',
                ],
            ],
            // Validação de contatos
            'contato' => [
                'tipoContato_id' => [
                    'required' => 'O campo tipo de contato é obrigatório.',
                    'exists' => 'O tipo de contato selecionado é inválido.',
                ],
                'telefone' => [
                    'string' => 'O campo telefone deve ser uma string.',
                    'max' => 'O campo telefone não pode ter mais de 15 caracteres.',
                ],
                'contato' => [
                    'required' => 'O campo nome do contato é obrigatório.',
                    'string' => 'O campo nome do contato deve ser uma string.',
                    'max' => 'O campo nome do contato não pode ter mais de 100 caracteres.',
                ],
                'email' => [
                    'email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
                    'max' => 'O campo e-mail não pode ter mais de 50 caracteres.',
                ],
                'observacoes' => [
                    'string' => 'O campo observações do contato deve ser uma string.',
                    'max' => 'O campo observações do contato não pode ter mais de 100 caracteres.',
                ],
            ],
        ],
    ],

    'transportadora' => [
        'validation' => [
            'nomeFantasia' => [
                'required' => 'O campo nome fantasia é obrigatório.',
                'string' => 'O campo nome fantasia deve ser uma string.',
                'max' => 'O campo nome fantasia não pode ter mais de 80 caracteres.',
            ],
            'razaoSocial' => [
                'required' => 'O campo razão social é obrigatório.',
                'string' => 'O campo razão social deve ser uma string.',
                'max' => 'O campo razão social não pode ter mais de 80 caracteres.',
            ],
            'cnpj' => [
                'required' => 'O campo CNPJ é obrigatório.',
                'string' => 'O campo CNPJ deve ser uma string.',
                'size' => 'O campo CNPJ deve ter exatamente 14 caracteres.',
                'unique' => 'O CNPJ informado já está cadastrado.',
            ],
            'situacao_id' => [
                'required' => 'O campo situação é obrigatório.',
                'exists' => 'A situação selecionada é inválida.',
            ],
            'observacao' => [
                'string' => 'O campo observação deve ser uma string.',
            ],
            'estadosAtendidos' => [
                'array' => 'O campo estados atendidos deve ser um array.',
            ],
            'estadosAtendidos.*' => [
                'string' => 'Cada estado deve ser uma string.',
                'size' => 'Cada estado deve ter exatamente 2 caracteres.',
            ],
        ],
    ],

    'revenda' => [
        'validation' => [
            'tipoRevenda_id' => [
                'required' => 'O campo Tipo de Revenda é obrigatório.',
                'exists' => 'O Tipo de Revenda selecionado é inválido.',
            ],
            'nomeFantasia' => [
                'required' => 'O campo Nome Fantasia é obrigatório.',
                'string' => 'O Nome Fantasia deve ser um texto.',
                'max' => 'O Nome Fantasia não pode exceder 80 caracteres.',
            ],
            'razaoSocial' => [
                'required' => 'O campo Razão Social é obrigatório.',
                'string' => 'A Razão Social deve ser um texto.',
                'max' => 'A Razão Social não pode exceder 80 caracteres.',
            ],
            'matrizRevenda_id' => [
                'exists' => 'A Matriz Revenda selecionada é inválida.',
            ],
            'dataCriacao' => [
                'required' => 'O campo Data de Criação é obrigatório.',
                'date' => 'A Data de Criação deve ser uma data válida.',
            ],
            'cnpj' => [
                'required' => 'O campo CNPJ é obrigatório.',
                'string' => 'O CNPJ deve ser um texto.',
                'size' => 'O CNPJ deve ter exatamente 14 dígitos.',
                'unique' => 'Este CNPJ já está em uso.',
            ],
            'inscricaoEstadual' => [
                'required' => 'O campo Inscrição Estadual é obrigatório.',
                'string' => 'A Inscrição Estadual deve ser um texto.',
                'max' => 'A Inscrição Estadual não pode exceder 20 caracteres.',
            ],
            'inscricaoMunicipal' => [
                'required' => 'O campo Inscrição Municipal é obrigatório.',
                'string' => 'A Inscrição Municipal deve ser um texto.',
                'max' => 'A Inscrição Municipal não pode exceder 20 caracteres.',
            ],
            'optanteSimples_id' => [
                'required' => 'O campo Optante pelo Simples é obrigatório.',
                'exists' => 'O Optante pelo Simples selecionado é inválido.',
            ],
            'responsavelNome' => [
                'string' => 'O Nome do Responsável deve ser um texto.',
                'max' => 'O Nome do Responsável não pode exceder 50 caracteres.',
            ],
            'responsavelRg' => [
                'string' => 'O RG do Responsável deve ser um texto.',
                'max' => 'O RG do Responsável não pode exceder 15 caracteres.',
            ],
            'responsavelRgOrgaoEmissor' => [
                'string' => 'O Órgão Emissor do RG deve ser um texto.',
                'max' => 'O Órgão Emissor do RG não pode exceder 15 caracteres.',
            ],
            'responsavelCpf' => [
                'string' => 'O CPF do Responsável deve ser um texto.',
                'size' => 'O CPF do Responsável deve ter exatamente 11 dígitos.',
            ],
            'observacao' => [
                'string' => 'A Observação deve ser um texto.',
            ],
            'situacao_id' => [
                'required' => 'O campo Situação é obrigatório.',
                'exists' => 'A Situação selecionada é inválida.',
            ],
            'nivelDesconto' => [
                'integer' => 'O Nível de Desconto deve ser um número inteiro.',
            ],
            'dataUltimaAlteracaoDesconto' => [
                'date' => 'A Data da Última Alteração de Desconto deve ser uma data válida.',
            ],
            'descontoInicial' => [
                'numeric' => 'O Desconto Inicial deve ser um número.',
                'min' => 'O Desconto Inicial deve ser no mínimo 0.',
                'max' => 'O Desconto Inicial não pode exceder 100.',
            ],
            'descontoMaximo' => [
                'numeric' => 'O Desconto Máximo deve ser um número.',
                'min' => 'O Desconto Máximo deve ser no mínimo 0.',
                'max' => 'O Desconto Máximo não pode exceder 100.',
            ],
            'descontoAtual' => [
                'numeric' => 'O Desconto Atual deve ser um número.',
                'min' => 'O Desconto Atual deve ser no mínimo 0.',
                'max' => 'O Desconto Atual não pode exceder 100.',
            ],
            'token' => [
                'string' => 'O Token deve ser um texto.',
                'max' => 'O Token não pode exceder 255 caracteres.',
            ],
            'dataAberturaRevenda' => [
                'required' => 'O campo Data de Abertura da Revenda é obrigatório.',
                'date' => 'A Data de Abertura da Revenda deve ser uma data válida.',
            ],
            'ramoAtividade' => [
                'required' => 'O campo Ramo de Atividade é obrigatório.',
                'string' => 'O Ramo de Atividade deve ser um texto.',
                'max' => 'O Ramo de Atividade não pode exceder 100 caracteres.',
            ],
            'tipoRegime_id' => [
                'required' => 'O campo Tipo de Regime é obrigatório.',
                'exists' => 'O Tipo de Regime selecionado é inválido.',
            ],
            'fornecedoresAudio' => [
                'string' => 'Os Fornecedores de Áudio devem ser um texto.',
            ],
            'fornecedoresVideo' => [
                'string' => 'Os Fornecedores de Vídeo devem ser um texto.',
            ],
            'fornecedoresAutomacao' => [
                'string' => 'Os Fornecedores de Automação devem ser um texto.',
            ],
            'tipoShowroom_id' => [
                'required' => 'O campo Tipo de Showroom é obrigatório.',
                'exists' => 'O Tipo de Showroom selecionado é inválido.',
            ],
            'areaExposicao' => [
                'required' => 'O campo Área de Exposição é obrigatório.',
                'numeric' => 'A Área de Exposição deve ser um número.',
                'min' => 'A Área de Exposição deve ser no mínimo 0.',
            ],
            'temJardim' => [
                'required' => 'O campo Tem Jardim é obrigatório.',
                'boolean' => 'O valor de Tem Jardim deve ser sim ou não.',
            ],
            'descontoVitalicio' => [
                'required' => 'O campo Desconto Vitalício é obrigatório.',
                'boolean' => 'O valor de Desconto Vitalício deve ser sim ou não.',
            ],
            'dataAprovacaoCadastro' => [
                'required' => 'O campo Data de Aprovação do Cadastro é obrigatório.',
                'date' => 'A Data de Aprovação do Cadastro deve ser uma data válida.',
            ],
            'origemCadastro_id' => [
                'required' => 'O campo Origem do Cadastro é obrigatório.',
                'exists' => 'A Origem do Cadastro selecionada é inválida.',
            ],
        ],
    ],
];
