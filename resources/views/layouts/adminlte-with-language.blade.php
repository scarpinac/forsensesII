@extends('adminlte::page')

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
            // Adiciona o seletor de idioma na navbar

            function markNotificationAsRead(notificationId, url = null) {
                console.log('Tentando marcar notificação como lida:', notificationId);

                // Obter CSRF token do meta tag ou do input hidden
                const csrfToken = document.querySelector('meta[name="csrf-token"]')
                    ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    : document.querySelector('input[name="_token"]')
                    ? document.querySelector('input[name="_token"]').value
                    : '{{ csrf_token() }}';

                console.log('CSRF Token:', csrfToken ? 'Presente' : 'Ausente');
                console.log('URL da requisição:', '{{ route("sistema.notificacao.marcar-como-lida") }}');

                fetch('{{ route("sistema.notificacao.marcar-como-lida") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        notification_id: notificationId
                    })
                })
                    .then(response => {
                        console.log('Status da resposta:', response.status);
                        console.log('Headers da resposta:', response.headers);

                        if (!response.ok) {
                            // Tentar ler o corpo da resposta para debug
                            return response.text().then(text => {
                                console.log('Corpo da resposta (erro):', text);
                                throw new Error(`HTTP error! status: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Resposta JSON:', data);
                        if (data.success) {
                            // Remover o badge se não houver mais notificações
                            const badge = document.querySelector('.navbar-badge');
                            if (badge) {
                                const currentCount = parseInt(badge.textContent);
                                if (currentCount <= 1) {
                                    badge.remove();
                                } else {
                                    badge.textContent = currentCount - 1;
                                }
                            }

                            // Remover a notificação do dropdown
                            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                            if (notificationItem) {
                                notificationItem.style.opacity = '0.5';
                                notificationItem.style.pointerEvents = 'none';
                            }

                            // Redirecionar se houver URL
                            if (url && url !== '#') {
                                window.location.href = url;
                            }
                        } else {
                            console.error('Erro na resposta:', data.message || 'Erro desconhecido');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao marcar notificação como lida:', error);
                        // Tentar recarregar a página como fallback
                        if (url && url !== '#') {
                            window.location.href = url;
                        }
                    });
            }

            // Adicionar evento de clique nas notificações
            document.addEventListener('click', function(e) {
                if (e.target.closest('.notification-item')) {
                    e.preventDefault();
                    const notificationItem = e.target.closest('.notification-item');
                    const notificationId = notificationItem.dataset.notificationId;
                    const url = notificationItem.dataset.url;
                    markNotificationAsRead(notificationId, url);
                }
            });

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
                            <span class="dropdown-item dropdown-header">{{ $unreadNotificationsCount }} Notificações</span>
                            <div class="dropdown-divider"></div>

                            @if($recentNotifications->count() > 0)
                                @foreach($recentNotifications as $notification)
                                    <a href="#" class="dropdown-item notification-item" data-notification-id="{{ $notification['id'] }}" data-url="{{ $notification['url'] ?? '#' }}">
                                        <div class="media">
                                            <div class="media-body">
                                                <h6 class="dropdown-item-title">
                                                    <i class="{{ $notification['icone'] }} {{ $notification['tipo'] == 'Informação' ? 'text-success' : ($notification['tipo'] == 'Aviso' ? 'text-warning' : ($notification['tipo'] == 'Erro' ? 'text-danger' : 'text-info')) }} mr-1"></i>
                                                    {{ $notification['titulo'] }}
                                                </h6>
                                                <p class="text-sm text-muted">{{ $notification['mensagem'] }}</p>
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
@endpush
