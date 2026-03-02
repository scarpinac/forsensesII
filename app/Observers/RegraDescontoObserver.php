<?php

namespace App\Observers;

use App\Models\Cadastro\RegraDesconto;
use App\Models\Cadastro\RegraDescontoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class RegraDescontoObserver
{
    public function created(RegraDesconto $regraDesconto): void
    {
        $this->saveHistory($regraDesconto, null, $regraDesconto->toArray(), 'Inclusão de Registro');
    }

    public function updated(RegraDesconto $regraDesconto): void
    {
        $dadosAnteriores = $regraDesconto->getOriginal();
        $dadosNovos = $regraDesconto->fresh()->toArray();
        $this->saveHistory($regraDesconto, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    public function deleted(RegraDesconto $regraDesconto): void
    {
        $dadosAnteriores = $regraDesconto->getOriginal();
        $this->saveHistory($regraDesconto, $dadosAnteriores, null, 'Deleção de Registro');
    }

    public function restored(RegraDesconto $regraDesconto): void
    {
        //
    }

    public function forceDeleted(RegraDesconto $regraDesconto): void
    {
        //
    }

    protected function saveHistory(RegraDesconto $regraDesconto, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        RegraDescontoHistorico::create([
            'user_id' => Auth::id(),
            'regra_desconto_id' => $regraDesconto->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
