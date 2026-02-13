<?php

return [
    'welcome' => 'Bienvenido al sistema',
    'no_records' => 'No se encontraron registros',
    'confirm_delete' => '¿Está seguro de que desea eliminar?',
    
    // Menu Form Request Validation Messages
    'menu' => [
        'validation' => [
            'descricao' => [
                'required' => 'El campo descripción es obligatorio.',
                'string' => 'El campo descripción debe ser una cadena de texto.',
                'max' => 'El campo descripción no puede tener más de 25 caracteres.',
            ],
            'icone' => [
                'string' => 'El campo icono debe ser una cadena de texto.',
                'max' => 'El campo icono no puede tener más de 50 caracteres.',
            ],
            'rota' => [
                'required' => 'El campo ruta es obligatorio.',
                'string' => 'El campo ruta debe ser una cadena de texto.',
                'max' => 'El campo ruta no puede tener más de 80 caracteres.',
            ],
            'menuPai_id' => [
                'exists' => 'El menú padre seleccionado no es válido.',
            ],
            'permissao_id' => [
                'required' => 'El campo permiso es obligatorio.',
                'exists' => 'El permiso seleccionado no es válido.',
            ],
            'situacao_id' => [
                'required' => 'El campo situación es obligatorio.',
                'exists' => 'La situación seleccionada no es válida.',
            ],
        ],
    ],
    
    // Permission Form Request Validation Messages
    'permission' => [
        'validation' => [
            'descricao' => [
                'required' => 'El campo descripción es obligatorio.',
                'string' => 'El campo descripción debe ser una cadena de texto.',
                'max' => 'El campo descripción no puede tener más de 80 caracteres.',
            ],
        ],
    ],
    'usuario' => [
        'validation' => [
            'name' => [
                'required' => 'El campo nombre es obligatorio.',
                'string' => 'El campo nombre debe ser una cadena de texto.',
                'max' => 'El campo nombre no puede tener más de 255 caracteres.',
            ],
            'email' => [
                'required' => 'El campo correo electrónico es obligatorio.',
                'string' => 'El campo correo electrónico debe ser una cadena de texto.',
                'email' => 'El campo correo electrónico debe ser una dirección de correo válida.',
                'max' => 'El campo correo electrónico no puede tener más de 255 caracteres.',
                'unique' => 'Este correo electrónico ya está en uso.',
            ],
            'password' => [
                'required' => 'El campo contraseña es obligatorio.',
                'string' => 'El campo contraseña debe ser una cadena de texto.',
                'min' => 'La contraseña debe tener al menos 8 caracteres.',
                'confirmed' => 'La confirmación de la contraseña no coincide.',
            ],
            'admin' => [
                'required' => 'El campo administrador es obligatorio.',
                'integer' => 'El campo administrador debe ser un número entero.',
                'in' => 'Seleccione una opción válida para administrador.',
            ],
            'avatar' => [
                'mimes' => 'El avatar debe ser una imagen en formatos: JPEG, PNG, JPG, GIF o WebP.',
                'max' => 'El avatar no puede ser mayor a 2MB.',
            ],
        ],
    ],
    
    // Access Level Form Request Validation Messages
    'access_level' => [
        'validation' => [
            'descricao' => [
                'required' => 'El campo descripción es obligatorio.',
                'string' => 'El campo descripción debe ser una cadena de texto.',
                'max' => 'El campo descripción no puede tener más de 80 caracteres.',
            ],
            'permissoes' => [
                'array' => 'El campo permisos debe ser un arreglo.',
                'integer' => 'Cada permiso debe ser un número entero.',
                'exists' => 'Uno de los permisos seleccionados no es válido.',
            ],
        ],
    ],
    
    // Notification Form Request Validation Messages
    'notification' => [
        'validation' => [
            'title' => [
                'required' => 'El campo título es obligatorio.',
                'string' => 'El campo título debe ser una cadena de texto.',
                'max' => 'El campo título no puede tener más de 50 caracteres.',
            ],
            'message' => [
                'required' => 'El campo mensaje es obligatorio.',
                'string' => 'El campo mensaje debe ser una cadena de texto.',
            ],
            'notification_type' => [
                'required' => 'El campo tipo de notificación es obligatorio.',
                'exists' => 'El tipo de notificación seleccionado no es válido.',
            ],
            'sent_notification_to' => [
                'required' => 'El campo destinatario es obligatorio.',
                'exists' => 'El destinatario seleccionado no es válido.',
            ],
            'icon' => [
                'string' => 'El campo icono debe ser una cadena de texto.',
                'max' => 'El campo icono no puede tener más de 30 caracteres.',
            ],
            'send_at' => [
                'required' => 'El campo fecha/hora de envío es obligatorio.',
                'date' => 'El campo fecha/hora de envío debe ser una fecha válida.',
                'after' => 'La fecha/hora de envío debe ser posterior a la fecha/hora actual.',
            ],
            'expired_at' => [
                'date' => 'El campo fecha de vencimiento debe ser una fecha válida.',
                'after' => 'La fecha de vencimiento debe ser posterior a la fecha/hora actual.',
            ],
            'send_to' => [
                'required' => 'El campo enviar a es obligatorio.',
                'string' => 'El campo enviar a debe ser una cadena de texto.',
            ],
            'users' => [
                'required_if' => 'El campo usuarios es obligatorio cuando el destinatario es "Usuarios Específicos".',
                'array' => 'El campo usuarios debe ser un arreglo.',
                'exists' => 'Uno o más usuarios seleccionados no son válidos.',
            ],
            'profiles' => [
                'required_if' => 'El campo perfiles es obligatorio cuando el destinatario es "Perfiles Específicos".',
                'array' => 'El campo perfiles debe ser un arreglo.',
                'exists' => 'Uno o más perfiles seleccionados no son válidos.',
            ],
        ],
    ],
    
    // API Form Request Validation Messages
    'api' => [
        'validation' => [
            'api_id' => [
                'required' => 'El campo tipo de API es obligatorio.',
                'exists' => 'El tipo de API seleccionado no es válido.',
            ],
            'credencial' => [
                'required' => 'El campo credencial es obligatorio.',
                'string' => 'El campo credencial debe ser una cadena de texto.',
            ],
            'situacao_id' => [
                'required' => 'El campo situación es obligatorio.',
                'exists' => 'La situación seleccionada no es válida.',
            ],
        ],
    ],

    // Parameter Form Request Validation Messages
    'parametro' => [
        'validation' => [
            'nome' => [
                'required' => 'El campo nombre es obligatorio.',
                'string' => 'El campo nombre debe ser una cadena de texto.',
                'max' => 'El campo nombre no puede tener más de 80 caracteres.',
            ],
            'descricao' => [
                'required' => 'El campo descripción es obligatorio.',
                'string' => 'El campo descripción debe ser una cadena de texto.',
            ],
            'tipo_id' => [
                'required' => 'El campo tipo es obligatorio.',
                'exists' => 'El tipo seleccionado no es válido.',
            ],
            'valor' => [
                'required' => 'El campo valor es obligatorio.',
                'string' => 'El campo valor debe ser una cadena de texto.',
            ],
        ],
    ],
];
