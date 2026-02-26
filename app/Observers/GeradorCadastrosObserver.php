<?php

namespace App\Observers;

use App\Models\Sistema\GeradorCadastros;
use App\Models\Sistema\GeradorCadastrosHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Support\Facades\Auth;

class GeradorCadastrosObserver
{
    /**
     * Handle the GeradorCadastros "created" event.
     */
    public function created(GeradorCadastros $gerador): void
    {
        $this->saveHistory($gerador, null, $gerador->toArray(), 'Inclusão de Registro');
    }

    /**
     * Handle the GeradorCadastros "updated" event.
     */
    public function updated(GeradorCadastros $gerador): void
    {
        // Pega os dados originais antes da atualização
        $dadosAnteriores = $gerador->getOriginal();

        // Pega os dados novos após a atualização
        $dadosNovos = $gerador->fresh()->toArray();

        $this->saveHistory($gerador, $dadosAnteriores, $dadosNovos, 'Alteração de Registro');
    }

    /**
     * Handle the GeradorCadastros "deleted" event.
     */
    public function deleted(GeradorCadastros $gerador): void
    {
        // Pega os dados originais antes da deleção
        $dadosAnteriores = $gerador->getOriginal();

        $this->saveHistory($gerador, $dadosAnteriores, null, 'Deleção de Registro');
    }

    /**
     * Salva o registro no histórico.
     */
    protected function saveHistory(GeradorCadastros $gerador, ?array $dadosAnteriores, ?array $dadosNovos, string $tipoDescricao): void
    {
        $tipoAlteracao = PadraoTipo::where('descricao', $tipoDescricao)->first();

        GeradorCadastrosHistorico::create([
            'user_id' => Auth::id(),
            'gerador_id' => $gerador->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'tipoAlteracao_id' => $tipoAlteracao ? $tipoAlteracao->id : null,
        ]);
    }
}
