<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Produto\StoreRequest;
use App\Http\Requests\Cadastro\Produto\UpdateRequest;
use App\Models\Cadastro\Produto;
use App\Models\Cadastro\ProdutoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdutoController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.index'), 403, 'Acesso não autorizado');
        $produtos = Produto::with(['familia', 'acabamento', 'tela', 'produtoBase'])->paginate();
        return view('cadastro.produto.index', compact('produtos'));
    }

    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.create'), 403, 'Acesso não autorizado');
        $familias = \App\Models\Cadastro\Familia::all();
        $acabamentos = \App\Models\Cadastro\Acabamento::with('cor')->get();
        $telas = \App\Models\Cadastro\Tela::with('cor')->get();
        $produtosBase = \App\Models\Cadastro\Produto::where('produtoBase', true)->get();
        $origens = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Origens');
        })->get();
        return view('cadastro.produto.create', compact('familias', 'acabamentos', 'telas', 'produtosBase', 'origens'));
    }

    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.store'), 403, 'Acesso não autorizado');
        $produto = Produto::create($request->validated());
        if($produto) {
            return redirect()->signedRoute('cadastro.produto.index')->with('success', __('labels.product.success.created'));
        }
        return redirect()->signedRoute('cadastro.produto.index')->with('error', __('labels.product.error.not_created'));
    }

    public function show(Produto $produto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $produto->load(['familia', 'acabamento', 'tela', 'produtoBase']);
        return view('cadastro.produto.show', compact('produto', 'bloquearCampos'));
    }

    public function edit(Produto $produto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.edit'), 403, 'Acesso não autorizado');
        $produto->load(['familia', 'acabamento', 'tela', 'produtoBase']);
        $familias = \App\Models\Cadastro\Familia::all();
        $acabamentos = \App\Models\Cadastro\Acabamento::with('cor')->get();
        $telas = \App\Models\Cadastro\Tela::with('cor')->get();
        $produtosBase = \App\Models\Cadastro\Produto::where('produtoBase', true)->get();
        $origens = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Origens');
        })->get();
        return view('cadastro.produto.edit', compact('produto', 'familias', 'acabamentos', 'telas', 'produtosBase', 'origens'));
    }

    public function update(UpdateRequest $request, Produto $produto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.update'), 403, 'Acesso não autorizado');
        if($produto->update($request->validated())){
            return redirect()->signedRoute('cadastro.produto.index')->with('success', __('labels.product.success.updated'));
        }
        return redirect()->signedRoute('cadastro.produto.index')->with('error', __('labels.product.error.not_updated'));
    }

    public function destroy(Produto $produto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.destroy'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.produto.destroy', compact('produto', 'bloquearCampos'));
    }

    public function delete(Produto $produto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.destroy'), 403, 'Acesso não autorizado');
        if($produto->delete()) {
            return redirect()->signedRoute('cadastro.produto.index')->with('success', __('labels.product.success.deleted'));
        }
        return redirect()->signedRoute('cadastro.produto.index')->with('error', __('labels.product.error.not_deleted'));
    }

    public function history(Produto $produto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.produto.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $produto->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.produto.history', compact('produto', 'bloquearCampos'));
    }

    public function historyDetails(Produto $produto, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.produto.history')) {
            abort(403);
        }
        $historico = ProdutoHistorico::findOrFail($historicoId);
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
            'id' => __('labels.product.history.fields.id'),
            'descricao' => __('labels.product.history.fields.descricao'),
            'codigo' => __('labels.product.history.fields.codigo'),
            'quantidadeVolumes' => __('labels.product.history.fields.quantidadeVolumes'),
            'peso' => __('labels.product.history.fields.peso'),
            'altura' => __('labels.product.history.fields.altura'),
            'largura' => __('labels.product.history.fields.largura'),
            'comprimento' => __('labels.product.history.fields.comprimento'),
            'precoUnitario' => __('labels.product.history.fields.precoUnitario'),
            'descontoProduto' => __('labels.product.history.fields.descontoProduto'),
            'familia_id' => __('labels.product.history.fields.familia_id'),
            'acabamento_id' => __('labels.product.history.fields.acabamento_id'),
            'tela_id' => __('labels.product.history.fields.tela_id'),
            'produtoBase' => __('labels.product.history.fields.produtoBase'),
            'produtoBase_id' => __('labels.product.history.fields.produtoBase_id'),
            'especificacao' => __('labels.product.history.fields.especificacao'),
            'observacao' => __('labels.product.history.fields.observacao'),
            'permitirVenda' => __('labels.product.history.fields.permitirVenda'),
            'permitirTela' => __('labels.product.history.fields.permitirTela'),
            'codigoBarras' => __('labels.product.history.fields.codigoBarras'),
            'ncm' => __('labels.product.history.fields.ncm'),
            'cst' => __('labels.product.history.fields.cst'),
            'cest' => __('labels.product.history.fields.cest'),
            'origem_id' => __('labels.product.history.fields.origem_id'),
            'created_at' => __('labels.product.history.fields.created_at'),
            'updated_at' => __('labels.product.history.fields.updated_at'),
            'deleted_at' => __('labels.product.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
