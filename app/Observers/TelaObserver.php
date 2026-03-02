<?php

namespace App\Observers;

use App\Models\Cadastro\Tela;
use App\Models\Cadastro\TelaHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class TelaObserver
{
    public function created(Tela $tela): void
    {
        $this->saveHistory($tela, null, $tela->toArray(), 'Inclusão de Registro');
    }

    public function updated(Tela $tela): void
    {
        $dadosAnteriores = $tela->getOriginal();
        $dadosNovos = $tela->fresh()->toArray();
        $this->saveHistory($tela, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    public function deleted(Tela $tela): void
    {
        $dadosAnteriores = $tela->getOriginal();
        $this->saveHistory($tela, $dadosAnteriores, null, 'Deleção de Registro');
    }

    public function restored(Tela $tela): void
    {
        //
    }

    public function forceDeleted(Tela $tela): void
    {
        //
    }

    protected function saveHistory(Tela $tela, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        TelaHistorico::create([
            'user_id' => Auth::id(),
            'tela_id' => $tela->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
