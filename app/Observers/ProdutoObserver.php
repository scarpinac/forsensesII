<?php

namespace App\Observers;

use App\Models\Cadastro\Produto;
use App\Models\Cadastro\ProdutoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class ProdutoObserver
{
    public function created(Produto $produto): void
    {
        $this->saveHistory($produto, null, $produto->toArray(), 'Inclusão de Registro');
    }

    public function updated(Produto $produto): void
    {
        $dadosAnteriores = $produto->getOriginal();
        $dadosNovos = $produto->fresh()->toArray();
        $this->saveHistory($produto, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    public function deleted(Produto $produto): void
    {
        $dadosAnteriores = $produto->getOriginal();
        $this->saveHistory($produto, $dadosAnteriores, null, 'Deleção de Registro');
    }

    public function restored(Produto $produto): void
    {
        //
    }

    public function forceDeleted(Produto $produto): void
    {
        //
    }

    protected function saveHistory(Produto $produto, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        ProdutoHistorico::create([
            'user_id' => Auth::id(),
            'produto_id' => $produto->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
