<?php

namespace App\Observers;

use App\Models\Cadastro\Acabamento;
use App\Models\Cadastro\AcabamentoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class AcabamentoObserver
{
    /**
     * Handle the Acabamento "created" event.
     */
    public function created(Acabamento $acabamento): void
    {
        $this->saveHistory($acabamento, null, $acabamento->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Acabamento "updated" event.
     */
    public function updated(Acabamento $acabamento): void
    {
        $dadosAnteriores = $acabamento->getOriginal();
        $dadosNovos = $acabamento->fresh()->toArray();
        $this->saveHistory($acabamento, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Acabamento "deleted" event.
     */
    public function deleted(Acabamento $acabamento): void
    {
        $dadosAnteriores = $acabamento->getOriginal();
        $this->saveHistory($acabamento, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the Acabamento "restored" event.
     */
    public function restored(Acabamento $acabamento): void
    {
        //
    }

    /**
     * Handle the Acabamento "force deleted" event.
     */
    public function forceDeleted(Acabamento $acabamento): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Acabamento $acabamento, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        AcabamentoHistorico::create([
            'user_id' => Auth::id(),
            'acabamento_id' => $acabamento->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
