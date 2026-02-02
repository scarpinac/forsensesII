<?php

return [
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
