<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class UsuarioObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->saveHistory($user, null, $user->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $user->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $user->fresh()->toArray();

        $this->saveHistory($user, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $user->getOriginal();

        $this->saveHistory($user, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(User $user, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        UserHistorico::create([
            'user_id' => Auth::id(),
            'usuario_id' => $user->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
