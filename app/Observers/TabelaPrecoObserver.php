<?php

namespace App\Observers;

use App\Models\Cadastro\TabelaPreco;
use App\Models\Cadastro\TabelaPrecoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class TabelaPrecoObserver
{
    public function created(TabelaPreco $tabelaPreco): void
    {
        $this->saveHistory($tabelaPreco, null, $tabelaPreco->toArray(), 'Inclusão de Registro');
    }

    public function updated(TabelaPreco $tabelaPreco): void
    {
        $dadosAnteriores = $tabelaPreco->getOriginal();
        $dadosNovos = $tabelaPreco->fresh()->toArray();
        $this->saveHistory($tabelaPreco, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    public function deleted(TabelaPreco $tabelaPreco): void
    {
        $dadosAnteriores = $tabelaPreco->getOriginal();
        $this->saveHistory($tabelaPreco, $dadosAnteriores, null, 'Deleção de Registro');
    }

    public function restored(TabelaPreco $tabelaPreco): void
    {
        //
    }

    public function forceDeleted(TabelaPreco $tabelaPreco): void
    {
        //
    }

    protected function saveHistory(TabelaPreco $tabelaPreco, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        TabelaPrecoHistorico::create([
            'user_id' => Auth::id(),
            'tabela_preco_id' => $tabelaPreco->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
