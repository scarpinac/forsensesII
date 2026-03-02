<?php

return [
    // Títulos e cabeçalhos
    'notification' => [
        'title' => [
            'index' => 'Notificações',
            'create' => 'Nova Notificação',
            'edit' => 'Editar Notificação',
            'show' => 'Visualizar Notificação',
            'destroy' => 'Excluir Notificação',
            'history' => 'Histórico da Notificação',
        ],
        'breadcrumb' => [
            'home' => 'Início',
            'listing' => 'Lista de Notificações',
        ],
        // Campos do formulário
        'title' => 'Título',
        'message' => 'Mensagem',
        'menssage_placeholder' => 'Digite a mensagem da notificação...',
        'notification_type' => 'Tipo de Notificação',
        'icon' => 'Ícone',
        'icon_help' => 'Nome do ícone FontAwesome (ex: fas fa-bell)',
        'send_at' => 'Data/Hora de Envio',
        'send_at_help' => 'Data e hora em que a notificação será enviada',
        'destiny' => 'Destinatários',
        'select_destiny' => 'Selecione o tipo de destinatário',
        'destino_todos' => 'Todos os Usuários',
        'destiny_users' => 'Usuários Específicos',
        'destiny_profiles' => 'Perfis Específicos',
        'usuarios' => 'Usuários',
        'usuarios_help' => 'Selecione os usuários que receberão a notificação',
        'perfis' => 'Perfis',
        'perfis_help' => 'Selecione os perfis que receberão a notificação',
        'sendTo' => 'Enviar Para',
        'sended' => 'Enviado',
        'expiredAt' => 'Data de Expiração',
        'actions' => 'Ações',
        // Botões e ações
        'create' => 'Nova Notificação',
        'edit' => 'Editar',
        'show' => 'Visualizar',
        'destroy' => 'Excluir',
        'history' => 'Histórico',
        'back' => 'Voltar',
        'save' => 'Salvar',
        // Histórico
        'history' => [
            'data' => [
                'title' => 'Dados da Notificação',
            ],
            'changes' => [
                'title' => 'Histórico de Alterações',
            ],
            'table' => [
                'date' => 'Data',
                'user' => 'Usuário',
                'type' => 'Tipo de Alteração',
                'actions' => 'Ações',
            ],
            'button' => [
                'details' => 'Detalhes',
            ],
            'fields' => [
                'id' => 'ID',
                'descricao' => 'Descrição',
                'created_at' => 'Data de Criação',
                'updated_at' => 'Data de Atualização',
                'deleted_at' => 'Data de Exclusão',
            ],
        ],
        // Modal
        'modal' => [
            'details' => [
                'title' => 'Detalhes da Alteração',
            ],
            'close' => 'Fechar',
        ],
        // Mensagens
        'no' => [
            'records' => 'Nenhuma notificação encontrada.',
            'history' => 'Nenhum histórico encontrado.',
        ],
    ],
    
    // Comissão
    'commission' => [
        'title' => [
            'index' => 'Comissões',
            'create' => 'Nova Comissão',
            'edit' => 'Editar Comissão',
            'show' => 'Visualizar Comissão',
            'destroy' => 'Excluir Comissão',
            'history' => 'Histórico da Comissão',
        ],
        'breadcrumb' => [
            'home' => 'Início',
            'listing' => 'Lista de Comissões',
        ],
        // Campos do formulário
        'value' => 'Valor',
        'form' => [
            'situation' => 'Tipo de Comissão',
        ],
        // Botões e ações
        'create' => 'Nova Comissão',
        'edit' => 'Editar',
        'show' => 'Visualizar',
        'destroy' => 'Excluir',
        'save' => 'Salvar',
        'save_changes' => 'Salvar Alterações',
        'back' => 'Voltar',
        'actions' => 'Ações',
        // Mensagens de sucesso
        'success' => [
            'created' => 'Comissão criada com sucesso!',
            'updated' => 'Comissão atualizada com sucesso!',
            'deleted' => 'Comissão excluída com sucesso!',
        ],
        // Mensagens de erro
        'error' => [
            'not_created' => 'Erro ao criar comissão.',
            'not_updated' => 'Erro ao atualizar comissão.',
            'not_deleted' => 'Erro ao excluir comissão.',
        ],
        // Mensagens gerais
        'no' => [
            'records' => 'Nenhuma comissão encontrada.',
            'history' => 'Nenhum histórico encontrado.',
        ],
        // Histórico
        'history' => [
            'data' => [
                'title' => 'Dados da Comissão',
            ],
            'changes' => [
                'title' => 'Histórico de Alterações',
            ],
            'table' => [
                'date' => 'Data',
                'user' => 'Usuário',
                'type' => 'Tipo de Alteração',
                'actions' => 'Ações',
            ],
            'button' => [
                'details' => 'Detalhes',
            ],
            'fields' => [
                'id' => 'ID',
                'descricao' => 'Descrição',
                'icone' => 'Ícone',
                'rota' => 'Rota',
                'comissaoPai_id' => 'Comissão Pai',
                'permissao_id' => 'Permissão',
                'situacao_id' => 'Situação',
                'created_at' => 'Data de Criação',
                'updated_at' => 'Data de Atualização',
                'deleted_at' => 'Data de Exclusão',
            ],
        ],
        // Modal
        'modal' => [
            'details' => [
                'title' => 'Detalhes da Alteração',
            ],
            'close' => 'Fechar',
        ],
    ],
    
    // Cliente
    'customer' => [
        'title' => [
            'index' => 'Clientes',
            'create' => 'Novo Cliente',
            'edit' => 'Editar Cliente',
            'show' => 'Visualizar Cliente',
            'destroy' => 'Excluir Cliente',
            'history' => 'Histórico do Cliente',
        ],
        'breadcrumb' => [
            'home' => 'Início',
            'listing' => 'Lista de Clientes',
        ],
        // Campos do formulário
        'form' => [
            'type' => 'Tipo de Cliente',
            'name' => 'Nome/Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'cpf' => 'CPF',
            'cnpj' => 'CNPJ',
            'birth_date' => 'Data de Nascimento',
            'rg' => 'RG',
            'rg_issuer' => 'Órgão Emissor RG',
            'state_registration' => 'Inscrição Estadual',
            'municipal_registration' => 'Inscrição Municipal',
            'simple_option' => 'Optante Simples Nacional',
            'responsible_name' => 'Nome do Responsável',
            'responsible_cpf' => 'CPF do Responsável',
            'responsible_rg' => 'RG do Responsável',
            'responsible_rg_issuer' => 'Órgão Emissor RG Responsável',
            'preference_name' => 'Nome de Preferência',
            'origin' => 'Origem do Cliente',
            'other_origin' => 'Outra Origem',
            'registration_date' => 'Data de Cadastro',
            'credit_limit' => 'Limite de Crédito',
            'credit_limit_date' => 'Data Limite de Crédito',
            'situation' => 'Situação',
            'observations' => 'Observações',
        ],
        // Endereços
        'address' => [
            'title' => 'Endereços',
            'add' => 'Adicionar Endereço',
            'remove' => 'Remover Endereço',
            'type' => 'Tipo de Endereço',
            'zip_code' => 'CEP',
            'street' => 'Logradouro',
            'number' => 'Número',
            'complement' => 'Complemento',
            'neighborhood' => 'Bairro',
            'city' => 'Cidade',
            'state' => 'Estado',
            'country' => 'País',
        ],
        // Contatos
        'contact' => [
            'title' => 'Contatos',
            'add' => 'Adicionar Contato',
            'remove' => 'Remover Contato',
            'type' => 'Tipo de Contato',
            'name' => 'Nome do Contato',
            'phone' => 'Telefone',
            'email' => 'E-mail',
            'observations' => 'Observações',
        ],
        // Botões e ações
        'create' => 'Novo Cliente',
        'edit' => 'Editar',
        'show' => 'Visualizar',
        'destroy' => 'Excluir',
        'save' => 'Salvar',
        'save_changes' => 'Salvar Alterações',
        'back' => 'Voltar',
        'actions' => 'Ações',
        'type' => 'Tipo de Cliente',
        'name' => 'Nome/Razão Social',
        'preferred_name' => 'Nome de Preferência',
        'trade_name' => 'Nome Fantasia',
        'cpf' => 'CPF',
        'cnpj' => 'CNPJ',
        'birth_date' => 'Data de Nascimento',
        'rg' => 'RG',
        'rg_issuer' => 'Órgão Emissor RG',
        'state_registration' => 'Inscrição Estadual',
        'city_registration' => 'Inscrição Municipal',
        'simple_option' => 'Optante Simples Nacional',
        'responsible' => 'Responsável',
        'responsible_name' => 'Nome do Responsável',
        'responsible_cpf' => 'CPF do Responsável',
        'responsible_rg' => 'RG do Responsável',
        'responsible_rg_issuer' => 'Órgão Emissor RG Responsável',
        'situation' => 'Situação',
        'origin' => 'Origem do Cliente',
        'other_origin' => 'Outra Origem',
        'registration_date' => 'Data de Cadastro',
        'credit_limit' => 'Limite de Crédito',
        'credit_limit_date' => 'Data Limite de Crédito',
        'observations' => 'Observações',
        'addresses' => 'Endereços',
        'contacts' => 'Contatos',
        'add_address' => 'Adicionar Endereço',
        'add_contact' => 'Adicionar Contato',
        'address_type' => 'Tipo de Endereço',
        'zip_code' => 'CEP',
        'street' => 'Logradouro',
        'number' => 'Número',
        'complement' => 'Complemento',
        'neighborhood' => 'Bairro',
        'city' => 'Cidade',
        'state' => 'Estado',
        'country' => 'País',
        'contact_type' => 'Tipo de Contato',
        'phone' => 'Telefone',
        'contact_value' => 'Nome do Contato',
        'email' => 'E-mail',
        'contact_observations' => 'Observações do Contato',
        'contact_observations_placeholder' => 'Digite observações sobre este contato...',
        'select' => 'Selecione...',
        'zip_code_placeholder' => '00000-000',
        'phone_placeholder' => '(00) 00000-0000',
        // Mensagens de sucesso
        'success' => [
            'created' => 'Cliente criado com sucesso!',
            'updated' => 'Cliente atualizado com sucesso!',
            'deleted' => 'Cliente excluído com sucesso!',
        ],
        // Mensagens de erro
        'error' => [
            'not_created' => 'Erro ao criar cliente.',
            'not_updated' => 'Erro ao atualizar cliente.',
            'not_deleted' => 'Erro ao excluir cliente.',
        ],
        // Mensagens gerais
        'no' => [
            'records' => 'Nenhum cliente encontrado.',
            'history' => 'Nenhum histórico encontrado.',
        ],
        // Histórico
        'history' => [
            'data' => [
                'title' => 'Dados do Cliente',
            ],
            'changes' => [
                'title' => 'Histórico de Alterações',
            ],
            'table' => [
                'date' => 'Data',
                'user' => 'Usuário',
                'type' => 'Tipo de Alteração',
                'actions' => 'Ações',
            ],
            'button' => [
                'details' => 'Detalhes',
            ],
            'fields' => [
                'id' => 'ID',
                'revenda_id' => 'Revenda',
                'tipoCliente_id' => 'Tipo de Cliente',
                'nome' => 'Nome/Razão Social',
                'nomeFantasia' => 'Nome Fantasia',
                'cpf' => 'CPF',
                'cnpj' => 'CNPJ',
                'dataNascimento' => 'Data de Nascimento',
                'rg' => 'RG',
                'rgOrgaoEmissor' => 'Órgão Emissor RG',
                'inscricaoEstadual' => 'Inscrição Estadual',
                'inscricaoMunicipal' => 'Inscrição Municipal',
                'optanteSimples_id' => 'Optante Simples Nacional',
                'responsavelNome' => 'Nome do Responsável',
                'responsavelCpf' => 'CPF do Responsável',
                'responsavelRg' => 'RG do Responsável',
                'responsavelRgOrgaoEmissor' => 'Órgão Emissor RG Responsável',
                'nomePreferencia' => 'Nome de Preferência',
                'origem_id' => 'Origem do Cliente',
                'outra_origem' => 'Outra Origem',
                'data_cadastro' => 'Data de Cadastro',
                'limite_credito' => 'Limite de Crédito',
                'data_limite_credite' => 'Data Limite de Crédito',
                'situacao_id' => 'Situação',
                'observacoes' => 'Observações',
                'created_at' => 'Data de Criação',
                'updated_at' => 'Data de Atualização',
                'deleted_at' => 'Data de Exclusão',
            ],
        ],
        // Modal
        'modal' => [
            'details' => [
                'title' => 'Detalhes da Alteração',
            ],
            'close' => 'Fechar',
        ],
    ],
];
