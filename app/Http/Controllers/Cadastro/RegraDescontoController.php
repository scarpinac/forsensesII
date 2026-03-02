<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\RegraDesconto\StoreRequest;
use App\Http\Requests\Cadastro\RegraDesconto\UpdateRequest;
use App\Models\Cadastro\RegraDesconto;
use App\Models\Cadastro\RegraDescontoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegraDescontoController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.index'), 403, 'Acesso não autorizado');
        $regrasDesconto = RegraDesconto::paginate();
        return view('cadastro.regra_desconto.index', compact('regrasDesconto'));
    }

    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.create'), 403, 'Acesso não autorizado');
        return view('cadastro.regra_desconto.create');
    }

    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.store'), 403, 'Acesso não autorizado');
        $regraDesconto = RegraDesconto::create($request->validated());
        if($regraDesconto) {
            return redirect()->signedRoute('cadastro.regra_desconto.index')->with('success', __('labels.discount_rule.success.created'));
        }
        return redirect()->signedRoute('cadastro.regra_desconto.index')->with('error', __('labels.discount_rule.error.not_created'));
    }

    public function show(RegraDesconto $regraDesconto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.regra_desconto.show', compact('regraDesconto', 'bloquearCampos'));
    }

    public function edit(RegraDesconto $regraDesconto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.edit'), 403, 'Acesso não autorizado');
        return view('cadastro.regra_desconto.edit', compact('regraDesconto'));
    }

    public function update(UpdateRequest $request, RegraDesconto $regraDesconto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.update'), 403, 'Acesso não autorizado');
        if($regraDesconto->update($request->validated())){
            return redirect()->signedRoute('cadastro.regra_desconto.index')->with('success', __('labels.discount_rule.success.updated'));
        }
        return redirect()->signedRoute('cadastro.regra_desconto.index')->with('error', __('labels.discount_rule.error.not_updated'));
    }

    public function destroy(RegraDesconto $regraDesconto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.destroy'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.regra_desconto.destroy', compact('regraDesconto', 'bloquearCampos'));
    }

    public function delete(RegraDesconto $regraDesconto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.destroy'), 403, 'Acesso não autorizado');
        if($regraDesconto->delete()) {
            return redirect()->signedRoute('cadastro.regra_desconto.index')->with('success', __('labels.discount_rule.success.deleted'));
        }
        return redirect()->signedRoute('cadastro.regra_desconto.index')->with('error', __('labels.discount_rule.error.not_deleted'));
    }

    public function history(RegraDesconto $regraDesconto)
    {
        abort_if (!Auth::user()->canAccess('cadastro.regra_desconto.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $regraDesconto->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.regra_desconto.history', compact('regraDesconto', 'bloquearCampos'));
    }

    public function historyDetails(RegraDesconto $regraDesconto, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.regra_desconto.history')) {
            abort(403);
        }
        $historico = RegraDescontoHistorico::findOrFail($historicoId);
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
            'id' => __('labels.discount_rule.history.fields.id'),
            'descricao' => __('labels.discount_rule.history.fields.descricao'),
            'periodo' => __('labels.discount_rule.history.fields.periodo'),
            'valorBase' => __('labels.discount_rule.history.fields.valorBase'),
            'descontoAcrescido' => __('labels.discount_rule.history.fields.descontoAcrescido'),
            'created_at' => __('labels.discount_rule.history.fields.created_at'),
            'updated_at' => __('labels.discount_rule.history.fields.updated_at'),
            'deleted_at' => __('labels.discount_rule.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
