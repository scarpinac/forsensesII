<?php

namespace App\Observers;

use App\Models\Padrao;
use App\Models\PadraoHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class PadraoObserver
{
    /**
     * Handle the Padrao "created" event.
     */
    public function created(Padrao $padrao): void
    {
        $this->saveHistory($padrao, null, $padrao->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Padrao "updated" event.
     */
    public function updated(Padrao $padrao): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $padrao->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $padrao->fresh()->toArray();

        $this->saveHistory($padrao, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Padrao "deleted" event.
     */
    public function deleted(Padrao $padrao): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $padrao->getOriginal();

        $this->saveHistory($padrao, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Padrao $padrao, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        PadraoHistorico::create([
            'user_id' => Auth::id(),
            'padrao_id' => $padrao->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
