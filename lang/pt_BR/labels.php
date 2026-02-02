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
];
