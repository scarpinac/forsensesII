<?php

namespace App\Observers;

use App\Models\Permissao;
use App\Models\PermissaoHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class PermissaoObserver
{
    /**
     * Handle the Permissao "created" event.
     */
    public function created(Permissao $permissao): void
    {
        $this->saveHistory($permissao, null, $permissao->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Permissao "updated" event.
     */
    public function updated(Permissao $permissao): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $permissao->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $permissao->fresh()->toArray();

        $this->saveHistory($permissao, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Permissao "deleted" event.
     */
    public function deleted(Permissao $permissao): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $permissao->getOriginal();

        $this->saveHistory($permissao, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Permissao $permissao, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        PermissaoHistorico::create([
            'user_id' => Auth::id(),
            'permissao_id' => $permissao->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
