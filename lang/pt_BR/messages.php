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
];
