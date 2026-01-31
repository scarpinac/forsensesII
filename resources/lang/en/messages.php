<?php

return [
    'welcome' => 'Welcome to the system',
    'no_records' => 'No records found',
    'confirm_delete' => 'Are you sure you want to delete?',
    
    // Menu Form Request Validation Messages
    'menu' => [
        'validation' => [
            'descricao' => [
                'required' => 'The description field is required.',
                'string' => 'The description field must be a string.',
                'max' => 'The description field may not be greater than 255 characters.',
            ],
            'icone' => [
                'string' => 'The icon field must be a string.',
                'max' => 'The icon field may not be greater than 255 characters.',
            ],
            'rota' => [
                'required' => 'The route field is required.',
                'string' => 'The route field must be a string.',
                'max' => 'The route field may not be greater than 255 characters.',
            ],
            'menuPai_id' => [
                'exists' => 'The selected parent menu is invalid.',
            ],
            'permissao_id' => [
                'required' => 'The permission field is required.',
                'exists' => 'The selected permission is invalid.',
            ],
            'situacao_id' => [
                'required' => 'The situation field is required.',
                'exists' => 'The selected situation is invalid.',
            ],
        ],
    ],
];
