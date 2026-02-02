<?php

return [
    // Títulos y encabezados
    'notification' => [
        'title' => [
            'index' => 'Notificaciones',
            'create' => 'Nueva Notificación',
            'edit' => 'Editar Notificación',
            'show' => 'Ver Notificación',
            'destroy' => 'Eliminar Notificación',
            'history' => 'Historial de Notificación',
        ],
        'breadcrumb' => [
            'home' => 'Inicio',
            'listing' => 'Lista de Notificaciones',
        ],
        // Campos del formulario
        'title' => 'Título',
        'message' => 'Mensaje',
        'menssage_placeholder' => 'Escriba el mensaje de la notificación...',
        'notification_type' => 'Tipo de Notificación',
        'icon' => 'Ícono',
        'icon_help' => 'Nombre del ícono FontAwesome (ej: fas fa-bell)',
        'send_at' => 'Fecha/Hora de Envío',
        'send_at_help' => 'Fecha y hora en que se enviará la notificación',
        'destiny' => 'Destinatarios',
        'select_destiny' => 'Seleccione el tipo de destinatario',
        'destino_todos' => 'Todos los Usuarios',
        'destiny_users' => 'Usuarios Específicos',
        'destiny_profiles' => 'Perfiles Específicos',
        'usuarios' => 'Usuarios',
        'usuarios_help' => 'Seleccione los usuarios que recibirán la notificación',
        'perfis' => 'Perfiles',
        'perfis_help' => 'Seleccione los perfiles que recibirán la notificación',
        'sendTo' => 'Enviar A',
        'sended' => 'Enviado',
        'expiredAt' => 'Fecha de Expiración',
        'actions' => 'Acciones',
        // Botones y acciones
        'create' => 'Nueva Notificación',
        'edit' => 'Editar',
        'show' => 'Ver',
        'destroy' => 'Eliminar',
        'history' => 'Historial',
        'back' => 'Volver',
        'save' => 'Guardar',
        // Historial
        'history' => [
            'data' => [
                'title' => 'Datos de la Notificación',
            ],
            'changes' => [
                'title' => 'Historial de Cambios',
            ],
            'table' => [
                'date' => 'Fecha',
                'user' => 'Usuario',
                'type' => 'Tipo de Cambio',
                'actions' => 'Acciones',
            ],
            'button' => [
                'details' => 'Detalles',
            ],
            'fields' => [
                'id' => 'ID',
                'descricao' => 'Descripción',
                'created_at' => 'Fecha de Creación',
                'updated_at' => 'Fecha de Actualización',
                'deleted_at' => 'Fecha de Eliminación',
            ],
        ],
        // Modal
        'modal' => [
            'details' => [
                'title' => 'Detalles del Cambio',
            ],
            'close' => 'Cerrar',
        ],
        // Mensajes
        'no' => [
            'records' => 'No se encontraron notificaciones.',
            'history' => 'No se encontró historial.',
        ],
    ],
];
