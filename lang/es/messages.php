<?php

return [
    'notification' => [
        'validation' => [
            'title' => [
                'required' => 'El campo título es obligatorio.',
                'string' => 'El campo título debe ser una cadena.',
                'max' => 'El campo título no puede tener más de 50 caracteres.',
            ],
            'message' => [
                'required' => 'El campo mensaje es obligatorio.',
                'string' => 'El campo mensaje debe ser una cadena.',
            ],
            'notification_type' => [
                'required' => 'El campo tipo de notificación es obligatorio.',
                'exists' => 'El tipo de notificación seleccionado es inválido.',
            ],
            'sent_notification_to' => [
                'required' => 'El campo destinatario es obligatorio.',
                'exists' => 'El destinatario seleccionado es inválido.',
            ],
            'icon' => [
                'string' => 'El campo ícono debe ser una cadena.',
                'max' => 'El campo ícono no puede tener más de 30 caracteres.',
            ],
            'send_at' => [
                'required' => 'El campo fecha/hora de envío es obligatorio.',
                'date' => 'El campo fecha/hora de envío debe ser una fecha válida.',
                'after' => 'La fecha/hora de envío debe ser posterior a la fecha/hora actual.',
            ],
            'expired_at' => [
                'date' => 'El campo fecha de expiración debe ser una fecha válida.',
                'after' => 'La fecha de expiración debe ser posterior a la fecha/hora actual.',
            ],
            'send_to' => [
                'required' => 'El campo enviar a es obligatorio.',
                'string' => 'El campo enviar a debe ser una cadena.',
            ],
            'users' => [
                'required_if' => 'El campo usuarios es obligatorio cuando el destinatario es "Usuarios Específicos".',
                'array' => 'El campo usuarios debe ser un array.',
                'exists' => 'Uno o más usuarios seleccionados son inválidos.',
            ],
            'profiles' => [
                'required_if' => 'El campo perfiles es obligatorio cuando el destinatario es "Perfiles Específicos".',
                'array' => 'El campo perfiles debe ser un array.',
                'exists' => 'Uno o más perfiles seleccionados son inválidos.',
            ],
        ],
    ],
];
