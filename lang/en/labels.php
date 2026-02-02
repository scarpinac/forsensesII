<?php

return [
    // Titles and headers
    'notification' => [
        'title' => [
            'index' => 'Notifications',
            'create' => 'New Notification',
            'edit' => 'Edit Notification',
            'show' => 'View Notification',
            'destroy' => 'Delete Notification',
            'history' => 'Notification History',
        ],
        'breadcrumb' => [
            'home' => 'Home',
            'listing' => 'Notification List',
        ],
        // Form fields
        'title' => 'Title',
        'message' => 'Message',
        'menssage_placeholder' => 'Type the notification message...',
        'notification_type' => 'Notification Type',
        'icon' => 'Icon',
        'icon_help' => 'FontAwesome icon name (ex: fas fa-bell)',
        'send_at' => 'Send Date/Time',
        'send_at_help' => 'Date and time when the notification will be sent',
        'destiny' => 'Recipients',
        'select_destiny' => 'Select recipient type',
        'destino_todos' => 'All Users',
        'destiny_users' => 'Specific Users',
        'destiny_profiles' => 'Specific Profiles',
        'usuarios' => 'Users',
        'usuarios_help' => 'Select users who will receive the notification',
        'perfis' => 'Profiles',
        'perfis_help' => 'Select profiles that will receive the notification',
        'sendTo' => 'Send To',
        'sended' => 'Sent',
        'expiredAt' => 'Expiration Date',
        'actions' => 'Actions',
        // Buttons and actions
        'create' => 'New Notification',
        'edit' => 'Edit',
        'show' => 'View',
        'destroy' => 'Delete',
        'history' => 'History',
        'back' => 'Back',
        'save' => 'Save',
        // History
        'history' => [
            'data' => [
                'title' => 'Notification Data',
            ],
            'changes' => [
                'title' => 'Change History',
            ],
            'table' => [
                'date' => 'Date',
                'user' => 'User',
                'type' => 'Change Type',
                'actions' => 'Actions',
            ],
            'button' => [
                'details' => 'Details',
            ],
            'fields' => [
                'id' => 'ID',
                'descricao' => 'Description',
                'created_at' => 'Creation Date',
                'updated_at' => 'Update Date',
                'deleted_at' => 'Deletion Date',
            ],
        ],
        // Modal
        'modal' => [
            'details' => [
                'title' => 'Change Details',
            ],
            'close' => 'Close',
        ],
        // Messages
        'no' => [
            'records' => 'No notifications found.',
            'history' => 'No history found.',
        ],
    ],
];
