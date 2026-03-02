<?php

namespace App\Observers;

use App\Models\Cadastro\Cor;
use App\Models\Cadastro\CorHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class CorObserver
{
    /**
     * Handle the Cor "created" event.
     */
    public function created(Cor $cor): void
    {
        $this->saveHistory($cor, null, $cor->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Cor "updated" event.
     */
    public function updated(Cor $cor): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $cor->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $cor->fresh()->toArray();

        $this->saveHistory($cor, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Cor "deleted" event.
     */
    public function deleted(Cor $cor): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $cor->getOriginal();

        $this->saveHistory($cor, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the Cor "restored" event.
     */
    public function restored(Cor $cor): void
    {
        //
    }

    /**
     * Handle the Cor "force deleted" event.
     */
    public function forceDeleted(Cor $cor): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Cor $cor, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        CorHistorico::create([
            'user_id' => Auth::id(),
            'cor_id' => $cor->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
