<?php

namespace App\Observers;

use App\Models\Cadastro\OrigemProduto;
use App\Models\Cadastro\OrigemProdutoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class OrigemProdutoObserver
{
    public function created(OrigemProduto $origemProduto): void
    {
        $this->saveHistory($origemProduto, null, $origemProduto->toArray(), 'Inclusão de Registro');
    }

    public function updated(OrigemProduto $origemProduto): void
    {
        $dadosAnteriores = $origemProduto->getOriginal();
        $dadosNovos = $origemProduto->fresh()->toArray();
        $this->saveHistory($origemProduto, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    public function deleted(OrigemProduto $origemProduto): void
    {
        $dadosAnteriores = $origemProduto->getOriginal();
        $this->saveHistory($origemProduto, $dadosAnteriores, null, 'Deleção de Registro');
    }

    public function restored(OrigemProduto $origemProduto): void
    {
        //
    }

    public function forceDeleted(OrigemProduto $origemProduto): void
    {
        //
    }

    protected function saveHistory(OrigemProduto $origemProduto, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        OrigemProdutoHistorico::create([
            'user_id' => Auth::id(),
            'origem_produto_id' => $origemProduto->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
