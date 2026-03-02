<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\TabelaPreco\StoreRequest;
use App\Http\Requests\Cadastro\TabelaPreco\UpdateRequest;
use App\Models\Cadastro\TabelaPreco;
use App\Models\Cadastro\TabelaPrecoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabelaPrecoController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.index'), 403, 'Acesso não autorizado');
        $tabelasPreco = TabelaPreco::with('situacao')->paginate();
        return view('cadastro.tabela_preco.index', compact('tabelasPreco'));
    }

    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.create'), 403, 'Acesso não autorizado');
        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situações');
        })->get();
        $tabelaPreco = new TabelaPreco;
        return view('cadastro.tabela_preco.create', compact('situacoes', 'tabelaPreco'));
    }

    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.store'), 403, 'Acesso não autorizado');
        $tabelaPreco = TabelaPreco::create($request->validated());
        if($tabelaPreco) {
            return redirect()->signedRoute('cadastro.tabela_preco.index')->with('success', __('labels.price_table.success.created'));
        }
        return redirect()->signedRoute('cadastro.tabela_preco.index')->with('error', __('labels.price_table.error.not_created'));
    }

    public function show(TabelaPreco $tabelaPreco)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $tabelaPreco->load('situacao');
        return view('cadastro.tabela_preco.show', compact('tabelaPreco', 'bloquearCampos'));
    }

    public function edit(TabelaPreco $tabelaPreco)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.edit'), 403, 'Acesso não autorizado');
        $tabelaPreco->load('situacao');
        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situações');
        })->get();
        return view('cadastro.tabela_preco.edit', compact('tabelaPreco', 'situacoes'));
    }

    public function update(UpdateRequest $request, TabelaPreco $tabelaPreco)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.update'), 403, 'Acesso não autorizado');
        if($tabelaPreco->update($request->validated())){
            return redirect()->signedRoute('cadastro.tabela_preco.index')->with('success', __('labels.price_table.success.updated'));
        }
        return redirect()->signedRoute('cadastro.tabela_preco.index')->with('error', __('labels.price_table.error.not_updated'));
    }

    public function destroy(TabelaPreco $tabelaPreco)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.destroy'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.tabela_preco.destroy', compact('tabelaPreco', 'bloquearCampos'));
    }

    public function delete(TabelaPreco $tabelaPreco)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.destroy'), 403, 'Acesso não autorizado');
        if($tabelaPreco->delete()) {
            return redirect()->signedRoute('cadastro.tabela_preco.index')->with('success', __('labels.price_table.success.deleted'));
        }
        return redirect()->signedRoute('cadastro.tabela_preco.index')->with('error', __('labels.price_table.error.not_deleted'));
    }

    public function history(TabelaPreco $tabelaPreco)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tabela_preco.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $tabelaPreco->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.tabela_preco.history', compact('tabelaPreco', 'bloquearCampos'));
    }

    public function historyDetails(TabelaPreco $tabelaPreco, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.tabela_preco.history')) {
            abort(403);
        }
        $historico = TabelaPrecoHistorico::findOrFail($historicoId);
        $historico->load(['user', 'tipoAlteracao']);
        $dadosAnteriores = $historico->dados_anteriores;
        $dadosNovos = $historico->dados_novos;

        if ($dadosAnteriores) {
            foreach ($dadosAnteriores as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosAnteriores[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {}
                }
            }
        }
        if ($dadosNovos) {
            foreach ($dadosNovos as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosNovos[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {}
                }
            }
        }

        $camposTabela = [
            'id' => __('labels.price_table.history.fields.id'),
            'descricao' => __('labels.price_table.history.fields.descricao'),
            'vigenciaAte' => __('labels.price_table.history.fields.vigenciaAte'),
            'situacao_id' => __('labels.price_table.history.fields.situacao_id'),
            'created_at' => __('labels.price_table.history.fields.created_at'),
            'updated_at' => __('labels.price_table.history.fields.updated_at'),
            'deleted_at' => __('labels.price_table.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
