<?php

namespace App\Observers;

use App\Models\Cadastro\Transportadora;
use App\Models\Cadastro\TransportadoraHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class TransportadoraObserver
{
    /**
     * Handle the Transportadora "created" event.
     */
    public function created(Transportadora $transportadora): void
    {
        $this->saveHistory($transportadora, null, $transportadora->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Transportadora "updated" event.
     */
    public function updated(Transportadora $transportadora): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $transportadora->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $transportadora->fresh()->toArray();

        $this->saveHistory($transportadora, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Transportadora "deleted" event.
     */
    public function deleted(Transportadora $transportadora): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $transportadora->getOriginal();

        $this->saveHistory($transportadora, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the Transportadora "restored" event.
     */
    public function restored(Transportadora $transportadora): void
    {
        //
    }

    /**
     * Handle the Transportadora "force deleted" event.
     */
    public function forceDeleted(Transportadora $transportadora): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Transportadora $transportadora, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        TransportadoraHistorico::create([
            'user_id' => Auth::id(),
            'transportadora_id' => $transportadora->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
