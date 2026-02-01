<?php

namespace App\Observers;

use App\Models\PadraoTipo;
use App\Models\PadraoTipoHistorico;
use Illuminate\Support\Facades\Auth;

class PadraoTipoObserver
{
    /**
     * Handle the PadraoTipo "created" event.
     */
    public function created(PadraoTipo $padraoTipo): void
    {
        $this->saveHistory($padraoTipo, null, $padraoTipo->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the PadraoTipo "updated" event.
     */
    public function updated(PadraoTipo $padraoTipo): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $padraoTipo->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $padraoTipo->fresh()->toArray();

        $this->saveHistory($padraoTipo, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the PadraoTipo "deleted" event.
     */
    public function deleted(PadraoTipo $padraoTipo): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $padraoTipo->getOriginal();

        $this->saveHistory($padraoTipo, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(PadraoTipo $padraoTipo, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = \App\Models\PadraoTipo::where('descricao', $tipoDescricao)->first();

        PadraoTipoHistorico::create([
            'user_id' => Auth::id(),
            'padrao_tipo_id' => $padraoTipo->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
