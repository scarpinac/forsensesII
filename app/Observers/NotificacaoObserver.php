<?php

namespace App\Observers;

use App\Models\Notificacao;
use App\Models\NotificacaoHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class NotificacaoObserver
{
    /**
     * Handle the Notificacao "created" event.
     */
    public function created(Notificacao $notificacao): void
    {
        $this->saveHistory($notificacao, null, $notificacao->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Notificacao "updated" event.
     */
    public function updated(Notificacao $notificacao): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $notificacao->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $notificacao->fresh()->toArray();

        $this->saveHistory($notificacao, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Notificacao "deleted" event.
     */
    public function deleted(Notificacao $notificacao): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $notificacao->getOriginal();

        $this->saveHistory($notificacao, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Notificacao $notificacao, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        NotificacaoHistorico::create([
            'user_id' => Auth::id(),
            'notificacao_id' => $notificacao->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }

    /**
     * Registrar marcação como lida/não lida (método estático para compatibilidade)
     */
    public static function registrarLeitura(Notificacao $notificacao, int $userId, string $tipo): void
    {
        $descricao = $tipo === 'read' ? 'Notificação marcada como lida' : 'Notificação marcada como não lida';
        $tipoDescricao = $tipo === 'read' ? 'Leitura de Notificação' : 'Desleitura de Notificação';

        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        NotificacaoHistorico::create([
            'user_id' => $userId,
            'notificacao_id' => $notificacao->id,
            'dados_anteriores' => ['lida' => $tipo === 'read' ? false : true],
            'dados_novos' => ['lida' => $tipo === 'read' ? true : false],
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
