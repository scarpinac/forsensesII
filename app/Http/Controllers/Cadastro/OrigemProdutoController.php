<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\OrigemProduto\StoreRequest;
use App\Http\Requests\Cadastro\OrigemProduto\UpdateRequest;
use App\Models\Cadastro\OrigemProduto;
use App\Models\Cadastro\OrigemProdutoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrigemProdutoController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.index'), 403, 'Acesso não autorizado');
        $origensProduto = OrigemProduto::with('situacao')->paginate();
        return view('cadastro.origem_produto.index', compact('origensProduto'));
    }

    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.create'), 403, 'Acesso não autorizado');
        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situações');
        })->get();
        return view('cadastro.origem_produto.create', compact('situacoes'));
    }

    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.store'), 403, 'Acesso não autorizado');
        $origemProduto = OrigemProduto::create($request->validated());
        if($origemProduto) {
            return redirect()->signedRoute('cadastro.origem_produto.index')->with('success', __('labels.product_origin.success.created'));
        }
        return redirect()->signedRoute('cadastro.origem_produto.index')->with('error', __('labels.product_origin.error.not_created'));
    }

    public function show(OrigemProduto $origemProduto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $origemProduto->load('situacao');
        return view('cadastro.origem_produto.show', compact('origemProduto', 'bloquearCampos'));
    }

    public function edit(OrigemProduto $origemProduto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.edit'), 403, 'Acesso não autorizado');
        $origemProduto->load('situacao');
        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situações');
        })->get();
        return view('cadastro.origem_produto.edit', compact('origemProduto', 'situacoes'));
    }

    public function update(UpdateRequest $request, OrigemProduto $origemProduto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.update'), 403, 'Acesso não autorizado');
        if($origemProduto->update($request->validated())){
            return redirect()->signedRoute('cadastro.origem_produto.index')->with('success', __('labels.product_origin.success.updated'));
        }
        return redirect()->signedRoute('cadastro.origem_produto.index')->with('error', __('labels.product_origin.error.not_updated'));
    }

    public function destroy(OrigemProduto $origemProduto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.destroy'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.origem_produto.destroy', compact('origemProduto', 'bloquearCampos'));
    }

    public function delete(OrigemProduto $origemProduto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.destroy'), 403, 'Acesso não autorizado');
        if($origemProduto->delete()) {
            return redirect()->signedRoute('cadastro.origem_produto.index')->with('success', __('labels.product_origin.success.deleted'));
        }
        return redirect()->signedRoute('cadastro.origem_produto.index')->with('error', __('labels.product_origin.error.not_deleted'));
    }

    public function history(OrigemProduto $origemProduto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.origem_produto.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $origemProduto->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.origem_produto.history', compact('origemProduto', 'bloquearCampos'));
    }

    public function historyDetails(OrigemProduto $origemProduto, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.origem_produto.history')) {
            abort(403);
        }
        $historico = OrigemProdutoHistorico::findOrFail($historicoId);
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
            'id' => __('labels.product_origin.history.fields.id'),
            'codigo' => __('labels.product_origin.history.fields.codigo'),
            'descricao' => __('labels.product_origin.history.fields.descricao'),
            'situacao_id' => __('labels.product_origin.history.fields.situacao_id'),
            'created_at' => __('labels.product_origin.history.fields.created_at'),
            'updated_at' => __('labels.product_origin.history.fields.updated_at'),
            'deleted_at' => __('labels.product_origin.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
