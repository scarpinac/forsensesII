<?php

namespace App\Observers;

use App\Models\Cadastro\Revenda;
use App\Models\Cadastro\RevendaHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class RevendaObserver
{
    /**
     * Handle the Revenda "created" event.
     */
    public function created(Revenda $revenda): void
    {
        $this->saveHistory($revenda, null, $revenda->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Revenda "updated" event.
     */
    public function updated(Revenda $revenda): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $revenda->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $revenda->fresh()->toArray();

        $this->saveHistory($revenda, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Revenda "deleted" event.
     */
    public function deleted(Revenda $revenda): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $revenda->getOriginal();

        $this->saveHistory($revenda, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the Revenda "restored" event.
     */
    public function restored(Revenda $revenda): void
    {
        //
    }

    /**
     * Handle the Revenda "force deleted" event.
     */
    public function forceDeleted(Revenda $revenda): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Revenda $revenda, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        RevendaHistorico::create([
            'user_id' => Auth::id(),
            'revenda_id' => $revenda->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
