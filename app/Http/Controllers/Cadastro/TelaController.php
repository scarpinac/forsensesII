<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Tela\StoreRequest;
use App\Http\Requests\Cadastro\Tela\UpdateRequest;
use App\Models\Cadastro\Tela;
use App\Models\Cadastro\TelaHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelaController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.index'), 403, 'Acesso não autorizado');
        $telas = Tela::with('cor')->paginate();
        return view('cadastro.tela.index', compact('telas'));
    }

    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.create'), 403, 'Acesso não autorizado');
        $cores = \App\Models\Cadastro\Cor::all();
        return view('cadastro.tela.create', compact('cores'));
    }

    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.store'), 403, 'Acesso não autorizado');
        $tela = Tela::create($request->validated());
        if($tela) {
            return redirect()->signedRoute('cadastro.tela.index')->with('success', __('labels.screen.success.created'));
        }
        return redirect()->signedRoute('cadastro.tela.index')->with('error', __('labels.screen.error.not_created'));
    }

    public function show(Tela $tela)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $tela->load('cor');
        return view('cadastro.tela.show', compact('tela', 'bloquearCampos'));
    }

    public function edit(Tela $tela)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.edit'), 403, 'Acesso não autorizado');
        $tela->load('cor');
        $cores = \App\Models\Cadastro\Cor::all();
        return view('cadastro.tela.edit', compact('tela', 'cores'));
    }

    public function update(UpdateRequest $request, Tela $tela)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.update'), 403, 'Acesso não autorizado');
        if($tela->update($request->validated())){
            return redirect()->signedRoute('cadastro.tela.index')->with('success', __('labels.screen.success.updated'));
        }
        return redirect()->signedRoute('cadastro.tela.index')->with('error', __('labels.screen.error.not_updated'));
    }

    public function destroy(Tela $tela)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.destroy'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.tela.destroy', compact('tela', 'bloquearCampos'));
    }

    public function delete(Tela $tela)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.destroy'), 403, 'Acesso não autorizado');
        if($tela->delete()) {
            return redirect()->signedRoute('cadastro.tela.index')->with('success', __('labels.screen.success.deleted'));
        }
        return redirect()->signedRoute('cadastro.tela.index')->with('error', __('labels.screen.error.not_deleted'));
    }

    public function history(Tela $tela)
    {
        abort_if (!Auth::user()->canAccess('cadastro.tela.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $tela->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.tela.history', compact('tela', 'bloquearCampos'));
    }

    public function historyDetails(Tela $tela, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.tela.history')) {
            abort(403);
        }
        $historico = TelaHistorico::findOrFail($historicoId);
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
            'id' => __('labels.screen.history.fields.id'),
            'descricao' => __('labels.screen.history.fields.descricao'),
            'cor_id' => __('labels.screen.history.fields.cor_id'),
            'created_at' => __('labels.screen.history.fields.created_at'),
            'updated_at' => __('labels.screen.history.fields.updated_at'),
            'deleted_at' => __('labels.screen.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
