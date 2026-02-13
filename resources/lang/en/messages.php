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
                'max' => 'The description field may not be greater than 25 characters.',
            ],
            'icone' => [
                'string' => 'The icon field must be a string.',
                'max' => 'The icon field may not be greater than 50 characters.',
            ],
            'rota' => [
                'required' => 'The route field is required.',
                'string' => 'The route field must be a string.',
                'max' => 'The route field may not be greater than 80 characters.',
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
    
    // Permission Form Request Validation Messages
    'permission' => [
        'validation' => [
            'descricao' => [
                'required' => 'The description field is required.',
                'string' => 'The description field must be a string.',
                'max' => 'The description field may not be greater than 80 characters.',
            ],
        ],
    ],

    // API Form Request Validation Messages
    'api' => [
        'validation' => [
            'api_id' => [
                'required' => 'The API type field is required.',
                'exists' => 'The selected API type is invalid.',
            ],
            'credencial' => [
                'required' => 'The credential field is required.',
                'string' => 'The credential field must be a string.',
            ],
            'situacao_id' => [
                'required' => 'The situation field is required.',
                'exists' => 'The selected situation is invalid.',
            ],
        ],
    ],

    // Parameter Form Request Validation Messages
    'parametro' => [
        'validation' => [
            'nome' => [
                'required' => 'The name field is required.',
                'string' => 'The name field must be a string.',
                'max' => 'The name field may not be greater than 80 characters.',
            ],
            'descricao' => [
                'required' => 'The description field is required.',
                'string' => 'The description field must be a string.',
            ],
            'tipo_id' => [
                'required' => 'The type field is required.',
                'exists' => 'The selected type is invalid.',
            ],
            'valor' => [
                'required' => 'The value field is required.',
                'string' => 'The value field must be a string.',
            ],
        ],
    ],
    'usuario' => [
        'validation' => [
            'name' => [
                'required' => 'The name field is required.',
                'string' => 'The name field must be a string.',
                'max' => 'The name field may not be greater than 255 characters.',
            ],
            'email' => [
                'required' => 'The e-mail field is required.',
                'string' => 'The e-mail field must be a string.',
                'email' => 'The e-mail field must be a valid e-mail address.',
                'max' => 'The e-mail field may not be greater than 255 characters.',
                'unique' => 'This e-mail is already in use.',
            ],
            'password' => [
                'required' => 'The password field is required.',
                'string' => 'The password field must be a string.',
                'min' => 'The password must be at least 8 characters.',
                'confirmed' => 'The password confirmation does not match.',
            ],
            'admin' => [
                'required' => 'The administrator field is required.',
                'integer' => 'The administrator field must be an integer.',
                'in' => 'Please select a valid option for administrator.',
            ],
            'avatar' => [
                'mimes' => 'The avatar must be an image in formats: JPEG, PNG, JPG, GIF or WebP.',
                'max' => 'The avatar may not be greater than 2MB.',
            ],
            'situacao_id' => [
                'required' => 'The situation field is required.',
                'exists' => 'The selected situation is invalid.',
            ],
        ],
    ],
    
    // Access Level Form Request Validation Messages
    'access_level' => [
        'validation' => [
            'descricao' => [
                'required' => 'The description field is required.',
                'string' => 'The description field must be a string.',
                'max' => 'The description field may not be greater than 80 characters.',
            ],
            'permissoes' => [
                'array' => 'The permissions field must be an array.',
                'integer' => 'Each permission must be an integer.',
                'exists' => 'One of the selected permissions is invalid.',
            ],
        ],
    ],
    
    // Notification Form Request Validation Messages
    'notification' => [
        'validation' => [
            'title' => [
                'required' => 'The title field is required.',
                'string' => 'The title field must be a string.',
                'max' => 'The title field may not be greater than 50 characters.',
            ],
            'message' => [
                'required' => 'The message field is required.',
                'string' => 'The message field must be a string.',
            ],
            'notification_type' => [
                'required' => 'The notification type field is required.',
                'exists' => 'The selected notification type is invalid.',
            ],
            'sent_notification_to' => [
                'required' => 'The recipient field is required.',
                'exists' => 'The selected recipient is invalid.',
            ],
            'icon' => [
                'string' => 'The icon field must be a string.',
                'max' => 'The icon field may not be greater than 30 characters.',
            ],
            'send_at' => [
                'required' => 'The send date/time field is required.',
                'date' => 'The send date/time field must be a valid date.',
                'after' => 'The send date/time must be after the current date/time.',
            ],
            'expired_at' => [
                'date' => 'The expiration date field must be a valid date.',
                'after' => 'The expiration date must be after the current date/time.',
            ],
            'send_to' => [
                'required' => 'The send to field is required.',
                'string' => 'The send to field must be a string.',
            ],
            'users' => [
                'required_if' => 'The users field is required when recipient is "Specific Users".',
                'array' => 'The users field must be an array.',
                'exists' => 'One or more selected users are invalid.',
            ],
            'profiles' => [
                'required_if' => 'The profiles field is required when recipient is "Specific Profiles".',
                'array' => 'The profiles field must be an array.',
                'exists' => 'One or more selected profiles are invalid.',
            ],
        ],
    ],
];
