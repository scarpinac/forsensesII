<?php

namespace App\Observers;

use App\Models\Sistema\GeradorCadastroCampo;
use App\Models\Sistema\GeradorCadastroCampoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class GeradorCadastroCampoObserver
{
    /**
     * Handle the GeradorCadastroCampo "created" event.
     */
    public function created(GeradorCadastroCampo $campo): void
    {
        $this->saveHistory($campo, null, $campo->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the GeradorCadastroCampo "updated" event.
     */
    public function updated(GeradorCadastroCampo $campo): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $campo->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $campo->fresh()->toArray();

        $this->saveHistory($campo, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the GeradorCadastroCampo "deleted" event.
     */
    public function deleted(GeradorCadastroCampo $campo): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $campo->getOriginal();

        $this->saveHistory($campo, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(GeradorCadastroCampo $campo, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        GeradorCadastroCampoHistorico::create([
            'user_id' => Auth::id(),
            'campo_id' => $campo->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
