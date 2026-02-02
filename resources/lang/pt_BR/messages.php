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
                'unique' => 'Este e-mail já está em uso.',
            ],
            'password' => [
                'required' => 'O campo senha é obrigatório.',
                'string' => 'O campo senha deve ser um texto.',
                'min' => 'A senha deve ter pelo menos 8 caracteres.',
                'confirmed' => 'A confirmação de senha não confere.',
            ],
            'admin' => [
                'required' => 'O campo administrador é obrigatório.',
                'integer' => 'O campo administrador deve ser um número.',
                'in' => 'Selecione uma opção válida para administrador.',
            ],
            'avatar' => [
                'mimes' => 'O avatar deve ser uma imagem nos formatos: JPEG, PNG, JPG, GIF ou WebP.',
                'max' => 'O avatar não pode ter mais de 2MB.',
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
];
