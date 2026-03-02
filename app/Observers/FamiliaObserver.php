<?php

namespace App\Observers;

use App\Models\Cadastro\Familia;
use App\Models\Cadastro\FamiliaHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class FamiliaObserver
{
    public function created(Familia $familia): void
    {
        $this->saveHistory($familia, null, $familia->toArray(), 'Inclusão de Registro');
    }

    public function updated(Familia $familia): void
    {
        $dadosAnteriores = $familia->getOriginal();
        $dadosNovos = $familia->fresh()->toArray();
        $this->saveHistory($familia, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    public function deleted(Familia $familia): void
    {
        $dadosAnteriores = $familia->getOriginal();
        $this->saveHistory($familia, $dadosAnteriores, null, 'Deleção de Registro');
    }

    public function restored(Familia $familia): void
    {
        //
    }

    public function forceDeleted(Familia $familia): void
    {
        //
    }

    protected function saveHistory(Familia $familia, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        FamiliaHistorico::create([
            'user_id' => Auth::id(),
            'familia_id' => $familia->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
