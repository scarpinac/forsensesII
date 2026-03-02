<?php

namespace App\Observers;

use App\Models\Cadastro\Cliente;
use App\Models\Cadastro\ClienteEndereco;
use App\Models\Cadastro\ClienteContato;
use App\Models\Cadastro\ClienteHistorico;
use Illuminate\Support\Facades\Auth;

class ClienteObserver
{
    /**
     * Handle the Cliente "created" event.
     */
    public function created(Cliente $cliente): void
    {
        $this->registrarHistorico($cliente, 'Cadastro', null, $cliente->toArray());
    }

    /**
     * Handle the Cliente "updated" event.
     */
    public function updated(Cliente $cliente): void
    {
        $dadosAnteriores = $cliente->getOriginal();
        $dadosNovos = $cliente->getChanges();
        
        // Remover campos de timestamp que não devem ser registrados
        unset($dadosNovos['updated_at']);
        
        if (!empty($dadosNovos)) {
            $this->registrarHistorico($cliente, 'Edição', $dadosAnteriores, $dadosNovos);
        }
    }

    /**
     * Handle the Cliente "deleted" event.
     */
    public function deleted(Cliente $cliente): void
    {
        $this->registrarHistorico($cliente, 'Exclusão', $cliente->toArray(), null);
    }

    /**
     * Handle the ClienteEndereco "created" event.
     */
    public function clienteEnderecoCreated(ClienteEndereco $endereco): void
    {
        $this->registrarHistorico(
            $endereco->cliente, 
            'Endereço Adicionado', 
            null, 
            $endereco->toArray()
        );
    }

    /**
     * Handle the ClienteEndereco "updated" event.
     */
    public function clienteEnderecoUpdated(ClienteEndereco $endereco): void
    {
        $dadosAnteriores = $endereco->getOriginal();
        $dadosNovos = $endereco->getChanges();
        
        // Remover campos de timestamp
        unset($dadosNovos['updated_at']);
        
        if (!empty($dadosNovos)) {
            $this->registrarHistorico(
                $endereco->cliente, 
                'Endereço Alterado', 
                $dadosAnteriores, 
                $dadosNovos
            );
        }
    }

    /**
     * Handle the ClienteEndereco "deleted" event.
     */
    public function clienteEnderecoDeleted(ClienteEndereco $endereco): void
    {
        $this->registrarHistorico(
            $endereco->cliente, 
            'Endereço Excluído', 
            $endereco->toArray(), 
            null
        );
    }

    /**
     * Handle the ClienteContato "created" event.
     */
    public function clienteContatoCreated(ClienteContato $contato): void
    {
        $this->registrarHistorico(
            $contato->cliente, 
            'Contato Adicionado', 
            null, 
            $contato->toArray()
        );
    }

    /**
     * Handle the ClienteContato "updated" event.
     */
    public function clienteContatoUpdated(ClienteContato $contato): void
    {
        $dadosAnteriores = $contato->getOriginal();
        $dadosNovos = $contato->getChanges();
        
        // Remover campos de timestamp
        unset($dadosNovos['updated_at']);
        
        if (!empty($dadosNovos)) {
            $this->registrarHistorico(
                $contato->cliente, 
                'Contato Alterado', 
                $dadosAnteriores, 
                $dadosNovos
            );
        }
    }

    /**
     * Handle the ClienteContato "deleted" event.
     */
    public function clienteContatoDeleted(ClienteContato $contato): void
    {
        $this->registrarHistorico(
            $contato->cliente, 
            'Contato Excluído', 
            $contato->toArray(), 
            null
        );
    }

    /**
     * Registrar histórico do cliente
     */
    private function registrarHistorico(Cliente $cliente, string $tipoAlteracao, ?array $dadosAnteriores, ?array $dadosNovos): void
    {
        // Buscar o tipo de alteração na tabela padrao_tipo
        $tipoAlteracaoModel = \App\Models\Sistema\PadraoTipo::where('descricao', $tipoAlteracao)->first();
        
        if (!$tipoAlteracaoModel) {
            // Criar o tipo se não existir
            $tipoAlteracaoModel = \App\Models\Sistema\PadraoTipo::create([
                'descricao' => $tipoAlteracao,
                'padrao_id' => \App\Models\Sistema\Padrao::TiposHistoricoCliente ?? 99, // Valor padrão se não existir
                'situacao_id' => 1, // Ativo
            ]);
        }

        ClienteHistorico::create([
            'user_id' => Auth::id(),
            'cliente_id' => $cliente->id,
            'tipoAlteracao_id' => $tipoAlteracaoModel->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
        ]);
    }

    /**
     * Registrar histórico em lote (para múltiplos endereços/contatos)
     */
    public function registrarHistoricoEmLote(Cliente $cliente, string $tipoAlteracao, array $operacoes): void
    {
        // Buscar o tipo de alteração na tabela padrao_tipo
        $tipoAlteracaoModel = \App\Models\Sistema\PadraoTipo::where('descricao', $tipoAlteracao)->first();
        
        if (!$tipoAlteracaoModel) {
            $tipoAlteracaoModel = \App\Models\Sistema\PadraoTipo::create([
                'descricao' => $tipoAlteracao,
                'padrao_id' => \App\Models\Sistema\Padrao::TiposHistoricoCliente ?? 99,
                'situacao_id' => 1,
            ]);
        }

        foreach ($operacoes as $operacao) {
            ClienteHistorico::create([
                'user_id' => Auth::id(),
                'cliente_id' => $cliente->id,
                'tipoAlteracao_id' => $tipoAlteracaoModel->id,
                'dados_anteriores' => $operacao['dados_anteriores'] ?? null,
                'dados_novos' => $operacao['dados_novos'] ?? null,
            ]);
        }
    }
}
