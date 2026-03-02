<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Acabamento\StoreRequest;
use App\Http\Requests\Cadastro\Acabamento\UpdateRequest;
use App\Models\Cadastro\Acabamento;
use App\Models\Cadastro\AcabamentoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcabamentoController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.index'), 403, 'Acesso não autorizado');
        $acabamentos = Acabamento::with(['tipoAcabamento', 'cor'])->paginate();
        return view('cadastro.acabamento.index', compact('acabamentos'));
    }

    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.create'), 403, 'Acesso não autorizado');
        $tiposAcabamento = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Acabamento');
        })->get();
        $cores = \App\Models\Cadastro\Cor::all();
        return view('cadastro.acabamento.create', compact('tiposAcabamento', 'cores'));
    }

    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.store'), 403, 'Acesso não autorizado');
        $acabamento = Acabamento::create($request->validated());
        if($acabamento) {
            return redirect()->signedRoute('cadastro.acabamento.index')->with('success', __('labels.finish.success.created'));
        }
        return redirect()->signedRoute('cadastro.acabamento.index')->with('error', __('labels.finish.error.not_created'));
    }

    public function show(Acabamento $acabamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $acabamento->load(['tipoAcabamento', 'cor']);
        return view('cadastro.acabamento.show', compact('acabamento', 'bloquearCampos'));
    }

    public function edit(Acabamento $acabamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.edit'), 403, 'Acesso não autorizado');
        $acabamento->load(['tipoAcabamento', 'cor']);
        $tiposAcabamento = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Acabamento');
        })->get();
        $cores = \App\Models\Cadastro\Cor::all();
        return view('cadastro.acabamento.edit', compact('acabamento', 'tiposAcabamento', 'cores'));
    }

    public function update(UpdateRequest $request, Acabamento $acabamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.update'), 403, 'Acesso não autorizado');
        if($acabamento->update($request->validated())){
            return redirect()->signedRoute('cadastro.acabamento.index')->with('success', __('labels.finish.success.updated'));
        }
        return redirect()->signedRoute('cadastro.acabamento.index')->with('error', __('labels.finish.error.not_updated'));
    }

    public function destroy(Acabamento $acabamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.destroy'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.acabamento.destroy', compact('acabamento', 'bloquearCampos'));
    }

    public function delete(Acabamento $acabamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.destroy'), 403, 'Acesso não autorizado');
        if($acabamento->delete()) {
            return redirect()->signedRoute('cadastro.acabamento.index')->with('success', __('labels.finish.success.deleted'));
        }
        return redirect()->signedRoute('cadastro.acabamento.index')->with('error', __('labels.finish.error.not_deleted'));
    }

    public function history(Acabamento $acabamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.acabamento.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $acabamento->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.acabamento.history', compact('acabamento', 'bloquearCampos'));
    }

    public function historyDetails(Acabamento $acabamento, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.acabamento.history')) {
            abort(403);
        }
        $historico = AcabamentoHistorico::findOrFail($historicoId);
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
            'id' => __('labels.finish.history.fields.id'),
            'descricao' => __('labels.finish.history.fields.descricao'),
            'tipoAcabamento_id' => __('labels.finish.history.fields.tipoAcabamento_id'),
            'cor_id' => __('labels.finish.history.fields.cor_id'),
            'created_at' => __('labels.finish.history.fields.created_at'),
            'updated_at' => __('labels.finish.history.fields.updated_at'),
            'deleted_at' => __('labels.finish.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
