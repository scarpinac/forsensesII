<?php

namespace App\Observers;

use App\Models\Api;
use App\Models\ApiHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class ApiObserver
{
    /**
     * Handle the Api "created" event.
     */
    public function created(Api $api): void
    {
        $this->saveHistory($api, null, $api->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Api "updated" event.
     */
    public function updated(Api $api): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $api->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $api->fresh()->toArray();

        $this->saveHistory($api, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Api "deleted" event.
     */
    public function deleted(Api $api): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $api->getOriginal();

        $this->saveHistory($api, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Api $api, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        ApiHistorico::create([
            'user_id' => Auth::id(),
            'api_id' => $api->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
