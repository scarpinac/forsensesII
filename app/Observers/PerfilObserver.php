<?php

namespace App\Observers;

use App\Models\Perfil;
use App\Models\PerfilHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class PerfilObserver
{
    /**
     * Handle the Perfil "created" event.
     */
    public function created(Perfil $perfil): void
    {
        $this->saveHistory($perfil, null, $perfil->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Perfil "updated" event.
     */
    public function updated(Perfil $perfil): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $perfil->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $perfil->fresh()->toArray();

        $this->saveHistory($perfil, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Perfil "deleted" event.
     */
    public function deleted(Perfil $perfil): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $perfil->getOriginal();

        $this->saveHistory($perfil, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the Perfil "restored" event.
     */
    public function restored(Perfil $perfil): void
    {
        //
    }

    /**
     * Handle the Perfil "force deleted" event.
     */
    public function forceDeleted(Perfil $perfil): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Perfil $perfil, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        PerfilHistorico::create([
            'user_id' => Auth::id(),
            'perfil_id' => $perfil->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
