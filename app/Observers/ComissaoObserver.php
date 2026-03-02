<?php

namespace App\Observers;

use App\Models\Cadastro\Comissao;
use App\Models\Cadastro\ComissaoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class ComissaoObserver
{
    /**
     * Handle the Comissao "created" event.
     */
    public function created(Comissao $comissao): void
    {
        $this->saveHistory($comissao, null, $comissao->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Comissao "updated" event.
     */
    public function updated(Comissao $comissao): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $comissao->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $comissao->fresh()->toArray();

        $this->saveHistory($comissao, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Comissao "deleted" event.
     */
    public function deleted(Comissao $comissao): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $comissao->getOriginal();

        $this->saveHistory($comissao, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the Comissao "restored" event.
     */
    public function restored(Comissao $comissao): void
    {
        //
    }

    /**
     * Handle the Comissao "force deleted" event.
     */
    public function forceDeleted(Comissao $comissao): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Comissao $comissao, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        ComissaoHistorico::create([
            'user_id' => Auth::id(),
            'comissao_id' => $comissao->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
