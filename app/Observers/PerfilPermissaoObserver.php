<?php

namespace App\Observers;

use App\Models\PerfilPermissao;
use App\Models\PerfilPermissaoHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class PerfilPermissaoObserver
{
    /**
     * Handle the PerfilPermissao "created" event.
     */
    public function created(PerfilPermissao $perfilPermissao): void
    {
        $this->saveHistory($perfilPermissao, null, $perfilPermissao->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the PerfilPermissao "updated" event.
     */
    public function updated(PerfilPermissao $perfilPermissao): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $perfilPermissao->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $perfilPermissao->fresh()->toArray();

        $this->saveHistory($perfilPermissao, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the PerfilPermissao "deleted" event.
     */
    public function deleted(PerfilPermissao $perfilPermissao): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $perfilPermissao->getOriginal();

        $this->saveHistory($perfilPermissao, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the PerfilPermissao "restored" event.
     */
    public function restored(PerfilPermissao $perfilPermissao): void
    {
        //
    }

    /**
     * Handle the PerfilPermissao "force deleted" event.
     */
    public function forceDeleted(PerfilPermissao $perfilPermissao): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(PerfilPermissao $perfilPermissao, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        PerfilPermissaoHistorico::create([
            'user_id' => Auth::id(),
            'perfil_permissao_id' => $perfilPermissao->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
