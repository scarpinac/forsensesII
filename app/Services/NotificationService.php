<?php

namespace App\Services;

use App\Models\Notificacao;
use App\Models\NotificacaoUsuario;
use App\Models\Notification;
use App\Models\NotificationRead;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Enviar notificação para todos os usuários
     */
    public function sendToAll(array $data): Notification
    {
        $data['target_type'] = 'all';
        $data['target_users'] = null;
        $data['target_roles'] = null;

        return $this->createNotification($data);
    }

    /**
     * Enviar notificação para usuários específicos
     */
    public function sendToUsers(array $userIds, array $data): Notification
    {
        $data['target_type'] = 'specific';
        $data['target_users'] = $userIds;
        $data['target_roles'] = null;

        return $this->createNotification($data);
    }

    /**
     * Enviar notificação para perfis (roles)
     */
    public function sendToRoles(array $roleNames, array $data): Notification
    {
        $data['target_type'] = 'role';
        $data['target_users'] = null;
        $data['target_roles'] = $roleNames;

        return $this->createNotification($data);
    }

    /**
     * Criar notificação e registros de leitura
     */
    private function createNotification(array $data): Notification
    {
        $notification = Notification::create($data);
        $this->createNotificationReads($notification);

        return $notification;
    }

    /**
     * Obter notificações não lidas de um usuário
     */
    public function getUnreadNotifications(User $user): Collection
    {
        $notificacoes = Notificacao::with(['tipoNotificacao'])
            ->naoExpirado()
            ->whereHas('leituras', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('lida', false);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Debug temporário
        \Log::info('Notificações encontradas: ' . $notificacoes->count());

        return $notificacoes->map(function ($notificacao) {
            return [
                'id' => $notificacao->id,
                'titulo' => $notificacao->titulo ?? '',
                'mensagem' => $notificacao->mensagem ?? '',
                'icone' => $notificacao->icone ?? 'fas fa-bell',
                'tipoNotificacao_id' => $notificacao->tipoNotificacao_id ?? 1,
                'created_at' => $notificacao->created_at ? $notificacao->created_at->format('d/m/Y H:i') : '',
                'expira_em' => $notificacao->expira_em ? $notificacao->expira_em->format('d/m/Y H:i') : null,
                'tipo2' => $notificacao->tipoNotificacao ? $notificacao->tipoNotificacao->descricao : 'info',
            ];
        });
    }

    /**
     * Contar notificações não lidas de um usuário
     */
    public function getUnreadCount(User $user): int
    {
        return Notificacao::naoExpirado()
            ->whereHas('leituras', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('lida', false);
            })
            ->count();
    }

    /**
     * Marcar notificação como lida para um usuário
     */
    public function markAsRead(Notificacao $notificacao, User $user): NotificacaoUsuario
    {
        $notificacaoUsuario = NotificacaoUsuario::where('notificacao_id', $notificacao->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $notificacaoUsuario->update([
            'lida' => true,
            'lida_em' => now(),
        ]);

        return $notificacaoUsuario;
    }

    /**
     * Marcar todas as notificações como lidas para um usuário
     */
    public function markAllAsRead(User $user): int
    {
        return NotificacaoUsuario::where('user_id', $user->id)
            ->where('lida', false)
            ->whereHas('notificacao', function ($query) {
                $query->naoExpirado();
            })
            ->update([
                'lida' => true,
                'lida_em' => now(),
            ]);
    }

    /**
     * Criar notificação de sistema (automática)
     */
    public function createSystemNotification(string $title, string $message, array $options = []): Notificacao
    {
        $data = array_merge([
            'titulo' => $title,
            'mensagem' => $message,
            'icone' => $options['icon'] ?? 'fas fa-info-circle',
            'expira_em' => $options['expires_at'] ?? null,
        ], $options);

        $notificacao = Notificacao::create($data);

        // Criar leituras para todos os usuários
        $this->createNotificationReads($notificacao);

        return $notificacao;
    }

    /**
     * Agendar envio de notificação
     */
    public function agendarNotificacao(array $dados): Notificacao
    {
        // Validar data de envio futura
        if (strtotime($dados['enviar_em']) <= time()) {
            throw new \Exception('A data de envio deve ser futura.');
        }

        // Criar notificação agendada
        $notificacao = Notificacao::create([
            'titulo' => $dados['titulo'],
            'mensagem' => $dados['mensagem'],
            'tipoNotificacao_id' => $dados['tipoNotificacao_id'],
            'icone' => $dados['icone'],
            'enviar_em' => $dados['enviar_em'],
//            'enviarNotificacaoPara_id' => $dados['enviarNotificacaoPara_id'],
//            'enviado_para' => $dados['enviado_para'],
            'enviado' => false,
        ]);

        return $notificacao;
    }

    /**
     * Criar leituras para todos os usuários para uma notificação
     */
    private function createNotificationReads(Notificacao $notificacao): void
    {
        $usuarios = User::get();

        foreach ($usuarios as $usuario) {
            NotificacaoUsuario::create([
                'user_id' => $usuario->id,
                'notificacao_id' => $notificacao->id,
                'lida' => false,
            ]);
        }
    }
}
