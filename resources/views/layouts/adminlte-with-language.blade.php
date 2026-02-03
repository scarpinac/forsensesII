@php
    // Função auxiliar para limitar texto preservando HTML
    function limitHtml($html, $limit = 30) {
        // Remove tags HTML para contar caracteres
        $text = strip_tags($html);
        if (strlen($text) <= $limit) {
            return $html;
        }
        // Limita o texto e adiciona ...
        $limitedText = substr($text, 0, $limit) . '...';
        return $limitedText;
    }
@endphp

@extends('adminlte::page')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('css')
<style>
/* Estilos para posicionar o sino de notificações */
.navbar-nav {
    display: flex;
    align-items: center;
}

/* Garante que o último elemento (sino) fique no final */
.navbar-nav .nav-item:last-child {
    margin-left: auto !important;
}

/* Estilo específico para o sino */
.navbar-nav li:nth-last-child(1) {
    margin-left: auto !important;
    order: 999;
}

/* Garante que o dropdown apareça sobre outros elementos */
.dropdown-menu {
    z-index: 9999;
}

/* Estilo para o badge do contador */
.navbar-badge {
    position: absolute;
    top: 0;
    right: 0;
    transform: translate(25%, -25%);
    font-size: 0.6rem;
    padding: 2px 4px;
}
</style>
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let userLi = document.querySelector('.user-menu');
            const navbar = userLi ? userLi.closest('ul') : null;
            if (navbar) {
                const languageSelector = `
                <div class="language-selector ml-3">
                    <div class="btn-group" role="group">
                        <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
                            @csrf
                                <button type="submit" name="locale" value="pt_BR"
                                    class="btn btn-sm {{ app()->getLocale() === 'pt_BR' ? 'btn-system' : 'btn-outline-system' }}"
                                        title="Português (Brasil)">
                                    🇧🇷 PT
                                </button>
                        </form>
                        <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" name="locale" value="en"
                                class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-system' : 'btn-outline-system' }}"
                                    title="English">
                                🇺🇸 EN
                            </button>
                        </form>
                        <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" name="locale" value="it"
                                class="btn btn-sm {{ app()->getLocale() === 'it' ? 'btn-system' : 'btn-outline-system' }}"
                                    title="Italiano">
                                🇮🇹 IT
                            </button>
                        </form>
                        <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" name="locale" value="es"
                                class="btn btn-sm {{ app()->getLocale() === 'es' ? 'btn-system' : 'btn-outline-system' }}"
                                    title="Español">
                                🇪🇸 ES
                            </button>
                        </form>
                    </div>
                </div>`;

                // Cria um elemento li para o seletor
                let li = document.createElement('li');
                li.className = 'nav-item mt-1';
                li.innerHTML = languageSelector;

                // Adiciona à navbar
                navbar.insertBefore(li, userLi);

                const notificationComponent = `
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" id="notificationDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if($unreadNotificationsCount > 0)
                                <span class="badge badge-danger navbar-badge">{{ $unreadNotificationsCount }}</span>
                             @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="notificationDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                            <span class="dropdown-item dropdown-header">{{ $unreadNotificationsCount }} {{ $unreadNotificationsCount > 1 ? 'Notificações' : 'Notificação'}}</span>
                            <div class="dropdown-divider"></div>

                            @if($recentNotifications->count() > 0)
                                @foreach($recentNotifications as $notification)
                                    <a href="#" class="dropdown-item notification-item"
                                       data-notification-id="{{ $notification['id'] }}"
                                       data-notification-url="{{ URL::signedRoute('sistema.notificacao.detalhes', ['notificacao' => $notification['id']]) }}"
                                       >
                                        <div class="media">
                                            <div class="media-body">
                                                <h6 class="dropdown-item-title">
                                                    <i class="{{ $notification['icone'] }} {{ $notification['tipo'] == 'Informação' ? 'text-success' : ($notification['tipo'] == 'Aviso' ? 'text-warning' : ($notification['tipo'] == 'Erro' ? 'text-danger' : 'text-info')) }} mr-1"></i>
                                                    {{ $notification['titulo'] }}
                                                </h6>
                                                <p class="text-sm text-muted">{!! limitHtml($notification['mensagem'], 30) !!}</p>
                                                <p class="text-sm text-muted mb-0"><i class="far fa-clock mr-1"></i>{{ $notification['created_at'] }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                              @endforeach
                            @else
                                <span class="dropdown-item text-muted">Nenhuma notificação nova</span>
                                <div class="dropdown-divider"></div>
                            @endif


                        </div>
                    </li>`;

                li = document.createElement('li');
                li.className = 'nav-item';
                li.innerHTML = notificationComponent;
                navbar.insertBefore(li, userLi);
            }
        });
    </script>
@push('js')
<script>
// Manipular clique nas notificações
$(document).on('click', '.notification-item', function(e) {
    e.preventDefault();

    const $this = $(this);
    const notificationId = $this.data('notification-id');
    const url = $this.data('notification-url');

    // Buscar detalhes completos da notificação
    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const notification = response.notification;

                // Preencher modal com dados da API
                $('#modalTitulo').text(notification.titulo);
                $('#modalMensagem').html(notification.mensagem); // HTML renderizado
                $('#modalCreatedAt').text(notification.created_at);
                $('#modalNotificationId').val(notification.id);

                // Configurar ícone e cor baseada no tipo
                const $modalIcone = $('#modalIcone');
                $modalIcone.removeClass().addClass(notification.icone + ' mr-2');

                // Aqui você pode ajustar as cores baseado no tipo se necessário
                $modalIcone.addClass('text-info'); // Cor padrão

                // Verificar se já foi lida e esconder botão se necessário
                if (notification.already_read) {
                    $('#markAsReadBtn').hide();
                    $('.modal-footer .btn-secondary').text('Fechar');
                } else {
                    $('#markAsReadBtn').show();
                    $('.modal-footer .btn-secondary').text('Fechar');
                }

                // Abrir modal
                $('#notificationModal').modal('show');
            } else {
                console.error('Erro ao buscar detalhes:', response.error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Erro ao carregar detalhes da notificação.');
                }
            }
        },
        error: function(xhr) {
            console.error('Erro na requisição:', xhr);
            if (typeof toastr !== 'undefined') {
                toastr.error('Erro ao carregar detalhes da notificação.');
            }
        }
    });
});

// Manipular envio do formulário de marcar como lida
$(document).on('submit', '#markAsReadForm', function(e) {
    e.preventDefault();

    const $form = $(this);
    const notificationId = $('#modalNotificationId').val();

    // Enviar requisição AJAX para marcar como lida
    $.ajax({
        url: $form.attr('action'),
        method: $form.attr('method'),
        data: $form.serialize(),
        success: function(response) {
            if (response.success) {
                // Fechar modal
                $('#notificationModal').modal('hide');

                // Remover notificação da lista
                $(`.notification-item[data-notification-id="${notificationId}"]`).closest('.dropdown-item').remove();

                // Atualizar contador
                const $badge = $('.navbar-badge');
                const currentCount = parseInt($badge.text()) || 0;
                const newCount = Math.max(0, currentCount - 1);

                if (newCount > 0) {
                    $badge.text(newCount);
                } else {
                    $badge.remove();
                    $('.dropdown-header').text('Nenhuma notificação nova');
                }

                // Mostrar mensagem de sucesso
                if (typeof toastr !== 'undefined') {
                    toastr.success('Notificação marcada como lida!');
                }
            } else {
                // Se já foi lida, esconder botão e mostrar mensagem
                if (response.already_read) {
                    $('#markAsReadBtn').hide();
                    $('.modal-footer .btn-secondary').text('Fechar');
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.warning('Esta notificação já foi marcada como lida anteriormente.');
                    }
                } else {
                    // Outro erro
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.error || 'Erro ao marcar notificação como lida.');
                    }
                }
            }
        },
        error: function(xhr) {
            console.error('Erro ao marcar notificação como lida:', xhr);
            if (typeof toastr !== 'undefined') {
                toastr.error('Erro ao marcar notificação como lida.');
            }
        }
    });
});
</script>
@endpush

<!-- Modal de Notificação Completa -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="markAsReadForm" method="POST" action="{{ URL::signedRoute('sistema.notificacao.marcar-como-lida') }}">
                @csrf
                <input type="hidden" name="notification_id" id="modalNotificationId">

                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">
                        <i id="modalIcone" class="mr-2"></i>
                        <span id="modalTitulo"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="far fa-clock mr-1"></i>
                            <span id="modalCreatedAt"></span>
                        </small>
                    </div>
                    <div class="alert">
                        <p id="modalMensagem" class="mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="markAsReadBtn" class="btn btn-primary">
                        <i class="fas fa-check mr-1"></i> Marcar como lida
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
