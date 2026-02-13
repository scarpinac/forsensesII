<?php

namespace App\Observers;

use App\Models\Parametro;
use App\Models\ParametroHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class ParametroObserver
{
    /**
     * Handle the Parametro "created" event.
     */
    public function created(Parametro $parametro): void
    {
        $this->saveHistory($parametro, null, $parametro->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Parametro "updated" event.
     */
    public function updated(Parametro $parametro): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $parametro->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $parametro->fresh()->toArray();

        $this->saveHistory($parametro, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Parametro "deleted" event.
     */
    public function deleted(Parametro $parametro): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $parametro->getOriginal();

        $this->saveHistory($parametro, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Parametro $parametro, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        ParametroHistorico::create([
            'user_id' => Auth::id(),
            'parametro_id' => $parametro->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
