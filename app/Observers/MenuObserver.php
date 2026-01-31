<?php

namespace App\Observers;

use App\Models\Menu;
use App\Models\MenuHistorico;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class MenuObserver
{
    /**
     * Handle the Menu "created" event.
     */
    public function created(Menu $menu): void
    {
        $this->saveHistory($menu, null, $menu->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the Menu "updated" event.
     */
    public function updated(Menu $menu): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $menu->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $menu->fresh()->toArray();

        $this->saveHistory($menu, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the Menu "deleted" event.
     */
    public function deleted(Menu $menu): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $menu->getOriginal();

        $this->saveHistory($menu, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(Menu $menu, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        MenuHistorico::create([
            'user_id' => Auth::id(),
            'menu_id' => $menu->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
