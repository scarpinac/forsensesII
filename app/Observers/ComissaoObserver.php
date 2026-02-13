<?php

namespace App\Observers;

use App\Models\Comissao;
use App\Models\ComissaoHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class ComissaoObserver
{
    /**
     * Handle the Comissao "created" event.
     */
    public function created(Comissao $Comissao): void
    {
        $this->saveHistory($Comissao, null, $Comissao->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Comissao "updated" event.
     */
    public function updated(Comissao $Comissao): void
    {
        $dadosAnteriores = $Comissao->getOriginal();
        $dadosNovos = $Comissao->fresh()->toArray();

        $this->saveHistory($Comissao, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Comissao "deleted" event.
     */
    public function deleted(Comissao $Comissao): void
    {
        $dadosAnteriores = $Comissao->getOriginal();
        $this->saveHistory($Comissao, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Comissao $Comissao, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        ComissaoHistorico::create([
            'user_id' => Auth::id(),
            'Comissao_id' => $Comissao->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}