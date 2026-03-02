<?php

return [
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
    
    // Comissão
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
];
