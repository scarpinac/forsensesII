<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Cliente\StoreRequest;
use App\Http\Requests\Cadastro\Cliente\UpdateRequest;
use App\Models\Cadastro\Cliente;
use App\Models\Cadastro\ClienteHistorico;
use App\Models\Cadastro\ClienteEndereco;
use App\Models\Cadastro\ClienteContato;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ClienteController extends Controller
{
    /**
     * Display a listing of resource.
     */
    public function index()
    {
        abort_if (!auth()->user()->canAccess('cadastro.cliente.index'), 403, 'Acesso não autorizado');

        $clientes = Cliente::with(['tipoCliente', 'situacao', 'origem'])
            ->when(request('tipo_cliente'), function($query, $tipoCliente) {
                $query->where('tipoCliente_id', $tipoCliente);
            })
            ->when(request('situacao'), function($query, $situacao) {
                $query->where('situacao_id', $situacao);
            })
            ->when(request('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                      ->orWhere('cpf', 'like', "%{$search}%")
                      ->orWhere('cnpj', 'like', "%{$search}%");
                });
            })
            ->paginate();

        $tiposCliente = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Cliente');
        })->get();

        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situação');
        })->get();

        return view('cadastro.cliente.index', compact('clientes', 'tiposCliente', 'situacoes'));
    }

    /**
     * Show form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.create'), 403, 'Acesso não autorizado');

        $tiposCliente = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Cliente');
        })->get();

        $origens = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Origem do Cliente');
        })->get();

        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situação');
        })->get();

        $tiposEndereco = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipo de Endereço do Cliente');
        })->get();

        $tiposContato = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipo de Contato do Cliente');
        })->get();

        $cliente = new Cliente;
        return view('cadastro.cliente.create', compact(
            'tiposCliente',
            'cliente',
            'origens',
            'situacoes',
            'tiposEndereco',
            'tiposContato'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.store'), 403, 'Acesso não autorizado');

        try {
            DB::beginTransaction();

            $dados = $request->validated();

            // Criar cliente
            $cliente = Cliente::create($dados);

            // Salvar endereços se existirem
            if ($request->has('enderecos') && !empty($request->input('enderecos'))) {
                foreach ($request->input('enderecos') as $endereco) {
                    if (!empty($endereco['logradouro'])) {
                        $cliente->enderecos()->create($endereco);
                    }
                }
            }

            // Salvar contatos se existirem
            if ($request->has('contatos') && !empty($request->input('contatos'))) {
                foreach ($request->input('contatos') as $contato) {
                    if (!empty($contato['contato'])) {
                        $cliente->contatos()->create($contato);
                    }
                }
            }

            DB::commit();

            return redirect()->signedRoute('cadastro.cliente.index')
                ->with('success', __('labels.customer.success.created'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->signedRoute('cadastro.cliente.index')
                ->with('error', __('labels.customer.error.not_created') . ' - ' . $e->getMessage());
        }
    }

    /**
     * Display specified resource.
     */
    public function show(Cliente $cliente)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.show'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        $cliente->load([
            'tipoCliente',
            'situacao',
            'origem',
            'enderecos.tipoEndereco',
            'contatos.tipoContato'
        ]);

        return view('cadastro.cliente.show', compact('cliente', 'bloquearCampos'));
    }

    /**
     * Show form for editing specified resource.
     */
    public function edit(Cliente $cliente)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.edit'), 403, 'Acesso não autorizado');

        $cliente->load([
            'tipoCliente',
            'situacao',
            'origem',
            'enderecos.tipoEndereco',
            'contatos.tipoContato'
        ]);

        $tiposCliente = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Cliente');
        })->get();

        $origens = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Origem do Cliente');
        })->get();

        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situação');
        })->get();

        $tiposEndereco = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipo de Endereço do Cliente');
        })->get();

        $tiposContato = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipo de Contato do Cliente');
        })->get();

        return view('cadastro.cliente.edit', compact(
            'cliente',
            'tiposCliente',
            'origens',
            'situacoes',
            'tiposEndereco',
            'tiposContato'
        ));
    }

    /**
     * Update specified resource in storage.
     */
    public function update(UpdateRequest $request, Cliente $cliente)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.update'), 403, 'Acesso não autorizado');

        try {
            DB::beginTransaction();

            $dados = $request->validated();

            // Atualizar cliente
            $cliente->update($dados);

            // Remover endereços existentes e adicionar novos
            $cliente->enderecos()->delete();
            if ($request->has('enderecos') && !empty($request->input('enderecos'))) {
                foreach ($request->input('enderecos') as $endereco) {
                    if (!empty($endereco['logradouro'])) {
                        $cliente->enderecos()->create($endereco);
                    }
                }
            }

            // Remover contatos existentes e adicionar novos
            $cliente->contatos()->delete();
            if ($request->has('contatos') && !empty($request->input('contatos'))) {
                foreach ($request->input('contatos') as $contato) {
                    if (!empty($contato['contato'])) {
                        $cliente->contatos()->create($contato);
                    }
                }
            }

            DB::commit();

            return redirect()->signedRoute('cadastro.cliente.index')
                ->with('success', __('labels.customer.success.updated'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->signedRoute('cadastro.cliente.index')
                ->with('error', __('labels.customer.error.not_updated') . ' - ' . $e->getMessage());
        }
    }

    /**
     * Remove specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        $cliente->load([
            'tipoCliente',
            'situacao',
            'origem',
            'enderecos.tipoEndereco',
            'contatos.tipoContato'
        ]);

        return view('cadastro.cliente.destroy', compact('cliente', 'bloquearCampos'));
    }

    /**
     * Delete specified resource from storage permanently.
     */
    public function delete(Cliente $cliente)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.destroy'), 403, 'Acesso não autorizado');

        try {
            DB::beginTransaction();

            // Remover endereços e contatos relacionados
            $cliente->enderecos()->delete();
            $cliente->contatos()->delete();

            // Remover cliente
            $cliente->delete();

            DB::commit();

            return redirect()->signedRoute('cadastro.cliente.index')
                ->with('success', __('labels.customer.success.deleted'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->signedRoute('cadastro.cliente.index')
                ->with('error', __('labels.customer.error.not_deleted') . ' - ' . $e->getMessage());
        }
    }

    /**
     * Display history of specified resource.
     */
    public function history(Cliente $cliente)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cliente.history'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        $cliente->load(['historicos.user', 'historicos.tipoAlteracao']);

        return view('cadastro.cliente.history', compact('cliente', 'bloquearCampos'));
    }

    /**
     * Show details of a specific history record.
     */
    public function historyDetails(Cliente $cliente, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.cliente.history')) {
            abort(403);
        }

        $historico = ClienteHistorico::findOrFail($historicoId);
        $historico->load(['user', 'tipoAlteracao']);

        $dadosAnteriores = $historico->dados_anteriores;
        $dadosNovos = $historico->dados_novos;

        // Formatar datas
        if ($dadosAnteriores) {
            foreach ($dadosAnteriores as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at', 'data_cadastro', 'dataNascimento', 'data_limite_credite']) && $value) {
                    try {
                        $dadosAnteriores[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
            }
        }

        if ($dadosNovos) {
            foreach ($dadosNovos as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at', 'data_cadastro', 'dataNascimento', 'data_limite_credite']) && $value) {
                    try {
                        $dadosNovos[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
            }
        }

        // Mapeamento de campos para exibição amigável
        $camposTabela = [
            'id' => __('labels.customer.history.fields.id'),
            'revenda_id' => __('labels.customer.history.fields.revenda_id'),
            'tipoCliente_id' => __('labels.customer.history.fields.tipoCliente_id'),
            'nome' => __('labels.customer.history.fields.nome'),
            'nomeFantasia' => __('labels.customer.history.fields.nomeFantasia'),
            'cpf' => __('labels.customer.history.fields.cpf'),
            'cnpj' => __('labels.customer.history.fields.cnpj'),
            'dataNascimento' => __('labels.customer.history.fields.dataNascimento'),
            'rg' => __('labels.customer.history.fields.rg'),
            'rgOrgaoEmissor' => __('labels.customer.history.fields.rgOrgaoEmissor'),
            'inscricaoEstadual' => __('labels.customer.history.fields.inscricaoEstadual'),
            'inscricaoMunicipal' => __('labels.customer.history.fields.inscricaoMunicipal'),
            'optanteSimples_id' => __('labels.customer.history.fields.optanteSimples_id'),
            'responsavelNome' => __('labels.customer.history.fields.responsavelNome'),
            'responsavelCpf' => __('labels.customer.history.fields.responsavelCpf'),
            'responsavelRg' => __('labels.customer.history.fields.responsavelRg'),
            'responsavelRgOrgaoEmissor' => __('labels.customer.history.fields.responsavelRgOrgaoEmissor'),
            'nomePreferencia' => __('labels.customer.history.fields.nomePreferencia'),
            'origem_id' => __('labels.customer.history.fields.origem_id'),
            'outra_origem' => __('labels.customer.history.fields.outra_origem'),
            'data_cadastro' => __('labels.customer.history.fields.data_cadastro'),
            'limite_credito' => __('labels.customer.history.fields.limite_credito'),
            'data_limite_credite' => __('labels.customer.history.fields.data_limite_credite'),
            'situacao_id' => __('labels.customer.history.fields.situacao_id'),
            'observacoes' => __('labels.customer.history.fields.observacoes'),
            'created_at' => __('labels.customer.history.fields.created_at'),
            'updated_at' => __('labels.customer.history.fields.updated_at'),
            'deleted_at' => __('labels.customer.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
