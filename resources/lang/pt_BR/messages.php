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
                'max' => 'O campo descrição não pode ter mais de 255 caracteres.',
            ],
            'icone' => [
                'string' => 'O campo ícone deve ser um texto.',
                'max' => 'O campo ícone não pode ter mais de 255 caracteres.',
            ],
            'rota' => [
                'required' => 'O campo rota é obrigatório.',
                'string' => 'O campo rota deve ser um texto.',
                'max' => 'O campo rota não pode ter mais de 255 caracteres.',
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
];
