<?php

namespace App\Jobs;

use App\Models\Notificacao;
use App\Models\NotificacaoUsuario;
use App\Models\User;
use App\Models\Perfil;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessScheduledNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Iniciando processamento de notificações agendadas...');

        // Buscar notificações que devem ser enviadas agora
        $notificacoes = Notificacao::where('enviar_em', '<=', now())
            ->where('enviado', false)
            ->with(['tipoNotificacao', 'enviarNotificacaoPara'])
            ->get();

        Log::info("Encontradas {$notificacoes->count()} notificações para processar");

        foreach ($notificacoes as $notificacao) {
            try {
                $this->processNotification($notificacao);
                $notificacao->update(['enviado' => true]);
                Log::info("Notificação {$notificacao->id} processada com sucesso");
            } catch (\Exception $e) {
                Log::error("Erro ao processar notificação {$notificacao->id}: " . $e->getMessage());
            }
        }

        Log::info('Processamento de notificações agendadas concluído');
    }

    /**
     * Processa uma notificação individual
     */
    private function processNotification(Notificacao $notificacao): void
    {
        $enviarPara = $notificacao->enviarNotificacaoPara;
        $destinatarios = [];

        switch ($enviarPara->descricao) {
            case 'Todos os Usuários':
                $destinatarios = User::where('ativo', true)->get();
                break;

            case 'Usuários Específicos':
                $usuariosIds = json_decode($notificacao->enviado_para, true) ?? [];
                $destinatarios = User::whereIn('id', $usuariosIds)->where('ativo', true)->get();
                break;

            case 'Perfis Específicos':
                $perfisIds = json_decode($notificacao->enviado_para, true) ?? [];
                $usuariosIds = User::whereIn('perfil_id', $perfisIds)
                    ->where('ativo', true)
                    ->pluck('id')
                    ->toArray();
                $destinatarios = User::whereIn('id', $usuariosIds)->get();
                break;

            default:
                Log::warning("Tipo de envio não reconhecido: {$enviarPara->descricao}");
                return;
        }

        // Criar registros de notificação para cada usuário
        foreach ($destinatarios as $usuario) {
            NotificacaoUsuario::create([
                'user_id' => $usuario->id,
                'notificacao_id' => $notificacao->id,
                'lida' => false,
                'lida_em' => null,
            ]);
        }

        Log::info("Notificação {$notificacao->id} enviada para {$destinatarios->count()} usuários");
    }
}
