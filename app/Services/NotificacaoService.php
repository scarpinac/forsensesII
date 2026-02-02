<?php

namespace App\Services;

use App\Models\Notificacao;
use App\Models\NotificacaoUsuario;
use App\Models\NotificacaoHistorico;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificacaoService
{
    /**
     * Obter notificações não lidas para um usuário
     */
    public function getNotificacoesNaoLidas(User $user)
    {
        return Notificacao::with(['tipoNotificacao'])
            ->where('enviado', true) // Apenas notificações enviadas
            ->whereHas('leituras', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('lida', false);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obter contagem de notificações não lidas
     */
    public function getContagemNaoLidas(User $user): int
    {
        return NotificacaoUsuario::where('user_id', $user->id)
            ->where('lida', false)
            ->whereHas('notificacao', function ($query) {
                $query->where('enviado', true); // Apenas notificações enviadas
            })
            ->count();
    }

    /**
     * Obter notificações recentes para o dropdown
     */
    public function getNotificacoesRecentes(User $user, int $limite = 5)
    {
        return Notificacao::with(['tipoNotificacao'])
            ->where('enviado', true) // Apenas notificações enviadas
            ->whereHas('leituras', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limite)
            ->get()
            ->map(function ($notificacao) use ($user) {
                $leitura = $notificacao->getLeituraParaUsuario($user);
                return [
                    'id' => $notificacao->id,
                    'titulo' => $notificacao->titulo,
                    'mensagem' => $notificacao->mensagem,
                    'tipo' => $notificacao->tipo_formatado,
                    'cor' => $notificacao->cor_tipo,
                    'icone' => $notificacao->icone ?? 'fas fa-bell',
                    'url' => $notificacao->url,
                    'created_at' => $notificacao->created_at->diffForHumans(),
                    'lida' => $leitura ? $leitura->lida : false,
                ];
            });
    }

    /**
     * Criar nova notificação
     */
    public function criarNotificacao(array $dados): Notificacao
    {
        $notificacao = Notificacao::create($dados);

        // Criar leituras para todos os usuários
        $this->criarLeiturasParaNotificacao($notificacao);

        Log::info('Notificação criada', [
            'notificacao_id' => $notificacao->id,
            'titulo' => $notificacao->titulo,
        ]);

        return $notificacao;
    }

    /**
     * Enviar notificação para todos os usuários
     */
    public function enviarParaTodos(array $dados): Notificacao
    {
        $notificacao = $this->criarNotificacao($dados);

        Log::info('Notificação enviada para todos', [
            'notificacao_id' => $notificacao->id,
            'usuarios_count' => User::count(),
        ]);

        return $notificacao;
    }

    /**
     * Enviar notificação para usuários específicos
     */
    public function enviarParaUsuarios(array $dados, array $userIds): Notificacao
    {
        $notificacao = $this->criarNotificacao($dados);

        // Criar leituras apenas para os usuários especificados
        foreach ($userIds as $userId) {
            NotificacaoUsuario::create([
                'user_id' => $userId,
                'notificacao_id' => $notificacao->id,
                'lida' => false,
            ]);
        }

        Log::info('Notificação enviada para usuários específicos', [
            'notificacao_id' => $notificacao->id,
            'user_ids' => $userIds,
        ]);

        return $notificacao;
    }

    /**
     * Enviar notificação para perfis específicos
     */
    public function enviarParaPerfis(array $dados, array $perfis): Notificacao
    {
        $notificacao = $this->criarNotificacao($dados);

        // Obter usuários com os perfis especificados
        $usuarios = User::whereHas('perfis', function ($query) use ($perfis) {
            $query->whereIn('descricao', $perfis);
        })->get();

        // Criar leituras para os usuários encontrados
        foreach ($usuarios as $usuario) {
            NotificacaoUsuario::create([
                'user_id' => $usuario->id,
                'notificacao_id' => $notificacao->id,
                'lida' => false,
            ]);
        }

        Log::info('Notificação enviada para perfis', [
            'notificacao_id' => $notificacao->id,
            'perfis' => $perfis,
            'usuarios_count' => $usuarios->count(),
        ]);

        return $notificacao;
    }

    /**
     * Marcar notificação como lida
     */
    public function marcarComoLida(int $notificacaoId, User $user): NotificacaoUsuario
    {
        $notificacao = Notificacao::findOrFail($notificacaoId);
        return $notificacao->marcarComoLidaPorUsuario($user);
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function marcarTodasComoLidas(User $user): int
    {
        $leituraNaoLidas = NotificacaoUsuario::where('user_id', $user->id)
            ->where('lida', false)
            ->whereHas('notificacao', function ($query) {
                $query->where('enviado', true); // Apenas notificações enviadas
            })
            ->get();

        $contador = 0;
        foreach ($leituraNaoLidas as $leitura) {
            if ($leitura->marcarComoLida()) {
                $contador++;
            }
        }

        Log::info('Notificações marcadas como lidas', [
            'user_id' => $user->id,
            'contador' => $contador,
        ]);

        return $contador;
    }

    /**
     * Obter histórico de uma notificação
     */
    public function getHistoricoNotificacao(int $notificacaoId)
    {
        return NotificacaoHistorico::with(['usuario', 'notificacao'])
            ->daNotificacao($notificacaoId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obter estatísticas de notificações
     */
    public function getEstatisticas(): array
    {
        $totalNotificacoes = Notificacao::count();
        $totalLeituras = NotificacaoUsuario::count();
        $leiturasLidas = NotificacaoUsuario::where('lida', true)->count();
        $leiturasNaoLidas = $totalLeituras - $leiturasLidas;

        return [
            'total_notificacoes' => $totalNotificacoes,
            'total_leituras' => $totalLeituras,
            'leituras_lidas' => $leiturasLidas,
            'leituras_nao_lidas' => $leiturasNaoLidas,
            'taxa_leitura' => $totalLeituras > 0 ? round(($leiturasLidas / $totalLeituras) * 100, 2) : 0,
        ];
    }

    /**
     * Criar leituras para todos os usuários para uma notificação
     */
    private function criarLeiturasParaNotificacao(Notificacao $notificacao): void
    {
        $usuarios = User::get();

        foreach ($usuarios as $usuario) {
            NotificacaoUsuario::create([
                'user_id' => $usuario->id,
                'notificacao_id' => $notificacao->id,
                'lida' => false,
            ]);
        }

        Log::info('Leituras criadas para notificação', [
            'notificacao_id' => $notificacao->id,
            'usuarios_count' => $usuarios->count(),
        ]);
    }

    /**
     * Limpar notificações expiradas
     */
    public function limparNotificacoesExpiradas(): int
    {
        $notificacoesExpiradas = Notificacao::where('expira_em', '<', now())->get();
        $contador = 0;

        foreach ($notificacoesExpiradas as $notificacao) {
            $notificacao->delete();
            $contador++;
        }

        Log::info('Notificações expiradas removidas', [
            'contador' => $contador,
        ]);

        return $contador;
    }
}
