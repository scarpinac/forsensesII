<?php

namespace App\Observers;

use App\Models\Cadastro\CondicaoPagamento;
use App\Models\Cadastro\CondicaoPagamentoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class CondicaoPagamentoObserver
{
    /**
     * Handle the CondicaoPagamento "created" event.
     */
    public function created(CondicaoPagamento $condicaoPagamento): void
    {
        $this->saveHistory($condicaoPagamento, null, $condicaoPagamento->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the CondicaoPagamento "updated" event.
     */
    public function updated(CondicaoPagamento $condicaoPagamento): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $condicaoPagamento->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $condicaoPagamento->fresh()->toArray();

        $this->saveHistory($condicaoPagamento, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the CondicaoPagamento "deleted" event.
     */
    public function deleted(CondicaoPagamento $condicaoPagamento): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $condicaoPagamento->getOriginal();

        $this->saveHistory($condicaoPagamento, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the CondicaoPagamento "restored" event.
     */
    public function restored(CondicaoPagamento $condicaoPagamento): void
    {
        //
    }

    /**
     * Handle the CondicaoPagamento "force deleted" event.
     */
    public function forceDeleted(CondicaoPagamento $condicaoPagamento): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(CondicaoPagamento $condicaoPagamento, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        CondicaoPagamentoHistorico::create([
            'user_id' => Auth::id(),
            'condicao_pagamento_id' => $condicaoPagamento->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
